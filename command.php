<?php

include('./vendor/autoload.php');

use Symfony\Component\Console\Application;
use Inwebo\Favicon\Command\GetterCommand;
$application = new Application();

$application->add(new GetterCommand());

$application->run();
