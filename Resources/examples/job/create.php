<?php

require_once __DIR__ . '/../../../autoloader.php';

use QualityPress\Component\CPanel\CPanel;
use QualityPress\Component\CPanel\Service\ServiceInterface;
use QualityPress\Component\CPanel\Model\CronJob;

$domain     = "";
$account    = "";
$password   = "";

### get cpanel api
$cpanel     = CPanel::getInstance($domain, $account, $password);

### CronTab
$cronTab    = $cpanel->getService(ServiceInterface::CRON_TAB);

### Create the job
$job = new CronJob(
    'wget -O - -q -t 1 http://{domain}/file >/dev/null',
    '*',
    '*',
    '*',
    '1',
    '0'
);

### Create command
$cronTab->create($item);