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
 * @category   ViewEngine
 * @package    TakeMyHand.ViewEngine
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * This is the base for view engine implementations,
 * the render method takes the path and the model data
 * if any is passed, to register you own view engine
 * just register a class implementing the interface
 * in the container
 *
 */

namespace TakeMyHand\ViewEngine;


interface IViewEngine
{
    /**
     * @param $view string location of the view file
     * @param $data mixed the data supplied to the view
     * @return string|null the rendered content
     */
    function render(string $view, $data) : ?string;
}