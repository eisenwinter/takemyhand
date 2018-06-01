<?php
/**
 *
 *  _____     _       _____     _____           _
 * |_   _|___| |_ ___|     |_ _|  |  |___ ___ _| |
 *   | | | .'| '_| -_| | | | | |     | .'|   | . |
 *   |_| |__,|_,_|___|_|_|_|_  |__|__|__,|_|_|___|
 *                         |___|
 *
 * */

spl_autoload_extensions(".php");
spl_autoload_register();

try{
    $bootstrapper = new \TakeMyHand\Bootstrap\Bootstrapper();
    $bootstrapper->setup();


}catch (Exception $e){
    TakeMyHand\Http\FullFailureHelper::fail("Uncaught Exception",$e);
}
