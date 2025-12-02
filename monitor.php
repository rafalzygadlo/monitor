<?php
declare(strict_types=1);

/**
 * Get status icon based on HTTP code
 */
function getStatusIcon(int $code): string
{
    return match (true) {
        $code >= 200 && $code < 300 => '✅',
        $code >= 300 && $code < 400 => '🔄',
        $code >= 400 && $code < 500 => '⚠️',
        $code >= 500 => '❌',
        default => '❓'
    };
}

/**
 * Get status text based on HTTP code
 */
function getStatusText(int $code): string
{
    return match (true) {
        $code >= 200 && $code < 300 => 'OK',
        $code >= 300 && $code < 400 => 'Redirect',
        $code >= 400 && $code < 500 => 'Client Error',
        $code >= 500 => 'Server Error',
        default => 'Unknown'
    };
}

/**
 * Check website availability and response time
 */
function checkWebsite(string $url): array
{
    $startTime = microtime(true);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $totalTime = microtime(true) - $startTime;
    
    curl_close($ch);
    
    return ['url' => $url, 'code' => $httpCode, 'time' => $totalTime];
}

/**
 * Check service availability via socket connection
 */
function checkService(string $host, int $port, int $timeout = 5): array
{
    $startTime = microtime(true);
    $result = 1;
    $response = null;
    
    $fp = fsockopen($host, $port, $errno, $errstr, $timeout);

    if ($fp) {
        $result = 0;
        $response = fgets($fp, 1024);
        fclose($fp);
    }
    
    $totalTime = microtime(true) - $startTime;
    
    return [
        'host' => $host,
        'port' => $port,
        'code' => $result,
        'time' => $totalTime,
        'response' => $response
    ];
}

/**
 * Check multiple websites concurrently using cURL multi
 */
function checkWebsitesConcurrent(array $urls): array
{
    $mh = curl_multi_init();
    $handles = [];
    $results = [];
    
    // Initialize all requests
    foreach ($urls as $url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        curl_multi_add_handle($mh, $ch);
        $handles[$url] = $ch;
    }
    
    // Execute all requests
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running > 0);
    
    // Collect results
    foreach ($handles as $url => $ch) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        
        $results[] = ['url' => $url, 'code' => $httpCode, 'time' => $totalTime];
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    
    curl_multi_close($mh);
    
    return $results;
}

// Monitor websites concurrently
$websites = [
    'https://new.tec-point.de',
    'https://app.insystec.de/timemaster',
    'https://hr.tec-point.de',
    'https://service.tec-point.de',
    'https://laser.tec-point.de'
];

$results = checkWebsitesConcurrent($websites);

echo "<style>
    body { font-family: Arial, sans-serif; }
    .status-row { padding: 10px; margin: 5px 0; border-radius: 5px; }
    .ok { background-color: #d4edda; }
    .error { background-color: #f8d7da; }
</style>";

foreach ($results as $result) {
    $icon = getStatusIcon($result['code']);
    $status = getStatusText($result['code']);
    $isOk = $result['code'] >= 200 && $result['code'] < 300;
    $class = $isOk ? 'ok' : 'error';
    
    echo sprintf(
        "<div class='status-row %s'>%s %s | Status: %s (%d) | Time: %.2fs</div>",
        $class,
        $icon,
        $result['url'],
        $status,
        $result['code'],
        $result['time']
    );
}
?>
