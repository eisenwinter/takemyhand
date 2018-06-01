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
 * @category   KeyValueStore
 * @package    TakeMyHand.KeyValueStore
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * stub implementation for a non-existent kvs
 *
 */

namespace TakeMyHand\Persistence;


use DateInterval;

class NoKeyValueStore implements  IKeyValueStore
{

    function persist(string $key, $obj, DateInterval $ttl = null)
    {
        //do nothing
    }

    function retrieve(string $key)
    {
        //nothing
        return null;
    }

    function is_set(string $key): bool
    {
        //never
        return false;
    }
}