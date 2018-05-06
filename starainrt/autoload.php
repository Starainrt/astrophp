<?php
spl_autoload_register(function ($class) {
    $dir=dirname(__FILE__);
    if ($class) {
        $file =dirname($dir).'/'.strtolower($class).'.php';
        $file = str_replace('\\', DIRECTORY_SEPARATOR , $file);
	$file = str_replace('/', DIRECTORY_SEPARATOR , $file);
        if (file_exists($file)) {
            require_once $file;
        }
    }
});