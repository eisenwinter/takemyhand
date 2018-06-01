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
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * Enumeration of Verbs / HTTP Methods
 *
 */

namespace TakeMyHand\Http;

abstract class HttpVerb
{
    public const GET = 'GET';
    public const POST ='POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const PATCH = 'PATCH';
    public const OPTIONS = 'OPTIONS';
    public const HEAD = 'HEAD';
    public const TRACE = 'TRACE';
}