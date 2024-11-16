<?php
// Ensure SOAP and PDO extensions are loaded
if (!extension_loaded('soap') || !extension_loaded('pdo')) {
    die("Required extensions (SOAP, PDO) are not loaded.");
}

// Include necessary files
require_once 'config/database.php';
require_once 'services/MNBExchangeRateService.php';

// Get supported currencies
$service = new MNBExchangeRateService();
$currencies = $service->getSupportedCurrencies();

// Default values
$selectedDate = date('Y-m-d');
$sourceCurrency = 'EUR';
$targetCurrency = 'HUF';
$selectedYear = date('Y');
$selectedMonth = date('m');

// Initialize result variables
$dailyRate = null;
$monthlyRates = null;
$dailyRateError = null;
$monthlyRatesError = null; // Ensure this is initialized

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Daily rate query
    if (isset($_POST['get_daily_rate'])) {
        $selectedDate = $_POST['date'];
        $sourceCurrency = $_POST['source_currency'];
        $targetCurrency = $_POST['target_currency'];
        
        $dailyRate = $service->getExchangeRateOnDay($selectedDate, $sourceCurrency, $targetCurrency);
        
        if ($dailyRate === null) {
            $dailyRateError = "No exchange rate found for the selected date and currencies.";
        }
    }
    
    // Monthly rates query
    if (isset($_POST['get_monthly_rates'])) {
        $selectedYear = $_POST['year'];
        $selectedMonth = $_POST['month'];
        $sourceCurrency = $_POST['source_currency'];
        $targetCurrency = $_POST['target_currency'];
        
        $monthlyRates = $service->getMonthlyExchangeRates($selectedYear, $selectedMonth, $sourceCurrency, $targetCurrency);
        
        if ($monthlyRates === null || empty($monthlyRates)) {
            $monthlyRatesError = "No monthly rates found for the selected period.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rates Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #f4f6f9;
            --card-background: #ffffff;
            --text-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card {
            background-color: var(--card-background);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 25px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .form-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-control {
            display: flex;
            flex-direction: column;
        }

        .form-control label {
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
        }

        .form-control select,
        .form-control input {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control select:focus,
        .form-control input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .results-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .rate-display {
            background-color: var(--secondary-color);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .chart-container {
            position: relative;
            height: 400px;
        }

        .rates-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .rates-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
        }

        .rates-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .rates-table tr:last-child td {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .form-section,
            .results-section {
                grid-template-columns: 1fr;
            }
        }

        .error-message {
            background-color: #ff6b6b;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard">
            <!-- Daily Rate Section -->
            <div class="card">
                <h2>Daily Exchange Rate</h2>
                
                <?php if (isset($dailyRateError)): ?>
                    <div class="error-message"><?= $dailyRateError ?></div>
                <?php endif; ?>

                <form method="post" class="form-section">
                    <div class="form-control">
                        <label>Date</label>
                        <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>" required>
                    </div>
                    
                    <div class="form-control">
                        <label>Source Currency</label>
                        <select name="source_currency">
                            <?php foreach($currencies as $currency): ?>
                                <option <?= $currency == $sourceCurrency ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($currency) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label>Target Currency</label>
                        <select name="target_currency">
                            <?php foreach($currencies as $currency): ?>
                                <option <?= $currency == $targetCurrency ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($currency) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-control" style="align-self: flex-end;">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn" name="get_daily_rate">Get Rate</button>
                    </div>
                </form>

                <?php if ($dailyRate !== null): ?>
                    <div class="rate-display">
                        <h3>Exchange Rate</h3>
                        <p>
                            1 <?= htmlspecialchars($sourceCurrency) ?> = 
                            <?= number_format($dailyRate, 4) ?> 
                            <?= htmlspecialchars($targetCurrency) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
<!-- Monthly Rates Section -->
<div class="card">
                <h2>Monthly Exchange Rates</h2>
                
                <?php if ($monthlyRatesError): ?>
                    <div class="error-message"><?= $monthlyRatesError ?></div>
                <?php endif; ?>

                <form method="post" class="form-section">
                    <div class="form-control">
                        <label>Year</label>
                        <select name="year">
                            <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                <option <?= $y == $selectedYear ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label>Month</label>
                        <select name="month">
                            <?php for($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= sprintf('%02d', $m) ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label>Source Currency</label>
                        <select name="source_currency">
                            <?php foreach($currencies as $currency): ?>
                                <option <?= $currency == $sourceCurrency ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($currency) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label>Target Currency</label>
                        <select name="target_currency">
                            <?php foreach($currencies as $currency): ?>
                                <option <?= $currency == $targetCurrency ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($currency) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-control" style="align-self: flex-end;">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn" name="get_monthly_rates">Get Rates</button>
                    </div>
                </form>

                <?php if ($monthlyRates !== null && !empty($monthlyRates)): ?>
                    <div class="results-section">
                        <div class="chart-container">
                            <canvas id="exchangeRateChart"></canvas>
                        </div>

                        <table class="rates-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($monthlyRates as $rate): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($rate['date']) ?></td>
                                        <td><?= number_format($rate['rate'], 4) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    // Chart rendering logic with error handling
    <?php if ($monthlyRates !== null && !empty($monthlyRates)): ?>
        const ctx = document.getElementById('exchangeRateChart').getContext('2d');
        
        // Prepare chart data
        const labels = <?= json_encode(array_column($monthlyRates, 'date')) ?>;
        const rateData = <?= json_encode(array_column($monthlyRates, 'rate')) ?>;
        
        // Check if we have data
        if (labels.length > 0 && rateData.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '<?= $sourceCurrency ?> to <?= $targetCurrency ?> Rate',
                        data: rateData,
                        borderColor: 'var(--primary-color)',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: 'var(--primary-color)',
                        pointBorderColor: 'white',
                        pointHoverBackgroundColor: 'white',
                        pointHoverBorderColor: 'var(--primary-color)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Exchange Rate Trend',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'Exchange Rate'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        } else {
            // If no data, display a message
            document.getElementById('exchangeRateChart').innerHTML = 
                '<p style="text-align: center; color: var(--text-color);">No chart data available</p>';
        }
    <?php endif; ?>
    </script>
</body>
</html>