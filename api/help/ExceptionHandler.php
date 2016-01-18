<?php

namespace Bookings;

use Slim\Middleware;

class ExceptionHandler extends Middleware
{
    public function call()
    {
        try {
            $this->next->call();
        } catch (\InvalidArgumentException $e) {
            $msg = "internal error: " . $e->getMessage();
            $error = new ErrorResult(GlobalErrors::$INTERNAL_ERROR->error, $msg, GlobalErrors::$INTERNAL_ERROR->getHttpCode());
            $this->app->response->setStatus($error->getHttpCode());
            $this->app->response->setBody(json_encode($error));
        }
    }
}
