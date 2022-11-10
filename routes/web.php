<?php

$routes = [
    "/formuser" => "UserController::form::GET",
    "/createuser" => "UserController::createUser::POST",
    "/home" => "HomeController::index::GET",
    "/user" => "HomeController::user::GET",
    "/users/{id}" => "HomeController::users::GET",
    "/users/login" => "HomeController::login::GET"
];
