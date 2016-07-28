<?php

/**
 * Override Database configuration for Platform.sh
 */

$relationships = getenv("PLATFORM_RELATIONSHIPS");
if (!$relationships) {
    return;
}
$relationships = json_decode(base64_decode($relationships), true);
foreach ($relationships['database'] as $endpoint) {
    if (empty($endpoint['query']['is_master'])) {
      continue;
    }

    $databaseConfig = array(
    	'type' => 'MySQLPDODatabase',
    	'server' => $endpoint['host'],
    	'username' => $endpoint['username'],
    	'password' => $endpoint['password'],
    	'database' => $endpoint['path'],
    	'path' => ''
    );
}

$variables = getenv("PLATFORM_VARIABLES");
if($variables) {
  $variables = json_decode(base64_decode($variables), true);

  define('SS_DEFAULT_ADMIN_USERNAME', ($variables['SS_DEFAULT_ADMIN_USERNAME']) ? $variables['SS_DEFAULT_ADMIN_USERNAME'] : 'username');
  define('SS_DEFAULT_ADMIN_PASSWORD', ($variables['SS_DEFAULT_ADMIN_PASSWORD']) ? $variables['SS_DEFAULT_ADMIN_PASSWORD'] : 'password');
}

?>
