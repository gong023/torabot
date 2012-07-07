<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/../app/Torabot_Controller.php';

Torabot_Controller::main('Torabot_Controller', array(
    '__ethna_unittest__',
    )
);
?>
