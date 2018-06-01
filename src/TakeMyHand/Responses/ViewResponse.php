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
 * @category   FrameworkBasics
 * @package    TakeMyHand
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * the view response returns a rendered viewengine result
 *
 */


namespace TakeMyHand\Responses;


use TakeMyHand\Response;
use TakeMyHand\ViewEngine\IViewEngine;

class ViewResponse extends Response
{
    private $data;
    private $view;
    private $cwd;

    public function __construct(string $view, $data = null){
        $this->view = $view;
        $this->data = $data;
        $this->needs_view_engine = true;
        $this->cwd = __DIR__;
    }

    public function render(IViewEngine $engine){
        $this->body = $engine->render($this->view, $this->data);
    }
}