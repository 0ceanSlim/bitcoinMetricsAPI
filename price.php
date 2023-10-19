<?php
// API endpoints and API keys
$coingecko_url = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd';
$coinmarketcap_url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?id=1&convert=USD';
$blockchain_info_url = 'https://blockchain.info/ticker';
$coinbase_url = 'https://api.coinbase.com/v2/prices/BTC-USD/spot';
$coinmarketcap_api_key = 'YOUR_COINMARKETCAP_API_KEY'; // Replace with your CoinMarketCap API key

// Initialize variables for storing prices
$coingecko_price = null;
$coinmarketcap_price = null;
$blockchain_15m_price = null;
$coinbase_price = null;

// Fetch data from CoinGecko
$coingecko_response = @file_get_contents($coingecko_url); // Use '@' to suppress warnings
if ($coingecko_response !== false) {
    $coingecko_data = json_decode($coingecko_response, true);
}

// Fetch data from CoinMarketCap
$coinmarketcap_headers = [
    'X-CMC_PRO_API_KEY: ' . $coinmarketcap_api_key
];

$coinmarketcap_context = stream_context_create([
    'http' => [
        'header' => $coinmarketcap_headers
    ]
]);

$coinmarketcap_response = @file_get_contents($coinmarketcap_url, false, $coinmarketcap_context); // Use '@' to suppress warnings
if ($coinmarketcap_response !== false) {
    $coinmarketcap_data = json_decode($coinmarketcap_response, true);
}

// Fetch data from blockchain.info
$blockchain_info_response = @file_get_contents($blockchain_info_url); // Use '@' to suppress warnings
if ($blockchain_info_response !== false) {
    $blockchain_info_data = json_decode($blockchain_info_response, true);
}

// Fetch data from Coinbase
$coinbase_response = @file_get_contents($coinbase_url); // Use '@' to suppress warnings
if ($coinbase_response !== false) {
    $coinbase_data = json_decode($coinbase_response, true);
}

// Extract the "15m" price from blockchain.info
if (isset($blockchain_info_data['USD']['15m'])) {
    $blockchain_15m_price = $blockchain_info_data['USD']['15m'];
}

// Extract the Coinbase amount
if (isset($coinbase_data['data']['amount'])) {
    $coinbase_price = (float)$coinbase_data['data']['amount'];
}

// Check if data was successfully retrieved from CoinGecko
if (isset($coingecko_data['bitcoin']['usd'])) {
    $coingecko_price = $coingecko_data['bitcoin']['usd'];
}

// Check if data was successfully retrieved from CoinMarketCap
if (isset($coinmarketcap_data['data']['1']['quote']['USD']['price'])) {
    $coinmarketcap_price = $coinmarketcap_data['data']['1']['quote']['USD']['price'];
}

// Check if data was retrieved from at least one source
if ($coingecko_price !== null || $coinmarketcap_price !== null || $blockchain_15m_price !== null || $coinbase_price !== null) {
    // Calculate the average price using available data
    $valid_data_count = 0;
    $sum_prices = 0;

    if ($coingecko_price !== null) {
        $sum_prices += $coingecko_price;
        $valid_data_count++;
    }

    if ($coinmarketcap_price !== null) {
        $sum_prices += $coinmarketcap_price;
        $valid_data_count++;
    }

    if ($blockchain_15m_price !== null) {
        $sum_prices += $blockchain_15m_price;
        $valid_data_count++;
    }

    if ($coinbase_price !== null) {
        $sum_prices += $coinbase_price;
        $valid_data_count++;
    }

    $average_price = $valid_data_count > 0 ? $sum_prices / $valid_data_count : null;

    // Format the average price to display only two decimal places
    if ($average_price !== null) {
        $average_price = number_format($average_price, 2);
    }

    // Create the response array
    $responseArray = [
        'Price' => $average_price
    ];

    // Return the response as JSON
    echo json_encode($responseArray);
} else {
    // Handle the case where data retrieval failed from all sources
    echo json_encode(['error' => 'Unable to fetch Bitcoin price from all sources.']);
}
?>
