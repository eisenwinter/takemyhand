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
 * interface for the container builder
 *
 */


namespace TakeMyHand\Container;


use TakeMyHand\Persistence\IKeyValueStore;

interface IContainerBuilder
{
    //statics in an interface, thanks php
    static function restoreable(IKeyValueStore $key_value_store) : bool;

    static function restore(IKeyValueStore $key_value_store) : IContainer;

    function register($type, \Closure $factory);
    function register_singleton($type, \Closure $factory);
    function register_instance_as($type, $instance);
    function register_enumerable($type, $concrete_class);
    function scan_implements($from, string $postfix = '');
    function scan_extends($from, string $postfix = '');
    function inject_property($type,$property, \Closure $factory);
    function build() : IContainer;
    function execute_module_registration(IContainerBuilderModule $module);

}