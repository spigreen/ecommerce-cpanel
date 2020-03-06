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

### Edit
$cronTab->edit(6, new \QualityPress\Component\CPanel\Model\CronJob(
    'wget -O - -q -t 1 http://{domain}/file >/dev/null',
    '*',
    '*',
    '*',
    '1',
    '0'
));