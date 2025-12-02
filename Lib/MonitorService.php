<?php
<?php
declare(strict_types=1);

namespace Lib;

use Model\Website;
use Model\CheckResult;

class MonitorService
{
    /**
     * Get status based on HTTP code
     */
    public static function getStatus(int $code): string
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
     * Get status icon
     */
    public static function getStatusIcon(int $code): string
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
     * Check multiple websites concurrently
     */
    public static function checkWebsitesConcurrent(array $websites): array
    {
        $mh = curl_multi_init();
        $handles = [];
        $results = [];
        
        // Initialize all requests
        foreach ($websites as $website) {
            $ch = curl_init($website->getUrl());
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_NOBODY => true,
                CURLOPT_TIMEOUT => $website->getTimeout(),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            
            curl_multi_add_handle($mh, $ch);
            $handles[$website->getId()] = ['ch' => $ch, 'website' => $website];
        }
        
        // Execute all requests
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);
        
        // Collect results
        foreach ($handles as $websiteId => $handle) {
            $ch = $handle['ch'];
            $website = $handle['website'];
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $totalTime = (float) curl_getinfo($ch, CURLINFO_TOTAL_TIME);
            
            $status = self::getStatus($httpCode);
            $icon = self::getStatusIcon($httpCode);
            
            $result = new CheckResult(
                $website->getId(),
                $website->getUrl(),
                $website->getName(),
                $httpCode,
                $totalTime,
                $status,
                $icon
            );
            
            $results[] = $result;
            
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        
        curl_multi_close($mh);
        
        return $results;
    }

    /**
     * Check single website
     */
    public static function checkWebsite(Website $website): CheckResult
    {
        $startTime = microtime(true);
        
        $ch = curl_init($website->getUrl());
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => $website->getTimeout(),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $totalTime = microtime(true) - $startTime;
        curl_close($ch);
        
        $status = self::getStatus($httpCode);
        $icon = self::getStatusIcon($httpCode);
        
        return new CheckResult(
            $website->getId(),
            $website->getUrl(),
            $website->getName(),
            $httpCode,
            $totalTime,
            $status,
            $icon
        );
    }
}
?>