<?php
/**
 *
 *  _____     _       _____     _____           _
 * |_   _|___| |_ ___|     |_ _|  |  |___ ___ _| |
 *  | | | .'| '_| -_| | | | | |     | .'|   | . |
 *  |_| |__,|_,_|___|_|_|_|_  |__|__|__,|_|_|___|
 *                        |___|
 *
 *  Starter Template
 *
 */

namespace App\Starter;
namespace App\Starter;

use TakeMyHand\Behaviour\Behaviour;
use TakeMyHand\Behaviour\IAction;
use TakeMyHand\Responses\RedirectResponse;
use TakeMyHand\Responses\ViewResponse;


class DefaultBehaviour extends Behaviour
{
    public function __construct()
    {

    }

    public function on(IAction $action)
    {
        $action->get('/', function($req, $ctx){
            $assoc_data = array();
            $assoc_data['User'] = 'User';
            $assoc_data['Date'] = date_format(new \DateTime('now'), 'g:ia \o\n l jS F Y');
            return new ViewResponse("starter/home",$assoc_data);
        });
    }
}