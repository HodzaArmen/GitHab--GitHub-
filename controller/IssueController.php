<?php
require_once("model/IssueDB.php");
require_once("model/RepoDB.php");
require_once("ViewHelper.php");

class IssueController {
    public static function index() {
        if (!isset($_GET["repo_id"])) {
            ViewHelper::error404();
            return;
        }

        $repoId = $_GET["repo_id"];
        $repo = RepoDB::get($repoId);

        if (!$repo) {
            ViewHelper::error404();
            return;
        }

        if (!$repo["is_public"] && (!isset($_SESSION["user_id"]) || $repo["user_id"] != $_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $status = isset($_GET["status"]) ? $_GET["status"] : "open";
        $issues = IssueDB::getByRepo($repoId, null, $status);

        $starCount = StarDB::getCount($repoId);
        $isStarred = isset($_SESSION["user_id"]) ? StarDB::isStarred($_SESSION["user_id"], $repoId) : false;

        $forkCount = ForkDB::getCount($repoId);

        ViewHelper::render("view/issue/list.php", [
            "repo" => $repo,
            "issues" => $issues,
            "status" => $status,
            "canEdit" => isset($_SESSION["user_id"]) && ($repo["user_id"] == $_SESSION["user_id"]),
            "isStarred" => $isStarred,
            "starCount" => $starCount,
            "forkCount" => $forkCount
        ]);
    }

    public static function showCreateForm() {
        if (!isset($_GET["repo_id"])) {
            ViewHelper::error404();
            return;
        }
        
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }
        
        $repoId = $_GET["repo_id"];
        $repo = RepoDB::get($repoId);
        
        if (!$repo) {
            ViewHelper::error404();
            return;
        }
        
        ViewHelper::render("view/issue/create.php", [
            "repo" => $repo
        ]);
    }

    public static function create() {
        if (!isset($_POST["repo_id"])) {
            ViewHelper::error404();
            return;
        }
        
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }
        
        $required = ["repo_id", "title", "description"];
        $missing = array_diff($required, array_keys($_POST));
        
        if (!empty($missing)) {
            ViewHelper::render("view/issue/create.php", [
                "repo" => RepoDB::get($_POST["repo_id"]),
                "error" => "Please fill in all required fields."
            ]);
            return;
        }
        
        $repo = RepoDB::get($_POST["repo_id"]);
        if (!$repo) {
            ViewHelper::error404();
            return;
        }
        
        $issueId = IssueDB::insert([
            "repo_id" => $repo["id"],
            "user_id" => $_SESSION["user_id"],
            "title" => $_POST["title"],
            "description" => $_POST["description"]
        ]);
        
        if ($issueId) {
            ViewHelper::redirect(BASE_URL . "issue/detail?id=" . $issueId);
        } else {
            ViewHelper::render("view/issue/create.php", [
                "repo" => $repo,
                "error" => "Failed to create issue."
            ]);
        }
    }

    public static function detail() {
        if (!isset($_GET["id"])) {
            ViewHelper::error404();
            return;
        }
        
        $issue = IssueDB::get($_GET["id"]);
        if (!$issue) {
            ViewHelper::error404();
            return;
        }
        
        $repo = RepoDB::get($issue["repo_id"]);
        
        if (!$repo["is_public"] && (!isset($_SESSION["user_id"]) || $repo["user_id"] != $_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }
        
        $comments = IssueDB::getComments($issue["id"]);
        
        ViewHelper::render("view/issue/detail.php", [
            "issue" => $issue,
            "repo" => $repo,
            "comments" => $comments,
            "canEdit" => isset($_SESSION["user_id"]) && ($repo["user_id"] == $_SESSION["user_id"])
        ]);
    }

    public static function addComment() {
        if (!isset($_POST["issue_id"])) {
            ViewHelper::error404();
            return;
        }
        
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }
        
        $issueId = $_POST["issue_id"];
        $issue = IssueDB::get($issueId);
        
        if (!$issue) {
            ViewHelper::error404();
            return;
        }
        if (empty($_POST["comment"])) {
            ViewHelper::render("view/issue/detail.php", [
                "issue" => $issue,
                "repo" => RepoDB::get($issue["repo_id"]),
                "error" => "Comment cannot be empty."
            ]);
            return;
        }
        IssueDB::addComment(
            $issueId,
            $_SESSION["user_id"],
            $_POST["comment"]
        );
        
        ViewHelper::redirect(BASE_URL . "issue/detail?id=" . $issueId);
    }

    public static function updateStatus() {
        if (!isset($_POST["id"]) || !isset($_POST["status"])) {
            ViewHelper::error404();
            return;
        }
        
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }
        
        $issueId = $_POST["id"];
        $issue = IssueDB::get($issueId);
        
        if (!$issue) {
            ViewHelper::error404();
            return;
        }
        
        IssueDB::updateStatus($issueId, $_POST["status"]);
        
        ViewHelper::redirect(BASE_URL . "issue/detail?id=" . $issueId);
    }
}