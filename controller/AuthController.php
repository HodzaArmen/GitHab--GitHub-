<?php
require_once("model/UserDB.php");
require_once("ViewHelper.php");

class AuthController {
    public static function showLoginForm($values = ["username" => "", "error" => ""]) {
        ViewHelper::render("view/login.php", $values);
    }

    public static function login() {
        if (!empty($_POST["username"]) && !empty($_POST["password"])) {
            $user = UserDB::getByUsername($_POST["username"]);
            
            if ($user && password_verify($_POST["password"], $user["password"])) {
                $_SESSION["user"] = $user;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                ViewHelper::redirect(BASE_URL . "dashboard");
            } else {
                self::showLoginForm([
                    "username" => $_POST["username"], 
                    "error" => "Invalid username or password."
                ]);
            }
        } else {
            self::showLoginForm([
                "username" => "", 
                "error" => "Please enter both username and password."
            ]);
        }
    }

    public static function logout() {
        unset($_SESSION["user"]);
        unset($_SESSION["user_id"]);
        unset($_SESSION["username"]);
        session_destroy();
        ViewHelper::redirect(BASE_URL);
    }

    public static function showRegisterForm($values = [
        "username" => "", 
        "email" => "", 
        "full_name" => "", 
        "error" => ""
    ]) {
        ViewHelper::render("view/register.php", $values);
    }

    public static function register() {
        $required = ["username", "password", "email"];
        $missing = array_diff($required, array_keys($_POST));
        
        if (!empty($missing)) {
            self::showRegisterForm([
                "username" => $_POST["username"] ?? "",
                "email" => $_POST["email"] ?? "",
                "full_name" => $_POST["full_name"] ?? "",
                "error" => "Please fill in all required fields."
            ]);
            return;
        }
        
        if (UserDB::getByUsername($_POST["username"])) {
            self::showRegisterForm([
                "username" => $_POST["username"],
                "email" => $_POST["email"],
                "full_name" => $_POST["full_name"],
                "error" => "Username already taken."
            ]);
            return;
        }
        
        if (UserDB::getByEmail($_POST["email"])) {
            self::showRegisterForm([
                "username" => $_POST["username"],
                "email" => $_POST["email"],
                "full_name" => $_POST["full_name"],
                "error" => "Email already registered."
            ]);
            return;
        }
        
        $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $userId = UserDB::insert([
            "username" => $_POST["username"],
            "password" => $hashedPassword,
            "email" => $_POST["email"],
            "full_name" => $_POST["full_name"] ?? null
        ]);
        
        if ($userId) {
            $_SESSION["user_id"] = $userId;
            $_SESSION["username"] = $_POST["username"];
            ViewHelper::redirect(BASE_URL . "dashboard");
        } else {
            self::showRegisterForm([
                "username" => $_POST["username"],
                "email" => $_POST["email"],
                "full_name" => $_POST["full_name"],
                "error" => "Registration failed. Please try again."
            ]);
        }
    }
}