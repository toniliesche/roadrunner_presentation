<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Enums;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\UnexpectedValueException;

enum HttpPhrase: string
{
    case ACCEPTED = 'Accepted';
    case ALREADY_REPORTED = 'Already Reported';
    case BAD_GATEWAY = 'Bad Gateway';
    case BAD_REQUEST = 'Bad Request';
    case CONFLICT = 'Conflict';
    case CREATED = 'Created';
    case EXPECTATION_FAILED = 'Expectation Failed';
    case FAILED_DEPENDENCY = 'Failed Dependency';
    case FORBIDDEN = 'Forbidden';
    case FOUND = 'Found';
    case GATEWAY_TIMEOUT = 'Gateway Time-out';
    case GONE = 'Gone';
    case HTTP_VERSION_NOT_SUPPORTED = 'HTTP Version not supported';
    case IM_A_TEAPOT = 'I\'m a teapot';
    case INSUFFICIENT_STORAGE = 'Insufficient Storage';
    case INTERNAL_SERVER_ERROR = 'Internal Server Error';
    case LENGTH_REQUIRED = 'Length Required';
    case LOCKED = 'Locked';
    case LOOP_DETECTED = 'Loop Detected';
    case METHOD_NOT_ALLOWED = 'Method Not Allowed';
    case MOVED_PERMANENTLY = 'Moved Permanently';
    case MULTIPLE_CHOICES = 'Multiple Choices';
    case MULTI_STATUS = 'Multi-status';
    case NETWORK_AUTHENTICATION_REQUIRED = 'Network Authentication Required';
    case NON_AUTHORITATIVE_INFORMATION = 'Non-Authoritative Information';
    case NOT_ACCEPTABLE = 'Not Acceptable';
    case NOT_EXTENDED = 'Not Extended';
    case NOT_FOUND = 'Not Found';
    case NOT_IMPLEMENTED = 'Not Implemented';
    case NOT_MODIFIED = 'Not Modified';
    case NO_CONTENT = 'No Content';
    case OK = 'OK';
    case PARTIAL_CONTENT = 'Partial Content';
    case PAYMENT_REQUIRED = 'Payment Required';
    case PERMAMENT_REDIRECT = 'Permanent Redirect';
    case PRECONDITION_FAILED = 'Precondition Failed';
    case PRECONDITION_REQUIRED = 'Precondition Required';
    case PROXY_AUTHENTICATION_REQUIRED = 'Proxy Authentication Required';
    case REQUESTED_RANGE_NOT_SATISFIABLE = 'Requested range not satisfiable';
    case REQUEST_ENTITY_TOO_LARGE = 'Request Entity Too Large';
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 'Request Header Fields Too Large';
    case REQUEST_TIMEOUT = 'Request Time-out';
    case REQUEST_URI_TOO_LARGE = 'Request-URI Too Large';
    case RESET_CONTENT = 'Reset Content';
    case SEE_OTHER = 'See Other';
    case SERVICE_UNAVAILABLE = 'Service Unavailable';
    case SWITCH_PROXY = 'Switch Proxy';
    case TEMPORARY_REDIRECT = 'Temporary Redirect';
    case TOO_MANY_REQUESTS = 'Too Many Requests';
    case UNAUTHORIZED = 'Unauthorized';
    case UNAVAILABLE_FOR_LEGAL_REASONS = 'Unavailable For Legal Reasons';
    case UNORDERED_COLLECTION = 'Unordered Collection';
    case UNPROCESSABLE_ENTITY = 'Unprocessable Entity';
    case UNSUPPORTED_MEDIA_TYPE = 'Unsupported Media Type';
    case UPDATE_REQUIRED = 'Upgrade Required';
    case USE_PROXY = 'Use Proxy';
    case VARIANT_ALSO_NEGOTIATES = 'Variant Also Negotiates';

