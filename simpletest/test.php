#!/usr/bin/php
<?php
class SimpleTest {
  protected function assert($boolean) {
    if (! $boolean) $this->fail();
  }

  protected function fail($msg = '') {
    echo "FAILURE! $msg\n";
    debug_print_backtrace();
    die;
  }
  

}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

if (phpversion() >= '5.3') $is_php_53 = true;
else $is_php_53 = false;

error_reporting(E_ALL | E_STRICT);
require_once '../db.class.php';
DB::$user = 'meekrodb_test_us';

include 'test_setup.php'; //test config values go here
DB::$password = $set_password;
DB::$dbName = $set_db;
DB::$host = $set_host;

require_once 'BasicTest.php';
require_once 'ObjectTest.php';
require_once 'WhereClauseTest.php';
require_once 'ErrorTest.php';

$classes_to_test = array(
  'BasicTest',
  'WhereClauseTest',
  'ObjectTest',
  'ErrorTest',
);

if ($is_php_53) {
  require_once 'ErrorTest_53.php';
  $classes_to_test[] = 'ErrorTest_53';
}

$time_start = microtime_float();
foreach ($classes_to_test as $class) {
  $object = new $class();
  
  foreach (get_class_methods($object) as $method) {
    if (substr($method, 0, 4) != 'test') continue;
    echo "Running $class::$method..\n";
    $object->$method();
  }
}
$time_end = microtime_float();
$time = round($time_end - $time_start, 2);

echo "Completed in $time seconds\n";


?>
