<?php
//load config
require_once 'config/config.php';
//load helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

//autoload core libralies
spl_autoload_register(function($className){
    require_once 'libralies/' . $className . '.php';
});