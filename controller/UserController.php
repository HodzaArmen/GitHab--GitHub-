<?php
require_once("model/UserDB.php");
require_once("model/RepoDB.php");
require_once("model/StarDB.php");
require_once("ViewHelper.php");

class UserController {
    public static function index() {
        $users = UserDB::getAllWithRepoCount();
        ViewHelper::render("view/user/index.php", ["users" => $users]);
    }

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
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $user = UserDB::get($_SESSION["user_id"]);

        ViewHelper::render("view/user/edit.php", ["user" => $user]);
    }

    public static function stars() {
        $userId = $_GET["id"] ?? null;

        if (!$userId) {
            ViewHelper::redirect(BASE_URL);
            return;
        }

        $user = UserDB::get($userId);

        if (!$user) {
            ViewHelper::error404();
            return;
        }

        $starredRepos = StarDB::getByUser($userId);

        $isCurrentUser = isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $userId;

        ViewHelper::render("view/user/stars.php", [
            "user" => $user,
            "starredRepos" => $starredRepos,
            "isCurrentUser" => $isCurrentUser
        ]);
    }
    public static function update() {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $user_id = $_SESSION["user_id"];
        $data = [
            "username" => $_POST["username"] ?? null,
            "email" => $_POST["email"] ?? null,
            "full_name" => $_POST["full_name"] ?? null,
            "bio" => $_POST["bio"] ?? null
        ];

        UserDB::update($user_id, $data);

        ViewHelper::redirect(BASE_URL . "user/profile?id=" . $_SESSION["user_id"]);
    }
}