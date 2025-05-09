<?php
class SystemSettings
{
    private static $instance = null;
    private $conn;
    private $cache = [];

    private function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public static function getInstance(PDO $db): self
    {
        if (self::$instance === null) {
            self::$instance = new self($db);
        }
        return self::$instance;
    }

    public function get(string $key, $default = null): string
    {
        // Use cached value if available
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $stmt = $this->conn->prepare("SELECT value FROM system_settings WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->cache[$key] = $result ? $result['value'] : $default;
        return $this->cache[$key];
    }

    public function update(string $key, string $value): bool
    {
        $stmt = $this->conn->prepare("UPDATE system_settings SET value = :value WHERE name = :name");
        $success = $stmt->execute([':value' => $value, ':name' => $key]);

        if ($success) {
            $this->cache[$key] = $value; // Update cache
        }

        return $success;
    }
}
