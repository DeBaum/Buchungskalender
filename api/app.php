<?php

namespace Bookings;

use Slim\Middleware;
use Slim\Slim;
use function Bookings\Routes\registerRoutes;

define('BOOKINGS_APP_ROOT', __DIR__);
define('BOOKINGS_DEBUG', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/help/result-handler.php';
require_once __DIR__ . '/help/GeneralHeaders.php';
require_once __DIR__ . '/help/ExceptionHandler.php';
require_once __DIR__ . '/routes/routes.php';
require_once __DIR__ . '/routes/routes-validations.php';

// Controllers
require_once __DIR__ . './controllers/BaseController.php';
require_once __DIR__ . './controllers/CategoryController.php';
require_once __DIR__ . './controllers/ResourceController.php';
require_once __DIR__ . './controllers/ReservationController.php';

// Models
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Resource.php';
require_once __DIR__ . '/models/Reservation.php';


$app = new Slim();

class UserRole
{
    const NONE = 0;
    const USER = 1;
    const ADMIN = 2;
}

$app->add(new GeneralHeaders());
$app->add(new ExceptionHandler());
registerRoutes($app);


$app->run();
