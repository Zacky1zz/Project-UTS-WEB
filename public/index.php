<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');

require BASE_PATH . '/src/Autoloader.php';
App\Autoloader::register(BASE_PATH . '/src');
require BASE_PATH . '/src/helpers.php';

ini_set('display_errors', '1');   // ubah ke '0' saat produksi
error_reporting(E_ALL);

use App\Controllers\{AuthController, EbookController, LoanController};
use App\Core\{Router, Session};
use App\Models\{Admin, Member};
use App\Repositories\UserRepository;

Session::start();

// Seed akun demo saat pertama kali dijalankan
$users = new UserRepository();
if ($users->all() === []) {
    $users->save(new Admin(0, 'Administrator', 'admin@ebook.test', password_hash('admin123', PASSWORD_DEFAULT)));
    $users->save(new Member(0, 'Member Demo', 'member@ebook.test', password_hash('member123', PASSWORD_DEFAULT)));
}

$router = new Router();
$router->add('GET', '/', [EbookController::class, 'index']);
$router->add('GET', '/ebooks', [EbookController::class, 'index']);
$router->add('GET', '/ebooks/create', [EbookController::class, 'createForm']);
$router->add('POST', '/ebooks/create', [EbookController::class, 'store']);
$router->add('GET', '/ebooks/{id}', [EbookController::class, 'show']);
$router->add('GET', '/ebooks/{id}/edit', [EbookController::class, 'editForm']);
$router->add('POST', '/ebooks/{id}/edit', [EbookController::class, 'update']);
$router->add('POST', '/ebooks/{id}/delete', [EbookController::class, 'destroy']);
$router->add('POST', '/ebooks/{id}/borrow', [LoanController::class, 'borrow']);

$router->add('GET', '/loans', [LoanController::class, 'index']);
$router->add('POST', '/loans/{id}/return', [LoanController::class, 'giveBack']);

$router->add('GET', '/login', [AuthController::class, 'loginForm']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/register', [AuthController::class, 'registerForm']);
$router->add('POST', '/register', [AuthController::class, 'register']);
$router->add('POST', '/logout', [AuthController::class, 'logout']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
