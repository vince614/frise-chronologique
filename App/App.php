<?php
namespace App;

use App_Core_Exception;
use Core\Configuration\Config;
use Core\Models\Database;
use Dotenv\Dotenv;

/**
 * Class App
 */
final class App
{

    /**
     * @var Database
     */
    static private $_dbinstance;

    /**
     * @var App
     */
    static private $_instance;

    /**
     * @var Config
     */
    static private $_config;

    /**
     * Registry collection
     *
     * @var array
     */
    static private $_registry = [];

    /**
     * Get App instance
     *
     * @return App
     */
    public static function init()
    {
        if (!self::$_instance) self::$_instance = new App();
        if (!self::$_config) self::$_config = new Config();
        return self::$_instance;
    }

    /**
     * Set all static data to defaults
     *
     */
    public static function reset()
    {
        self::$_config      = null;
        self::$_instance    = null;
        self::$_dbinstance  = null;
        self::$_registry    = [];
    }

    /**
     * Register a new variable
     *
     * @param string $key
     * @param mixed $value
     * @param bool $graceful
     * @throws App_Core_Exception
     */
    public static function register($key, $value, $graceful = false)
    {
        if (isset(self::$_registry[$key])) {
            if ($graceful) {
                return;
            }
            self::throwException('Registry key "'.$key.'" already exists');
        }
        self::$_registry[$key] = $value;
    }

    /**
     * Unregister a variable from register by key
     *
     * @param string $key
     */
    public static function unregister($key)
    {
        if (isset(self::$_registry[$key])) {
            if (is_object(self::$_registry[$key]) && (method_exists(self::$_registry[$key], '__destruct'))) {
                self::$_registry[$key]->__destruct();
            }
            unset(self::$_registry[$key]);
        }
    }

    /**
     * Retrieve a value from registry by a key
     *
     * @param string $key
     * @return mixed
     */
    public static function registry($key)
    {
        if (isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        }
        return null;
    }

    /**
     * Retrieve config value
     *
     * @param $code
     * @return mixed
     */
    public static function getConfig($code)
    {
        if (!self::$_config) {
            self::_initConfig();
        }
        return self::getConfigInstance()->getConfig($code);
    }

    /**
     * Retrieve model object
     *
     * @param string $modelClass
     * @return mixed
     */
    public static function getModel($modelClass)
    {
        $modelClass = ucfirst($modelClass) . "Model";
        $className = "App\\Models\\" . $modelClass;
        if (class_exists($className)) {
            return new $className;
        }
        return false;
    }

    /**
     * Throw Exception
     *
     * @param string $message
     * @throws App_Core_Exception
     */
    public static function throwException($message)
    {
        throw new App_Core_Exception($message);
    }

    /**
     * Retrieve a config instance
     *
     * @return Config
     */
    public static function getConfigInstance()
    {
        return self::$_config;
    }

    /**
     * Init config model
     */
    private function _initConfig()
    {
        self::$_config = new Config();
    }

    /**
     * Retrieve host url
     *
     * @return mixed
     */
    public static function getHost()
    {
        return App::getConfig(Config::LOCAL_MODE_CONFIG_CODE) == 1 ?
            App::getConfig(Config::UNSECURE_URL_CONFIG_CODE) :
            App::getConfig(Config::SECURE_URL_CONFIG_CODE);
    }

}