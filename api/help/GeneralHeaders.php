<?php

namespace Bookings;

use Slim\Middleware;


class GeneralHeaders extends Middleware
{
    public function call()
    {
        $app = $this->app;
        $response = $app->response;
        $response->setStatus(200);
        $response->headers->set('Content-Type', 'application/json');
        $this->next->call();
    }
}