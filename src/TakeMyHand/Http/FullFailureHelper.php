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
 * @category   Helpers
 * @package    TakeMyHand
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.4
 * @since      v0.0.3
 *
 * little helper for non-recoverable errors
 *
 */
namespace TakeMyHand\Http;


class FullFailureHelper
{
    //last resort
	private static $inlined_template = '<!doctype html><html lang="en"><head> <meta charset="utf-8"> <title>FAILURE</title> <style><!-- body{background: #FF1B1C; color: #FFFFFC; font-family: \'Open Sans\', Arial, Helvetica, sans-serif}.is-container{text-align: center; display: block; position: relative; width: 80%; margin: 100px auto}.is-hero-headline{font-size: 220px; position: relative; display: inline-block; z-index: 2; height: 250px; letter-spacing: 15px}.is-headline, .is-subheadline{display: block; position: relative; text-align: center}.is-headline{letter-spacing: 12px; font-size: 4em; line-height: 80%}.is-subheadline{font-size: 20px}hr{padding: 0; border: none; border-top: 5px solid #fff; color: #BEB7A4; text-align: center; margin: 0 auto; width: 420px; height: 10px; z-index: -10}.is-code{background-color: #FFFFFC; color: #000; border-radius: 5px; margin: 5px; padding: 15px; overflow-y: auto}--> </style></head><body> <div class="is-container"> <div class="is-hero-headline">@status</div><hr> <div class="is-headline">@reason</div><div class="is-subheadline">&laquo; @message &raquo;</div></div>@details</body></html>';

    public static function get_error_template(string $status,string $reason,string $message,?string $details) : string{
        $base_template = '';
        $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'TakeMyHand' . DIRECTORY_SEPARATOR . 'InternalViews'.DIRECTORY_SEPARATOR . 'error.html';
        if(is_readable($path)){
            $base_template  = file_get_contents($path);
        }else{
            $base_template = self::$inlined_template;
        }
        $base_template = preg_replace("/@message/",$message,$base_template);
        $base_template = preg_replace("/@reason/",$reason,$base_template);
        $base_template = preg_replace("/@status/",$status,$base_template);
        if($details != null){
            $base_template = preg_replace("/@details/",$details,$base_template);
        }else{
            $base_template = preg_replace("/@details/",'',$base_template);
        }
        return $base_template;
    }

    public static function fail_message(string $message){
        self::fail($message, '');
    }

    public static function fail_exception(\Exception $ex){
        self::fail('Unhandled exception occurred', $ex->getMessage());
    }

    public static function fail(string $message, string $cause){
        $details = '';
        if(strtolower($_ENV['TMH_DEBUG_INFORMATION']) == 'true'){
            $exported = var_export($cause, true);
            $details =  '<pre class="is-code">'.$exported.'</pre>';
        }
        ob_clean();
        ob_start();
        echo self::get_error_template('500','Fatal Error',$message,$details);
        ob_end_flush();
    }

}