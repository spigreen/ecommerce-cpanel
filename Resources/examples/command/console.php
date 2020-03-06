<?php

use Symfony\Component\Console\Application;

$console = new Application('QualityPress Application', 'n/a');
$console->add(new \QualityPress\Component\CPanel\Command\CreateJobFromYamlCommand());
$console->add(new \QualityPress\Component\CPanel\Command\CreateJobCommand());

return $console;