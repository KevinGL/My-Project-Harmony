<?php

$routes = [
    "/formuser" => "UserController::form::GET",
    "/formuser2" => "UserController::form2::GET",
    "/createuser" => "UserController::createUser::POST",
    "/update" => "UserController::updateUser::POST",
    "/delete" => "UserController::deleteUser::GET",
    "/showuser" => "UserController::getUser::GET",
    "/showallusers" => "UserController::getAllUsers::GET",

    "/articles" => "ArticleController::index::GET",
    "/deletearticle" => "ArticleController::delete::GET",

    "/home" => "HomeController::index::GET",
    "/user" => "HomeController::user::GET",
    "/users/{id}" => "HomeController::users::GET",
    "/users/login" => "HomeController::login::GET"
];
