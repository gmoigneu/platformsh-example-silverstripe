<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	'type' => 'MySQLPDODatabase',
	'server' => 'mysql',
	'username' => 'silverstripe',
	'password' => 'silverstripe',
	'database' => 'silverstripe',
	'path' => ''
);

require_once('_platformsh.php');

// Set the site locale
i18n::set_locale('en_US');
