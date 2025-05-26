<?php
require_once("model/CommitDB.php");
require_once("model/RepoDB.php");
require_once("model/BranchDB.php");
require_once("ViewHelper.php");

class CommitController {
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

        $branches = BranchDB::getByRepo($repoId);
        $currentBranch = isset($_GET["branch"]) ? $_GET["branch"] : $branches[0]["name"];
        $commits = CommitDB::getByRepo($repoId, $currentBranch);

        $starCount = StarDB::getCount($repoId);
        $isStarred = isset($_SESSION["user_id"]) ? StarDB::isStarred($_SESSION["user_id"], $repoId) : false;

        $forkCount = ForkDB::getCount($repoId);

        ViewHelper::render("view/commit/list.php", [
            "repo" => $repo,
            "branches" => $branches,
            "currentBranch" => $currentBranch,
            "commits" => $commits,
            "canEdit" => isset($_SESSION["user_id"]) && ($repo["user_id"] == $_SESSION["user_id"]),
            "isStarred" => $isStarred,
            "starCount" => $starCount,
            "forkCount" => $forkCount
        ]);
    }

    public static function showCreateForm() {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        if (!isset($_GET["repo_id"])) {
            ViewHelper::error404();
            return;
        }

        $repoId = $_GET["repo_id"];
        $repo = RepoDB::get($repoId);

        if (!$repo || $repo["user_id"] != $_SESSION["user_id"]) {
            ViewHelper::error404();
            return;
        }

        $branches = BranchDB::getByRepo($repoId);

        ViewHelper::render("view/commit/create.php", [
            "repo" => $repo,
            "branches" => $branches
        ]);
    }

    public static function create() {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $required = ["repo_id", "branch_id", "message"];
        $missing = array_diff($required, array_keys($_POST));

        if (!empty($missing)) {
            ViewHelper::redirect(BASE_URL . "commit/create?repo_id=" . $_POST["repo_id"]);
            return;
        }

        $repo = RepoDB::get($_POST["repo_id"]);
        if (!$repo || $repo["user_id"] != $_SESSION["user_id"]) {
            ViewHelper::error404();
            return;
        }

        $branch = BranchDB::get($_POST["branch_id"]);
        if (!$branch || $branch["repo_id"] != $repo["id"]) {
            ViewHelper::error404();
            return;
        }

        $commitId = CommitDB::insert([
            "repo_id" => $repo["id"],
            "branch_id" => $branch["id"],
            "user_id" => $_SESSION["user_id"],
            "message" => $_POST["message"]
        ]);

        if ($commitId) {
            ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $repo["id"]);
        } else {
            ViewHelper::render("view/commit/create.php", [
                "repo" => $repo,
                "branches" => BranchDB::getByRepo($repo["id"]),
                "error" => "Failed to create commit."
            ]);
        }
    }

    public static function detail() {
        if (!isset($_GET["id"])) {
            ViewHelper::error404();
            return;
        }

        $commit = CommitDB::get($_GET["id"]);
        if (!$commit) {
            ViewHelper::error404();
            return;
        }

        $repo = RepoDB::get($commit["repo_id"]);
        $branch = BranchDB::get($commit["branch_id"]);

        ViewHelper::render("view/commit/detail.php", [
            "commit" => $commit,
            "repo" => $repo,
            "branch" => $branch,
            "canEdit" => isset($_SESSION["user_id"]) && ($repo["user_id"] == $_SESSION["user_id"])
        ]);
    }
}