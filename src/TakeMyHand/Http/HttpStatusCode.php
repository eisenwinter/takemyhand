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
 * HTTP Status Code 'Enumeration'
 *
 */
namespace TakeMyHand\Http;


abstract class HttpStatusCode
{
    //2xx
    public const Ok = 200;
    public const Created = 201;
    public const Accepted = 202;
    public const NAInformation = 203;
    public const NoContent = 204;
    public const ResetContent = 205;
    public const PartialContent = 206;

    //3xx
    public const MultipleChoices = 300;
    public const MovedPermanently = 301;
    public const Found = 302;
    public const SeeOther = 303;
    public const NotModified = 304;
    public const UseProxy = 305;
    public const TemporaryRedirect = 307;
    public const PermanentRedirect = 308;

    //4xx
    public const BadRequest = 400;
    public const Unauthorized = 401;
    public const PaymentRequired = 402;
    public const Forbidden = 403;
    public const NotFound = 404;
    public const MethodNotAllowed = 405;
    public const NotAcceptable = 406;
    public const ProxyAuthRequired = 407;
    public const RequestTimeout = 408;
    public const Conflict = 409;
    public const Gone = 410;
    public const LengthRequired = 411;
    public const PreconditionFailed = 412;
    public const PayloadTooLarge = 413;
    public const UriTooLong = 414;
    public const UnsupportedMediaType = 415;
    public const RangeNotSatisfiable = 416;
    public const ExpectationFailed = 417;
    public const Teapot = 418;
    public const MisdirectedRequest = 421;
    public const UpgradeRequired = 426;
    public const PreconditionRequired = 428;
    public const TooManyRequests = 429;
    public const RequestHeaderTooLarge = 431;
    public const UnavailableForLegalReasons = 451;

    //5xx
    public const InternalServerError = 500;
    public const NotImplemented = 501;
    public const BadGateway = 502;
    public const ServiceUnavailable = 503;
    public const GatewayTimeout = 504;
    public const HTTPVersionNotSupported = 505;
    public const VariantAlsoNegotiates = 506;
    public const NotExtended = 510;
    public const NetworkAuthenticationRequired = 511;
}