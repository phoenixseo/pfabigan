<?php

// @codingStandardsIgnoreFile

/**
 * Enable access to rebuild.php.
 *
 * This setting can be enabled to allow Drupal's php and database cached
 * storage to be cleared via the rebuild.php page. Access to this page can also
 * be gained by generating a query string from rebuild_token_calculator.sh and
 * using these parameters in a request to rebuild.php.
 */
#$settings['rebuild_access'] = TRUE;

/**
 * Skip file system permissions hardening.
 *
 * The system module will periodically check the permissions of your site's
 * site directory to ensure that it is not writable by the website user. For
 * sites that are managed with a version control system, this can cause problems
 * when files in that directory such as settings.php are updated, because the
 * user pulling in the changes won't have permissions to modify files in the
 * directory.
 */
#$settings['skip_permissions_hardening'] = TRUE;

# update free access.
#$settings['update_free_access'] = TRUE;

# get temporary file path from .env
#if (!isset($config['system.file']['path']['temporary'])) {
$config['system.file']['path']['temporary'] = getenv('prod_tmp_dir');
#}

# db settings.
$databases['default']['default'] = array (
  'database' => getenv('prod_DB_DATABASE'),
  'username' => getenv('prod_DB_USER'),
  'password' => getenv('prod_DB_PASS'),
  'prefix' => getenv('prod_DB_PREFIX'),
  'host' => getenv('prod_DB_HOST'),
  'port' => getenv('prod_DB_PORT'),
  'namespace' => getenv('prod_DB_NAMESPACE'),
  'driver' => getenv('prod_DB_DRIVER'),
);
