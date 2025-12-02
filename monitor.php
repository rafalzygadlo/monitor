<?php
declare(strict_types=1);

require_once 'index.php';

use Lib\MonitorService;
use Model\Website;

// Example websites (in production, load from database)
$websitesData = [
    ['id' => 1, 'name' => 'TEC Point Main', 'url' => 'https://new.tec-point.de', 'timeout' => 10],
    ['id' => 2, 'name' => 'TimeMaster App', 'url' => 'https://app.insystec.de/timemaster', 'timeout' => 10],
    ['id' => 3, 'name' => 'HR System', 'url' => 'https://hr.tec-point.de', 'timeout' => 10],
    ['id' => 4, 'name' => 'Service Portal', 'url' => 'https://service.tec-point.de', 'timeout' => 10],
    ['id' => 5, 'name' => 'Laser System', 'url' => 'https://laser.tec-point.de', 'timeout' => 10],
];

// Convert to Website objects
$websites = array_map(fn($data) => Website::fromArray($data), $websitesData);

// Run monitoring
$results = MonitorService::checkWebsitesConcurrent($websites);

// Display results
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monitor - Website Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
        }
        .status-row {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .ok {
            border-left-color: #28a745;
            background-color: #d4edda;
        }
        .redirect {
            border-left-color: #ffc107;
            background-color: #fff3cd;
        }
        .error {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
        .icon {
            font-size: 1.5em;
            margin-right: 10px;
        }
        .info {
            margin-top: 8px;
            font-size: 0.9em;
            color: #666;
        }
        .timestamp {
            font-size: 0.85em;
            color: #999;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Website Monitor</h1>
        
        <?php foreach ($results as $result): ?>
            <?php
                $class = $result->isOk() ? 'ok' : ($result->getStatus() === 'Redirect' ? 'redirect' : 'error');
            ?>
            <div class="status-row <?php echo $class; ?>">
                <strong>
                    <span class="icon"><?php echo $result->getIcon(); ?></span>
                    <?php echo htmlspecialchars($result->getName()); ?>
                </strong>
                <div class="info">
                    <strong>URL:</strong> <?php echo htmlspecialchars($result->getUrl()); ?><br>
                    <strong>Status:</strong> <?php echo $result->getStatus(); ?> (<?php echo $result->getHttpCode(); ?>) | 
                    <strong>Response Time:</strong> <?php echo number_format($result->getResponseTime(), 2); ?>s
                </div>
                <div class="timestamp">
                    Checked: <?php echo $result->getTimestamp(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
