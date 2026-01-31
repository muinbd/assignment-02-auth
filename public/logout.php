<?php
session_start();

require_once __DIR__ . '/../app/Core/Auth.php';

Auth::logout();

header('Location: login.php');
exit();
