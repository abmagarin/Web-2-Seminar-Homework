<?php
class MNBExchangeRateService {
    private $client;
    private $db;

    public function __construct() {
        // Database connection
        $this->db = DatabaseConfig::getConnection();

        // SOAP Client
        try {
            $this->client = new SoapClient("http://www.mnb.hu/arfolyamok.asmx?WSDL", [
                'trace' => 1,
                'exceptions' => true
            ]);
        } catch (Exception $e) {
            die("SOAP Client Error: " . $e->getMessage());
        }
    }

    // Fetch and cache exchange rates
    private function fetchAndCacheRates($startDate, $endDate, $sourceCurrency, $targetCurrency) {
        try {
            $result = $this->client->GetExchangeRates([
                'startDate' => $startDate,
                'endDate' => $endDate,
                'currencyNames' => implode(',', [$sourceCurrency, $targetCurrency])
            ]);

            $xmlResult = simplexml_load_string($result->GetExchangeRatesResult);
            
            if (!$xmlResult || !isset($xmlResult->Day)) {
                return null;
            }

            // Prepare insert statement
            $stmt = $this->db->prepare(
                "INSERT IGNORE INTO exchange_rates (date, source_currency, target_currency, rate) 
                VALUES (:date, :source, :target, :rate)"
            );

            foreach ($xmlResult->Day as $day) {
                $date = (string)$day['date'];
                $rates = $day->Rate;
                
                if (count($rates) < 2) continue;

                $sourceRate = floatval(str_replace(',', '.', (string)$rates[0]));
                $targetRate = floatval(str_replace(',', '.', (string)$rates[1]));
                
                $rate = $sourceRate / $targetRate;

                $stmt->execute([
                    ':date' => $date,
                    ':source' => $sourceCurrency,
                    ':target' => $targetCurrency,
                    ':rate' => $rate
                ]);
            }

            return true;
        } catch (Exception $e) {
            error_log("Fetch and Cache Error: " . $e->getMessage());
            return false;
        }
    }

    // Get exchange rates for a specific day
    public function getExchangeRateOnDay($date, $sourceCurrency, $targetCurrency) {
        try {
            // First, try to get from database
            $stmt = $this->db->prepare(
                "SELECT rate FROM exchange_rates 
                WHERE date = :date AND source_currency = :source AND target_currency = :target"
            );
            $stmt->execute([
                ':date' => $date,
                ':source' => $sourceCurrency,
                ':target' => $targetCurrency
            ]);
            $cachedRate = $stmt->fetchColumn();

            // If not in database, fetch and cache
            if (!$cachedRate) {
                $this->fetchAndCacheRates($date, $date, $sourceCurrency, $targetCurrency);
                
                // Try fetching again
                $stmt->execute([
                    ':date' => $date,
                    ':source' => $sourceCurrency,
                    ':target' => $targetCurrency
                ]);
                $cachedRate = $stmt->fetchColumn();
            }

            return $cachedRate;
        } catch (Exception $e) {
            error_log("Exchange Rate Error: " . $e->getMessage());
            return null;
        }
    }

    // Get monthly exchange rates
    public function getMonthlyExchangeRates($year, $month, $sourceCurrency, $targetCurrency) {
        try {
            $startDate = date('Y-m-d', strtotime("{$year}-{$month}-01"));
            $endDate = date('Y-m-d', strtotime('last day of ' . $startDate));

            // First, try to get from database
            $stmt = $this->db->prepare(
                "SELECT date, rate FROM exchange_rates 
                WHERE date BETWEEN :start AND :end 
                AND source_currency = :source AND target_currency = :target 
                ORDER BY date"
            );
            $stmt->execute([
                ':start' => $startDate,
                ':end' => $endDate,
                ':source' => $sourceCurrency,
                ':target' => $targetCurrency
            ]);
            $cachedRates = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If no rates in database, fetch and cache
            if (empty($cachedRates)) {
                $this->fetchAndCacheRates($startDate, $endDate, $sourceCurrency, $targetCurrency);
                
                // Try fetching again
                $stmt->execute([
                    ':start' => $startDate,
                    ':end' => $endDate,
                    ':source' => $sourceCurrency,
                    ':target' => $targetCurrency
                ]);
                $cachedRates = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $cachedRates;
        } catch (Exception $e) {
            error_log("Monthly Rates Error: " . $e->getMessage());
            return null;
        }
    }

    // Get supported currencies
    public function getSupportedCurrencies() {
        return ['EUR', 'USD', 'HUF', 'GBP', 'CHF'];
    }
}