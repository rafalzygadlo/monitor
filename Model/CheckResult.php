<?php
<?php
declare(strict_types=1);

namespace Model;

class CheckResult
{
    private int $websiteId;
    private string $url;
    private string $name;
    private int $httpCode;
    private float $responseTime;
    private string $status;
    private string $icon;
    private string $timestamp;

    public function __construct(
        int $websiteId,
        string $url,
        string $name,
        int $httpCode,
        float $responseTime,
        string $status,
        string $icon
    ) {
        $this->websiteId = $websiteId;
        $this->url = $url;
        $this->name = $name;
        $this->httpCode = $httpCode;
        $this->responseTime = $responseTime;
        $this->status = $status;
        $this->icon = $icon;
        $this->timestamp = date('Y-m-d H:i:s');
    }

    public function getWebsiteId(): int
    {
        return $this->websiteId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getResponseTime(): float
    {
        return $this->responseTime;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function isOk(): bool
    {
        return $this->httpCode >= 200 && $this->httpCode < 300;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'website_id' => $this->websiteId,
            'url' => $this->url,
            'name' => $this->name,
            'http_code' => $this->httpCode,
            'response_time' => $this->responseTime,
            'status' => $this->status,
            'icon' => $this->icon,
            'timestamp' => $this->timestamp
        ];
    }
}
?>