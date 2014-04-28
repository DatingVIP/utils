<?php
/**
 * Environment utility class
 *
 * @package DatingVIP
 * @subpackage utils
 * @author Boris Momcilovic <boris@firstbeatmedia.com>
 * @copyright &copy; 2014 firstbeatmedia.com
 * @version 1.0
 */

namespace DatingVIP\utils;

class Env
{
/**
 * Mapping of environment names to arrays of regex patterns
 *
 * @var array
 * @access protected
 * @static
 */
    protected static $map = [];

/**
 * Default environment name
 *
 * @var string
 * @access protected
 * @static
 */
    protected static $default = 'production';

/**
 * List of environment names where we consider debug mode on
 *
 * @var array $debug
 * @access protected
 * @static
 */
    protected static $debug = ['development'];

/**
 * Creating instances is disabled
 *
 * @param void
 * @access private
 * @return void
 * @final
 */
    final private function __construct() {}

/**
 * Cloning is disabled
 *
 * @param void
 * @access private
 * @return void
 * @final
 */
    final private function __clone() {}

/**
 * Setup a map of environments to their patterns and optionally debug environments
 *
 * @param array $map
 * @param array $debug	[= []]
 * @access public
 * @return void
 * @static
 */
    public static function setup(Array $map, Array $debug = [])
    {
        array_change_key_case ($map, CASE_LOWER);
        static::$map = $map;

        if (!empty ($debug))	{ static::$debug = $debug; }
    }

/**
 * Get environment name for given host
 * - if no host given will try for current servers HTTP_HOST
 *
 * @param string $host	[= '']
 * @access public
 * @return string
 * @static
 */
    public static function get($host = '')
    {
        if (!is_scalar ($host) || empty ($host)) {
            $host = isset ($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        }

        foreach (static::$map as $env => $patterns) {
            foreach ((array) $patterns as $pattern) {
                if (preg_match ($pattern, $host))		{ return $env; }
            }
        }

        return static::$default;
    }

/**
 * Check if current environment allows debug
 *
 * @param void
 * @access public
 * @return bool
 * @static
 */
    public static function debug()
    {
        return in_array (static::get (), static::$debug);
    }

/**
 * Check if given environment is the same as for given host
 *
 * @param string $env
 * @param string $host	[= '']
 * @access public
 * @return bool
 * @static
 */
    public static function is($env, $host = '')
    {
        return static::get ($host) === strtolower ($env);
    }

/**
 * Check if we're running via CLI
 *
 * @param void
 * @access public
 * @return bool
 * @static
 */
    public static function isCLI()
    {
        return substr (PHP_SAPI, 0, 3) == 'cli' || (isset ($_SERVER['argc']) && isset ($_SERVER['argv']));
    }

/**
 * Check if we're running via web
 *
 * @param void
 * @access public
 * @return bool
 * @static
 */
    public static function isWeb()
    {
        return !static::isCli ();
    }

/**
 * Check if we're running over HTTPS
 *
 * @param void
 * @access public
 * @return bool
 * @static
 */
    public static function isHTTPS()
    {
        return !empty ($_SERVER['HTTPS']) || (isset ($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    }

/**
 * Magic static wrapper for "isEnvironmentName" checker methods
 *
 * @param string $name
 * @param array $args
 * @access public
 * @return bool
 * @static
 * @magic
 */
    public static function __callStatic($name, $args)
    {
        if (substr ($name, 0, 2) == 'is') {
            return static::is (strtolower (substr ($name, 2)), isset ($args[0]) ? $args[0] : '');
        }
    }

}
