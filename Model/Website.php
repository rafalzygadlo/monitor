<?php
<?php
declare(strict_types=1);

namespace Model;

class Website
{
    private int $id;
    private string $name;
    private string $url;
    private int $timeout;
    private bool $active;

    public function __construct(
        int $id,
        string $name,
        string $url,
        int $timeout = 10,
        bool $active = true
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->timeout = $timeout;
        $this->active = $active;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Create Website from database array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['url'],
            $data['timeout'] ?? 10,
            $data['active'] ?? true
        );
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'timeout' => $this->timeout,
            'active' => $this->active
        ];
    }
}
?>