<?php
session_start();

require_once("controller/AuthController.php");
require_once("controller/RepoController.php");
require_once("controller/CommitController.php");
require_once("controller/UserController.php");
require_once("controller/IssueController.php");
require_once("controller/FileController.php");
require_once("ViewHelper.php");

define("BASE_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php"));
define("ASSETS_URL", BASE_URL . "assets/");

$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

$urls = [
    # AUTH
    "login" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            AuthController::login();
        } else {
            AuthController::showLoginForm();
        }
    },
    "logout" => function() {
        AuthController::logout();
    },
    "register" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            AuthController::register();
        } else {
            AuthController::showRegisterForm();
        }
    },

    # REPO
    "repo" => function() {
        RepoController::index();
    },
    "repo/create" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            RepoController::create();
        } else {
            RepoController::showCreateForm();
        }
    },
    "repo/detail" => function() {
        RepoController::detail();
    },
    "repo/edit" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            RepoController::update();
        } else {
            RepoController::showEditForm();
        }
    },
    "repo/delete" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            RepoController::delete();
        }
    },
    "repo/star" => function() {
        RepoController::star();
    },
    "repo/fork" => function() {
        RepoController::fork();
    },

    # COMMIT
    "commit" => function() {
        CommitController::index();
    },
    "commit/create" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            CommitController::create();
        } else {
            CommitController::showCreateForm();
        }
    },
    "commit/detail" => function() {
        CommitController::detail();
    },

    # USER
    "user" => function() {
        UserController::index();
    },
    "user/profile" => function() {
        UserController::profile();
    },
    "user/list" => function() {
        UserController::list();
    },
    "user/edit" => function() {
        UserController::edit();
    },
    "user/update" => function() {
        UserController::update();
    },

    # ISSUE
    "issue" => function() {
        IssueController::index();
    },
    "issue/create" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            IssueController::create();
        } else {
            IssueController::showCreateForm();
        }
    },
    "issue/detail" => function() {
        IssueController::detail();
    },
    "issue/comment" => function() {
        IssueController::addComment();
    },
    "issue/update-status" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            IssueController::updateStatus();
        }
    },

    # FILE
    "file/edit" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            FileController::update();
        } else {
            FileController::edit();
        }
    },
    "file/new" => function() {
        FileController::new();
    },
    "file/delete" => function() {
        FileController::delete();
    },
    "file/view" => function() {
        FileController::view();
    },
    # DASHBOARD
    "dashboard" => function() {
        ViewHelper::render("view/dashboard.php");
    },

    # Default route - Home
    "" => function() {
        $publicRepos = RepoDB::getAllPublic();

        if (isset($_SESSION["user_id"])) {
            $userRepos = RepoDB::getByUser($_SESSION["user_id"]);
            $starredRepos = StarDB::getByUser($_SESSION["user_id"]);
        } else {
            $userRepos = [];
            $starredRepos = [];
        }

        ViewHelper::render("view/home.php", [
            "publicRepos" => $publicRepos,
            "userRepos" => $userRepos,
            "starredRepos" => $starredRepos
        ]);
    }
];

try {
    if (isset($urls[$path])) {
        $urls[$path]();
    } else {
        ViewHelper::error404();
    }
} catch (Exception $e) {
    echo "An error occurred: <pre>$e</pre>";
    // Log the error in a real application
}