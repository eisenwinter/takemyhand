<?php
/**
 *
 *  _____     _       _____     _____           _
 * |_   _|___| |_ ___|     |_ _|  |  |___ ___ _| |
 *  | | | .'| '_| -_| | | | | |     | .'|   | . |
 *  |_| |__,|_,_|___|_|_|_|_  |__|__|__,|_|_|___|
 *                        |___|
 *
 * TakeMyHand  Boilerplate-Nano-Framework v0.0.3
 * @category   Environment
 * @package    TakeMyHand.Env
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * Loads .env files, used to substitute not set environment variables
 *
 */

namespace TakeMyHand\Env;
class EnvLoader
{
    const PREFIX = 'TMH_';
    function __construct($basePath, $environment = '')
    {
        $filename = (strlen($environment) > 0 ) ? $environment . '.env' : '.env';
        if(is_readable ($basePath . DIRECTORY_SEPARATOR . $filename)){
            $this->fill_env_array($basePath . DIRECTORY_SEPARATOR . $filename);
        }
    }

    private function fill_env_array($path){
           $env_file = file_get_contents($path);
           $values = preg_grep("/^[a-zA-Z_-]*\=(.?)*$/",explode(PHP_EOL, $env_file));
           foreach($values as $val){
               $kvp = explode('=',$val,2);
               $_ENV[self::PREFIX.trim($kvp[0])] = trim($kvp[1]);
               putenv(self::PREFIX.trim($kvp[0]).'='.trim($kvp[1]));

           }
    }
}