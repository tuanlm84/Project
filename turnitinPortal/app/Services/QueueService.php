<?php

namespace App\Services;

use Redis;

class QueueService
{
    protected $redis;

    public function __construct()
    {
        // Load .env nếu chưa có
        if (!isset($_ENV['REDIS_HOST'])) {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        $host = $_ENV['REDIS_HOST'];
        $port = $_ENV['REDIS_PORT'];
        $password = $_ENV['REDIS_PASSWORD'] ?? null;

        $this->redis = new Redis();
        $this->redis->connect($host, (int)$port);

        if ($password) {
            $this->redis->auth($password);
        }
    }

    public function pushJob(array $job)
    {
        $payload = json_encode($job);
        $this->redis->rPush('turnitin_jobs', $payload);
    }
}
