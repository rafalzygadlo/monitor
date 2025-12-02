<?php
function checkWebsite($url) 
{
    $startTime = microtime(true);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // nie pobieraj zawartości strony
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // maksymalny czas oczekiwania
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // a
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $totalTime = microtime(true) - $startTime;
    
    curl_close($ch);
    
    return array('code' => $httpCode, 'time' => $totalTime);
    
}

function checkService($host, $port, $timeout = 5) 
{
    $startTime = microtime(true);
    $result = 1;
    
    $fp = fsockopen($host, $port, $errno, $errstr, $timeout);

    if ($fp) 
    {    
        // Odbieramy odpowiedź z serwera
        $result = 0;
        $response = fgets($fp, 1024);
        $totalTime = microtime(true) - $startTime;
            
        // Zamykamy połączenie
        fclose($fp);
    }
    
    return array('code' => $result, 'time' => $totalTime, 'response' => $response) ;
}


print_r(checkWebsite('new.tec-point.de'));
print "<br>";
print_r(checkWebsite('app.insystec.de/timemaster'));
print "<br>";
print_r(checkService('maxkod.pl', 57185));
print_r(checkService('maxkod.pl', 465));
