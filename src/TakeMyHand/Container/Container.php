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
 * IOC Container, concrete Implementation
 *
 */
namespace TakeMyHand\Container;


use ReflectionClass;

class Container implements IContainer
{
    private $registry;

    public function __construct($registry){
        $this->registry = $registry;
    }

    private function handle_property_injection($type, $constructed_obj){
        if(isset($this->registry[$type]['inject'])){
            foreach($this->registry[$type]['inject'] as $inject){
                $constructed_obj->{$inject['prop_name']} = $inject['factory']($this);
            }
        }
    }

    private function resolve_enumerable($type){
        $ret = array();
        foreach($this->registry[$type]['known_types'] as $t){
            $reflected = new ReflectionClass($t['qualified_name']);
            $ctor = $reflected->getConstructor();
            if($ctor != null){
               $p =  $ctor->getParameters();
               if(count($p) == 0 ){
                   $instance = new $t['qualified_name']();
                   $this->handle_property_injection($type,$instance);
                   array_push($ret, $instance);
               }else{
                   $inject = array();
                   foreach ($p as $r) {
                       $to_inject = $this->resolve($r->getClass()->name);
                       if($to_inject == null){
                           throw  new \Exception('Cannot satisified dependency for ' .$r->getClass()->name. ' in ' .$t['qualified_name']);
                       }
                       array_push($inject, $to_inject);
                   }
                   $instance = $reflected->newInstanceArgs($inject);
                   $this->handle_property_injection($type, $instance);
                   array_push($ret, $instance);
               }
            }else{
                $instance = new $t['qualified_name']();
                $this->handle_property_injection($type, $instance);
                array_push($ret, $instance);
            }
        }

        return $ret;
    }

    public function resolve($type){
        if(isset($this->registry[$type])){
            switch($this->registry[$type]['as']){
                case 'enumerable':
                    return $this->resolve_enumerable($type);

                case 'instance':
                    $instance = $this->registry[$type]['factory']($this);
                    $this->handle_property_injection($type, $instance);
                    return $instance;
                case 'singleton':
                    if(!isset($this->registry[$type]['instance']) || $this->registry[$type]['instance'] == null){
                        $this->registry[$type]['instance'] = $this->registry[$type]['factory']($this);
                    }
                    $instance = $this->registry[$type]['instance'];
                    $this->handle_property_injection($type, $instance);
                    return $instance;

                default:
                    return null;
            }
        }
        return null;
    }

    function is_registered($type)
    {
        return isset($this->registry[$type]);
    }
}