# Monolog Loki Handler

Este paquete te permite enviar logs directamente desde PHP a Grafana Loki usando Monolog, sin necesidad de Promtail.

## Instalación

```bash
composer require tekoestudio/monolog-loki
```

## Uso

```php
use Monolog\Logger;
use TekoEstudio\Loki\LokiHandler;

$logger = new Logger('app');
$logger->pushHandler(new LokiHandler(
    'https://logs-prod3.grafana.net',
    'TU_TENANT_ID',
    'TU_API_KEY',
    ['env' => 'prod']
));

$logger->info('Log enviado directamente a Grafana Loki');
```

## Configuración recomendada en Grafana

- Crea una API Key con permiso de escritura para **Loki**.
- Ve a **Explore** y usa la consulta `{job="php"}` para ver los logs.

## Licencia

MIT
