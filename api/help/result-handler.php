<?php

namespace Bookings;


use Slim\Slim;

/**
 * General errors
 * @package bookings
 */
class GlobalErrors
{
    public static $NOT_AUTHORIZED_NO_LOGIN;
    public static $NOT_AUTHORIZED_NO_PERMISSIONS;
    public static $RESOURCE_NOT_FOUND;
    public static $NOTHING_CHANGED;
    public static $INVALID_REQUEST;
    public static $INTERNAL_ERROR;

    public static function init()
    {
        GlobalErrors::$NOT_AUTHORIZED_NO_LOGIN = new ErrorResult(1, "not logged in", 403);
        GlobalErrors::$NOT_AUTHORIZED_NO_PERMISSIONS = new ErrorResult(2, "no permissions", 403);
        GlobalErrors::$RESOURCE_NOT_FOUND = new ErrorResult(3, "resource not found", 404);
        GlobalErrors::$NOTHING_CHANGED = new ErrorResult(4, "nothing changed", 204);
        GlobalErrors::$INVALID_REQUEST = new ErrorResult(5, "invalid request", 400);
        GlobalErrors::$INTERNAL_ERROR = new ErrorResult(6, "internal error", 500);
    }
}

GlobalErrors::init();

function returnResult($data)
{
    echo(json_encode($data));
}

/**
 * @param ErrorResult $error Basic error template
 * @param null $msg errorMessage (overrides the $error`s msg)
 *
 * @param null $httpStatus httpStatusCode (overrides the $error´s httpStatus)
 *
 * @return bool
 */
function returnSlimError(ErrorResult $error, $msg = null, $httpStatus = null)
{
    if ($httpStatus !== null || $msg !== null) {
        // any overrides
        $httpStatus = $httpStatus ?: $error->getHttpCode();
        $httpStatus = $httpStatus ?: 500;
        $msg = $msg ?: $error->msg;
        $error = new ErrorResult($error->error, $msg, $httpStatus);
    }
    $body = json_encode($error);
    $app = Slim::getInstance();
    $app->halt($httpStatus, $body);

    return false;
}

class ErrorResult
{
    public $error;
    public $msg;

    private $httpCode;

    public function __construct($error, $msg, $httpCode = null)
    {
        $this->error = $error;
        $this->msg = $msg;
        $this->httpCode = $httpCode;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}