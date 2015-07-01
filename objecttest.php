<?php

use Shopware\Kernel;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/autoload.php';

$environment = getenv('SHOPWARE_ENV') ?: getenv('REDIRECT_SHOPWARE_ENV') ?: 'production';

$kernel = new Kernel($environment, $environment !== 'production');

$request = Request::createFromGlobals();

$kernel->boot();
$container = $kernel->getContainer();

$em = $container->get('models');
$em->getConnection()
    ->getConfiguration()
    ->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

$group = $em
    ->getRepository(Shopware\Models\Customer\Group::class)
    ->findOneBy(array('key' => 'EK'));

$dummyData = new \Shopware\Models\Customer\Customer();
$dummyData->setEmail('test@phpunit.org');

$dummyData->setGroup($group);

    $em->persist($dummyData);
    $em->flush();
