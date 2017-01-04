<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CustomAutoloader{

    public function __construct(){
        spl_autoload_register(array($this, 'loader'));
    }

    public function loader($className){
        if (substr($className, 0, 6) == 'models')
            require  APPPATH .  str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    }

}
?>
