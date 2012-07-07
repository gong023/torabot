<?php
/**
 *  {$action_name}.php
 *
 *  @author     {$author}
 *  @package    Torabot
 *  @version    $Id$
 */
chdir(dirname(__FILE__));
require_once '{$dir_app}/Torabot_Controller.php';

ini_set('max_execution_time', 0);

Torabot_Controller::main_CLI('Torabot_Controller', '{$action_name}');
?>
