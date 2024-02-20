<?php
/**
 * Created by PhpStorm.
 * User: josea
 * Date: 16/04/2022
 * Time: 1:46
 */
class Autoload
{
    private $filePath;

    function __construct()
    {

        $this->filePath = realpath(dirname(__FILE__));

        spl_autoload_register(array($this, "libs"));
        spl_autoload_register(array($this, "models"));
        spl_autoload_register(array($this, "api"));

    }

    public function libs($class)
    {

        $controllerDir = $this->filePath . "/libs/";

        if (file_exists($controllerDir . $class . ".php")) {

            require_once $controllerDir . $class . ".php";

        }

    }

    public function models($class)
    {

        $controllerDir = $this->filePath . "/models/";

        if (file_exists($controllerDir . $class . ".php")) {

            require_once $controllerDir . $class . ".php";

        }

    }
    public function api($class)
    {
        $controllerDir = $this->filePath . "/api/";
        if (file_exists($controllerDir . $class . ".php")) {

            require_once $controllerDir . $class . ".php";

        }

    }
}
new Autoload();