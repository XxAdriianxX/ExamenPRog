<?php

function autoloading($className) {
    $file = __DIR__ . "/clases/" . $className . ".php";
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("La clase $className no se encuentra.");
    }
}

spl_autoload_register("autoloading");
?>