    /**
     * @throws UnexpectedValueException
     */
    public static function fromCode(int $code): HttpPhrase {
        return match($code) {
            200 => self::OK,
            201 => self::CREATED,
            202 => self::ACCEPTED,
            203 => self::NON_AUTHORITATIVE_INFORMATION,
            204 => self::NO_CONTENT,
            205 => self::RESET_CONTENT,
            206 => self::PARTIAL_CONTENT,
            207 => self::MULTI_STATUS,
            208 => self::ALREADY_REPORTED,
            300 => self::MULTIPLE_CHOICES,
            301 => self::MOVED_PERMANENTLY,
            302 => self::FOUND,
            303 => self::SEE_OTHER,
            304 => self::NOT_MODIFIED,
            305 => self::USE_PROXY,
            306 => self::SWITCH_PROXY,
            307 => self::TEMPORARY_REDIRECT,
            308 => self::PERMAMENT_REDIRECT,
            400 => self::BAD_REQUEST,
            401 => self::UNAUTHORIZED,
            402 => self::PAYMENT_REQUIRED,
            403 => self::FORBIDDEN,
            404 => self::NOT_FOUND,
            405 => self::METHOD_NOT_ALLOWED,
            406 => self::NOT_ACCEPTABLE,
            407 => self::PROXY_AUTHENTICATION_REQUIRED,
            408 => self::REQUEST_TIMEOUT,
            409 => self::CONFLICT,
            410 => self::GONE,
            411 => self::LENGTH_REQUIRED,
            412 => self::PRECONDITION_FAILED,
            413 => self::REQUEST_ENTITY_TOO_LARGE,
            414 => self::REQUEST_URI_TOO_LARGE,
            415 => self::UNSUPPORTED_MEDIA_TYPE,
            416 => self::REQUESTED_RANGE_NOT_SATISFIABLE,
            417 => self::EXPECTATION_FAILED,
            418 => self::IM_A_TEAPOT,
            422 => self::UNPROCESSABLE_ENTITY,
            423 => self::LOCKED,
            424 => self::FAILED_DEPENDENCY,
            425 => self::UNORDERED_COLLECTION,
            426 => self::UPDATE_REQUIRED,
            428 => self::PRECONDITION_REQUIRED,
            429 => self::TOO_MANY_REQUESTS,
            431 => self::REQUEST_HEADER_FIELDS_TOO_LARGE,
            451 => self::UNAVAILABLE_FOR_LEGAL_REASONS,
            500 => self::INTERNAL_SERVER_ERROR,
            501 => self::NOT_IMPLEMENTED,
            502 => self::BAD_GATEWAY,
            503 => self::SERVICE_UNAVAILABLE,
            504 => self::GATEWAY_TIMEOUT,
            505 => self::HTTP_VERSION_NOT_SUPPORTED,
            506 => self::VARIANT_ALSO_NEGOTIATES,
            507 => self::INSUFFICIENT_STORAGE,
            508 => self::LOOP_DETECTED,
            510 => self::NOT_EXTENDED,
            511 => self::NETWORK_AUTHENTICATION_REQUIRED,
            default => throw new UnexpectedValueException(\sprintf('Could not map http code "%d" to phrase', $code)),
        };
    }
    
    public function toCode(): int {
        return match($this) {
            self::OK => 200,
            self::CREATED => 201,
            self::ACCEPTED => 202,
            self::NON_AUTHORITATIVE_INFORMATION => 203,
            self::NO_CONTENT => 204,
            self::RESET_CONTENT => 205,
            self::PARTIAL_CONTENT => 206,
            self::MULTI_STATUS => 207,
            self::ALREADY_REPORTED => 208,
            self::MULTIPLE_CHOICES => 300,
            self::MOVED_PERMANENTLY => 301,
            self::FOUND => 302,
            self::SEE_OTHER => 303,
            self::NOT_MODIFIED => 304,
            self::USE_PROXY => 305,
            self::SWITCH_PROXY => 306,
            self::TEMPORARY_REDIRECT => 307,
            self::PERMAMENT_REDIRECT => 308,
            self::BAD_REQUEST => 400,
            self::UNAUTHORIZED => 401,
            self::PAYMENT_REQUIRED => 402,
            self::FORBIDDEN => 403,
            self::NOT_FOUND => 404,
            self::METHOD_NOT_ALLOWED => 405,
            self::NOT_ACCEPTABLE => 406,
            self::PROXY_AUTHENTICATION_REQUIRED => 407,
            self::REQUEST_TIMEOUT => 408,
            self::CONFLICT => 409,
            self::GONE => 410,
            self::LENGTH_REQUIRED => 411,
            self::PRECONDITION_FAILED => 412,
            self::REQUEST_ENTITY_TOO_LARGE => 413,
            self::REQUEST_URI_TOO_LARGE => 414,
            self::UNSUPPORTED_MEDIA_TYPE => 415,
            self::REQUESTED_RANGE_NOT_SATISFIABLE => 416,
            self::EXPECTATION_FAILED => 417,
            self::IM_A_TEAPOT => 418,
            self::UNPROCESSABLE_ENTITY => 422,
            self::LOCKED => 423,
            self::FAILED_DEPENDENCY => 424,
            self::UNORDERED_COLLECTION => 425,
            self::UPDATE_REQUIRED => 426,
            self::PRECONDITION_REQUIRED => 428,
            self::TOO_MANY_REQUESTS => 429,
            self::REQUEST_HEADER_FIELDS_TOO_LARGE => 431,
            self::UNAVAILABLE_FOR_LEGAL_REASONS => 451,
            self::INTERNAL_SERVER_ERROR => 500,
            self::NOT_IMPLEMENTED => 501,
            self::BAD_GATEWAY => 502,
            self::SERVICE_UNAVAILABLE => 503,
            self::GATEWAY_TIMEOUT => 504,
            self::HTTP_VERSION_NOT_SUPPORTED => 505,
            self::VARIANT_ALSO_NEGOTIATES => 506,
            self::INSUFFICIENT_STORAGE => 507,
            self::LOOP_DETECTED => 508,
            self::NOT_EXTENDED => 510,
            self::NETWORK_AUTHENTICATION_REQUIRED => 511,
        };
    }
}
