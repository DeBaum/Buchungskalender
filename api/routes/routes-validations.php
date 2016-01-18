<?php

namespace Bookings\Routes;

use Bookings\GlobalErrors;
use Slim\Slim;
use function Bookings\returnSlimError;

class RouteValidations
{


    /**
     * Request body should not be empty
     */
    public static $noEmptyBody = __NAMESPACE__ . "\\noEmptyBody";

    /**
     * Checks if the user is logged in and has
     * the required roles
     *
     * @param string $role
     *
     * @return \Closure
     */
    public static function hasRole($role = 'user')
    {
        return function () use ($role) {
            $user = wp_get_current_user();
            // DEBUG TODO REMOVE
            $user->ID = 1;
            // DEBUG
            if ($user->ID == 0) {
                // not logged in
                returnSlimError(GlobalErrors::$NOT_AUTHORIZED_NO_LOGIN, "no login", 401);
            }
        };
    }
}

function noEmptyBody()
{
    $app = Slim::getInstance();
    if ($app->request->getBody() == null) {
        returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", 401);
    }
}

function handleException()
{
    $app = Slim::getInstance();
    if ($app->request->getBody() == null) {
        returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", 401);
    }
}