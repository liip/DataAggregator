<?php
$moduleDir =  __DIR__ . "/..";

if (!file_exists($moduleDir . "/vendor/autoload.php")) {
    die(
        "\n[ERROR] You need to run composer before running the test suite.\n".
            "To do so run the following commands:\n".
            "    curl -s http://getcomposer.org/installer | php\n".
            "    php composer.phar install --dev\n\n"
    );
}

$loader = require_once($moduleDir .'/vendor/autoload.php');

$loader->addClassMap(array("Liip\\DataAggregator\\Tests\\DataAggregatorTestCase" => __DIR__ . '/DataAggregatorTestCase.php' ));
