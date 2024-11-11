<?php

use Src\Controller\LoginController;
use Src\Controller\RegisterController;
use Src\Controller\CreateController;
use Src\Controller\OverviewController;
use Src\Controller\DeleteController;
use Src\Controller\EditController;
use Src\Model\Teacher;
use Src\Model\Student;
use Src\Model\User;

require_once '../vendor/autoload.php';

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=student_management', 'root', '');

// Twig setup
$loader = new \Twig\Loader\FilesystemLoader('../resources'); // Correct the path to point directly to 'resources' folder
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Set to true for production to enable caching
]);

// Get the current page parameter
$page = $_GET['page'] ?? 'login'; // Default to login page

// Initialize the controller
switch ($page) {
    case 'login':
        $controller = new LoginController($twig, $pdo);
        break;
    case 'register':
        $controller = new RegisterController($twig, $pdo);
        break;
    case 'create':
        $controller = new CreateController($twig, $pdo);
        break;
    case 'overview':
        $controller = new OverviewController($twig, $pdo);
        break;
    case 'edit':
        $controller = new EditController($twig, $pdo);
        break;
        
    case 'delete':
        $controller = new DeleteController( $pdo);
        break;
    default:
        $controller = new LoginController($twig, $pdo);
}

// Call the render method of the selected controller
$controller->render();
