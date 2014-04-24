<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar;

define('OCA\Calendar\JSON_API_VERSION', '1.0');
define('OCA\Calendar\PHP_API_VERSION', '1.0');

$app = new App();
$app->registerNavigation();

$app->registerCron();
$app->registerHooks();
$app->registerProviders();