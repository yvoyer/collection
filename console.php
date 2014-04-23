<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace {
    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\Tools\Console\ConsoleRunner;
    use Doctrine\ORM\Tools\Setup;

    require_once 'vendor/autoload.php';

    $em = EntityManager::create(
        array(
            'driver' => 'pdo_sqlite',
            'in_memory' => true,
        ),
        Setup::createXMLMetadataConfiguration(array(__DIR__ . '/tests/Integration/config'))
    );
    ConsoleRunner::run(ConsoleRunner::createHelperSet($em));
}