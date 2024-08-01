<?php
if(!session_id()) session_start();

$module = $_GET['module'] ?? '';
if (!$module) $module = 'product';
$moduleName = ucfirst($module) .'Controller';
$path = "controllers/{$moduleName}.php";
if (!is_file( __DIR__ . '/' . $path)){
    http_response_code(404);
    include('views/404.php');
    exit();
}
require_once __DIR__ . '/config/database.php';
require_once $path;
$module = new $moduleName();
$module->handleRequest();

