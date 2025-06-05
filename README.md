# Monolog Loki Handler

Este paquete te permite enviar logs directamente desde PHP a Grafana Loki usando Monolog, sin necesidad de Promtail.

## Instalación

```bash
composer require felipeteko/monolog-loki
```

## Uso

```php
use Monolog\Logger;
use TekoEstudio\Loki\LokiHandler;

$logger = new Logger('app');
$logger->pushHandler(new LokiHandler(
    'LOKI_URL',
    'LOKI_USERNAME',
    'LOKI_API_KEY',
    ['env' => 'prod']
));

$logger->info('Log enviado directamente a Grafana Loki');
```

## Tests

```bash
composer install
composer test
```

## Licencia

MIT
