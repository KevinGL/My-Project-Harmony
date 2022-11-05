<?php

$routes = [
    "/home" => "HomeController::index::GET",
    "/user" => "HomeController::user::GET",
    "/users/{id}" => "HomeController::users::GET",
    "/users/login" => "HomeController::login::GET"
];
