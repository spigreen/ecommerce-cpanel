<?php

require_once __DIR__ . '/../../../autoloader.php';

use QualityPress\Component\CPanel\CPanel;
use QualityPress\Component\CPanel\Service\ServiceInterface;

$domain     = "";
$account    = "";
$password   = "";

### get cpanel api
$cpanel     = CPanel::getInstance($domain, $account, $password);

### CronTab
$cronTab    = $cpanel->getService(ServiceInterface::CRON_TAB);

### Delete Command (Using the line order of job)
$cronTab->delete(1);