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
 * @category   Composition/IOC
 * @package    TakeMyHand.Container
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * ContainerBuilder concrete implementation
 *
 */
namespace TakeMyHand\Container;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RegexIterator;
use TakeMyHand\Persistence\IKeyValueStore;

class ContainerBuilder implements IContainerBuilder
{
    private $registry = array();
    /** @var IKeyValueStore $store*/
    private $store;

    public function __construct($key_value_store){
        $this->store = $key_value_store;
    }


    public function build(): IContainer
    {
        //$this->store->persist('container', serialize($this->registry));
        return new Container($this->registry);
    }

    public static function restoreable(IKeyValueStore $key_value_store): bool
    {
        return $key_value_store->is_set('container');
    }

    public static function restore(IKeyValueStore $key_value_store) : IContainer{
        $registry_data = $key_value_store->retrieve('container');
        return new Container(unserialize($registry_data));
    }

    public function register($type, \Closure $factory)
    {
        $this->registry[$type] = array(
            "type" => $type,
            "as" => "instance",
            "factory" => $factory
        );
    }

    public function register_singleton($type, \Closure $factory)
    {
        $this->registry[$type] = array(
            "type" => $type,
            "as" => "singleton",
            "factory" => $factory,
            "instance" => null
        );
    }

    function register_instance_as($type, $instance)
    {
        $this->registry[$type] = array(
            "type" => $type,
            "as" => "singleton",
            "instance" => $instance
        );
    }

    private function scan_dir(string $postfix = '')
    {
        //wew http://php.net/manual/de/class.recursivedirectoryiterator.php
        //php has come a long way lel
        $rdi = new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$_ENV['TMH_APP_DIR']);
        $i = new RecursiveIteratorIterator($rdi);
        return new RegexIterator($i, '/^.+\\'.$postfix.'.php$/i', RegexIterator::GET_MATCH);
    }

    private function scan($from, \Closure $check, $postfix){
        $found = $this->scan_dir($postfix);
        foreach($found as $key => $file){
            $class_name = basename($key,'.php');
            $base_length = strlen($_SERVER['DOCUMENT_ROOT']);
            $qualified_name = substr($key,$base_length + 1, strlen($key) - $base_length - 5);
            if(DIRECTORY_SEPARATOR == '/'){
                $qualified_name = str_replace('/','\\',$qualified_name);
            }
            if($check($qualified_name)){
                if(!isset($this->registry[$from])){
                    $this->registry[$from] = array(
                        "type" => $from,
                        "as" => "enumerable",
                        "known_types" => array()
                    );
                }
                array_push($this->registry[$from]["known_types"], array(
                                                                            "type" => $class_name,
                                                                            "qualified_name" => $qualified_name,
                                                                            "file" => $key));
            }
        }
    }

    public function scan_implements($from, string $postfix = '')
    {
        $this->scan($from, function($class_name) use ($from) {
            $reflected = new ReflectionClass($class_name);
            return $reflected->implementsInterface($from);
        }, $postfix);
    }

    public function scan_extends($from, string $postfix = '')
    {
        $this->scan($from, function($class_name) use ($from) {
            $reflected = new ReflectionClass($class_name);
            return $reflected->isSubclassOf($from);
        }, $postfix);

    }

    public function register_enumerable($type, $concrete_class)
    {
        if(!isset($this->registry[$type])){
            $this->registry[$type] = array(
                "type" => $type,
                "as" => "enumerable",
                "known_types" => array()
            );
        }
        array_push($this->registry[$type]["known_types"], array(
            "type" => $concrete_class,
            "qualified_name" => $concrete_class,
            "file" => $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$concrete_class.'.php'));

    }

    public function inject_property($type, $property, \Closure $factory)
    {
        if(!isset($this->registry[$type])) {
            throw new \Exception("Cannot perform property injection on unregistred type!");
        }
        if(!isset($this->registry[$type]['inject'])){
            $this->registry[$type]['inject'] = array();
        }
        array_push($this->registry[$type]['inject'], array('prop_name' => $property, 'factory' => $factory));
    }

    public function execute_module_registration(IContainerBuilderModule $module){
        $module->build($this);
    }
}