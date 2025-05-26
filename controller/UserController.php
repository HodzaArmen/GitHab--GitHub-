<?php
require_once("model/UserDB.php");
require_once("model/RepoDB.php");
require_once("model/StarDB.php");
require_once("ViewHelper.php");

class UserController {
    public static function profile() {
        if (!isset($_GET["id"])) {
            if (isset($_SESSION["user_id"])) {
                ViewHelper::redirect(BASE_URL . "user/profile?id=" . $_SESSION["user_id"]);
            } else {
                ViewHelper::redirect(BASE_URL . "login");
            }
            return;
        }
        
        $userId = $_GET["id"];
        $user = UserDB::get($userId);
        
        if (!$user) {
            ViewHelper::error404();
            return;
        }
        
        $repos = RepoDB::getByUser($userId);
        $starredRepos = StarDB::getByUser($userId);
        
        ViewHelper::render("view/user/profile.php", [
            "user" => $user,
            "repos" => $repos,
            "starredRepos" => $starredRepos,
            "isCurrentUser" => isset($_SESSION["user_id"]) && ($userId == $_SESSION["user_id"])
        ]);
    }

    public static function list() {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }
        
        $users = UserDB::getAll();
        ViewHelper::render("view/user/list.php", ["users" => $users]);
    }

    public static function edit() {
        // Check if the user is logged in
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        // Get the user's information from the database
        $user = UserDB::get($_SESSION["user_id"]);

        // Render the edit profile view
        ViewHelper::render("view/user/edit.php", ["user" => $user]);
    }

    public static function stars() {
        // Get the user ID from the query string
        $userId = $_GET["id"] ?? null;

        // If the user ID is missing, redirect to the home page
        if (!$userId) {
            ViewHelper::redirect(BASE_URL);
            return;
        }

        // Get the user's information from the database
        $user = UserDB::get($userId);

        // If the user is not found, display a 404 error
        if (!$user) {
            ViewHelper::error404();
            return;
        }

        // Get the list of starred repositories for the user
        $starredRepos = StarDB::getByUser($userId);

        // Determine if the current user is viewing their own profile
        $isCurrentUser = isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $userId;

        // Render the stars view
        ViewHelper::render("view/user/stars.php", [
            "user" => $user,
            "starredRepos" => $starredRepos,
            "isCurrentUser" => $isCurrentUser
        ]);
    }
    public static function update() {
        // Check if the user is logged in
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        // Get the user's information from the POST request
        $user_id = $_SESSION["user_id"];
        $data = [
            "username" => $_POST["username"] ?? null,
            "email" => $_POST["email"] ?? null,
            "full_name" => $_POST["full_name"] ?? null,
            "bio" => $_POST["bio"] ?? null
        ];

        // Update the user's information in the database
        UserDB::update($user_id, $data);

        // Redirect to the user's profile page
        ViewHelper::redirect(BASE_URL . "user/profile?id=" . $_SESSION["user_id"]);
    }
}