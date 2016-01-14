<?php

namespace Bookings\Routes;

use Bookings;
use Bookings\Controller\CategoryController;
use Bookings\Controller\ReservationController;
use Bookings\Controller\ResourceController;
use Bookings\GlobalErrors;
use Bookings\UserRole;
use Slim\Middleware;
use Slim\Slim;
use function Bookings\returnResult;
use function Bookings\returnSlimError;

/**
 * The routes are responsible for route configuration and request
 * validation like general authentication and validation
 */

/**
 * Registers all required routes
 *
 * @param Slim $app
 */
function registerRoutes(Slim $app)
{

    // Categories
    $app->group('/categories', function () use ($app) {
        $categoryCtrl = new CategoryController();

        $app->post('/', RouteValidations::$noEmptyBody, RouteValidations::hasRole(UserRole::ADMIN), function () use ($app, $categoryCtrl) {
            $categoryCtrl->create(json_decode($app->request->getBody()));
        });

        $app->get('/:id', function ($id) use ($app, $categoryCtrl) {
            returnResultWithNotFound($categoryCtrl->get($id));
        });

        $app->get('/', function () use ($app, $categoryCtrl) {
            returnResult($categoryCtrl->getAll());
        });

        $app->put('/:id', RouteValidations::$noEmptyBody, RouteValidations::hasRole(UserRole::ADMIN), function ($id) use ($app, $categoryCtrl) {
            $categoryCtrl->update($id, json_decode($app->request->getBody()));
        });
    });

    // Resources
    $app->group('/resources', function () use ($app) {
        $resourceCtrl = new ResourceController();

        $app->post('/', RouteValidations::$noEmptyBody, RouteValidations::hasRole(UserRole::ADMIN), function () use ($app, $resourceCtrl) {
            $resourceCtrl->create(json_decode($app->request->getBody()));
        });

        $app->get('/:id', function ($id) use ($app, $resourceCtrl) {
            returnResultWithNotFound($resourceCtrl->get($id));
        });

        $app->get('/', function () use ($app, $resourceCtrl) {
            returnResult($resourceCtrl->getAll());
        });

        $app->put('/:id', RouteValidations::$noEmptyBody, RouteValidations::hasRole(UserRole::ADMIN), function ($id) use ($app, $resourceCtrl) {
            $resourceCtrl->update($id, json_decode($app->request->getBody()));
        });
    });

    // Reservation
    $app->group('/reservations', function () use ($app) {
        $reservationCtrl = new ReservationController();

        $app->post('/', RouteValidations::$noEmptyBody, RouteValidations::hasRole(UserRole::ADMIN), function () use ($app, $reservationCtrl) {
            $reservationCtrl->create(json_decode($app->request->getBody()));
        });

        $app->get('/:id', function ($id) use ($app, $reservationCtrl) {
            returnResultWithNotFound($reservationCtrl->get($id));
        });

        $app->get('/', function () use ($app, $reservationCtrl) {
            returnResult($reservationCtrl->getAll());
        });

        $app->put('/:id', RouteValidations::$noEmptyBody, RouteValidations::hasRole(UserRole::ADMIN), function ($id) use ($app, $reservationCtrl) {
            $reservationCtrl->update($id, json_decode($app->request->getBody()));
        });
    });

    /**
     * Returns an 404 error if the result is null
     *
     * @param $result
     */
    function returnResultWithNotFound($result)
    {
        if ($result != null) {
            returnResult($result);
        } else {
            returnSlimError(GlobalErrors::$RESOURCE_NOT_FOUND);
        }
    }

}