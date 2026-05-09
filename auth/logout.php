<?php
/**
 * WorkBazar — Logout
 */
require_once __DIR__ . '/../includes/app.php';
App::init();
Security::startSession();
Auth::logout();
header("Location: login.php");
exit;
