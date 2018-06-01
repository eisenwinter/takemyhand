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

namespace App;

class CompositionModule implements \TakeMyHand\Container\IContainerBuilderModule
{

    //this is were we hook up the stuff that belongs to the actual site
    //it will always be called after the framework itself is done building up
    function build(\TakeMyHand\Container\IContainerBuilder $builder)
    {
       //TODO Register Components
    }
}