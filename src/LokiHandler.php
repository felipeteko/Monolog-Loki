<?php

namespace TekoEstudio\Loki;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\LineFormatter;

class LokiHandler extends AbstractProcessingHandler
{
    protected string $lokiUrl;
    protected string $username;
    protected string $password;
    protected array $labels;

    public function __construct(
        string $lokiUrl,
        string $username,
        string $password,
        array $labels = [],
        int|string $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->lokiUrl = rtrim($lokiUrl, '/');
        $this->username = $username;
        $this->password = $password;
        $this->labels = array_merge(['job' => 'php'], $labels);
    }

    protected function write(LogRecord $record): void
    {
        $timestamp = sprintf("%.0f", microtime(true) * 1_000_000_000);

        $stream = [
            'stream' => $this->labels,
            'values' => [
                [$timestamp, rtrim($record->formatted) . "\n"]
            ]
        ];

        $payload = json_encode(['streams' => [$stream]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        $ch = curl_init("{$this->lokiUrl}/loki/api/v1/push");
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec($ch);

        if ($response === false) {
            error_log('LokiHandler cURL error: ' . curl_error($ch));
        } else {
            error_log('LokiHandler response: ' . $response);
        }

        curl_close($ch);
    }


    protected function getDefaultFormatter(): LineFormatter
    {
        return new LineFormatter(null, null, true, true);
    }
}
