<?php

if(file_exists(__DIR__.'/env.php')){
    require_once __DIR__.'/env.php';
}

use TekoEstudio\Loki\LokiHandler;
use Monolog\Logger;

it('can be instantiated', function () {
    $handler = new LokiHandler(
        $_ENV['LOKI_SERVER'],
        $_ENV['LOKI_USERNAME'],
        $_ENV['LOKI_PASSWORD'],
        ['env' => $_ENV['LOKI_ENV']]
    );

    expect($handler)->toBeInstanceOf(LokiHandler::class);
});

it('accepts and merges labels', function () {
     $handler = new LokiHandler(
        $_ENV['LOKI_SERVER'],
        $_ENV['LOKI_USERNAME'],
        $_ENV['LOKI_PASSWORD'],
        ['env' => $_ENV['LOKI_ENV'], 'service' => 'auth']
    );

    $reflection = new ReflectionClass($handler);
    $property = $reflection->getProperty('labels');
    $property->setAccessible(true);

    $actualLabels = $property->getValue($handler);

    expect($actualLabels)->toMatchArray([
        'job' => 'php',
        'env' => $_ENV['LOKI_ENV'],
        'service' => 'auth',
    ]);
});

test('sends log to Loki', function () {
    $handler = new LokiHandler(
        $_ENV['LOKI_SERVER'],
        $_ENV['LOKI_USERNAME'],
        $_ENV['LOKI_PASSWORD'],
        ['env' => $_ENV['LOKI_ENV']]
    );

    $logger = new Logger('integration-test');
    $logger->pushHandler($handler);

    $logger->info('Mensaje de prueba desde LokiHandlerTest');

    expect(true)->toBeTrue(); // Simboliza que la llamada no lanzó errores
});
