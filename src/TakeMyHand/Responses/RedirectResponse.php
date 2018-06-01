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
 * redirects
 *
 */


namespace TakeMyHand\Responses;


use TakeMyHand\Http\HttpStatusCode;
use TakeMyHand\Response;

class RedirectResponse extends Response
{
    private $redirect_to_uri;
    public function __construct($uri, bool $soft = false){
        $this->redirect_to_uri = $uri;
        if($soft){
            $this->set_status_code(HttpStatusCode::Found);
        }else{
            $this->set_status_code(HttpStatusCode::MovedPermanently);
        }

        $this->set_header('Location',$uri);
    }
}