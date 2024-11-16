<?php
require_once 'config/database.php';
require_once 'services/MNBExchangeRateService.php';

// Initialize service
$service = new MNBExchangeRateService();

// Currencies to populate
$currencies = $service->getSupportedCurrencies();

// Populate rates for the last 6 months
$endDate = date('Y-m-d');
$startDate = date('Y-m-d', strtotime('-6 months'));

// Fetch rates for all currency combinations
foreach ($currencies as $sourceCurrency) {
    foreach ($currencies as $targetCurrency) {
        if ($sourceCurrency !== $targetCurrency) {
            echo "Fetching rates for {$sourceCurrency} to {$targetCurrency}...\n";
            $service->getMonthlyExchangeRates(
                date('Y', strtotime($startDate)), 
                date('m', strtotime($startDate)), 
                $sourceCurrency, 
                $targetCurrency
            );
        }
    }
}

echo "Data population complete.\n";