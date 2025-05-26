<?php
require_once("model/RepoDB.php");
require_once("ViewHelper.php");


class FileController {
    public static function edit() {
        $repo_id = $_GET['repo_id'] ?? null;
        $file = $_GET['file'] ?? null;
        $file_name = basename($file);

        if (!$repo_id || !$file) {
            die("Missing parameters.");
        }

        require_once 'view/file/edit.php';
    }


    public static function update() {
        if (!isset($_POST["repo_id"], $_POST["file"], $_POST["content"], $_POST["message"])) {
            ViewHelper::error404();
            return;
        }

        $file = $_POST["file"];

        if (!file_exists($file) || strpos(realpath($file), realpath("uploads/")) !== 0) {
            ViewHelper::error404();
            return;
        }

        file_put_contents($file, $_POST["content"]);

        CommitDB::insert([
            "repo_id" => $_POST["repo_id"],
            "branch_id" => BranchDB::getDefault($_POST["repo_id"])["id"],
            "user_id" => $_SESSION["user_id"],
            "parent_commit_id" => null,
            "message" => $_POST["message"]
        ]);

        ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $_POST["repo_id"]);
    }
    public static function new() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $repoId = $_POST["repo_id"];
            $repo = RepoDB::get($repoId);
            $repoName = $repo["name"];
            $userId = $_SESSION["user_id"];
            $message = $_POST["message"];

            $repoPath = "uploads/" . $userId . "/" . $repoName;
            if (!is_dir($repoPath)) {
                mkdir($repoPath, 0777, true);
            }

            $files = $_FILES["files"];
            $uploadedFiles = [];

            foreach ($files["tmp_name"] as $index => $tmpPath) {
                if ($files["error"][$index] !== UPLOAD_ERR_OK) {
                    continue; // preskoči, če je prišlo do napake
                }

                $filename = basename($files["name"][$index]);
                $targetPath = $repoPath . "/" . $filename;

                if (move_uploaded_file($tmpPath, $targetPath)) {
                    RepoDB::insertFile($repoId, $filename, $targetPath);
                    $uploadedFiles[] = $filename;
                }
            }

            // Samo če smo dejansko kaj naložili
            if (count($uploadedFiles) > 0) {
                CommitDB::insert([
                    "repo_id" => $repoId,
                    "branch_id" => BranchDB::getDefault($repoId)["id"],
                    "user_id" => $userId,
                    "parent_commit_id" => null,
                    "message" => $message
                ]);
            }

            ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $repoId);
        } else {
            if (!isset($_GET["repo_id"])) ViewHelper::error404();
            $repo = RepoDB::get($_GET["repo_id"]);
            ViewHelper::render("view/file/new.php", ["repo" => $repo]);
        }
    }
    public static function delete() {
        if (!isset($_POST["repo_id"]) || !isset($_POST["file"])) {
            ViewHelper::error404();
            return;
        }

        $file = $_POST["file"];
        if (!file_exists($file) || strpos(realpath($file), realpath("uploads/")) !== 0) {
            ViewHelper::error404();
            return;
        }
        # unlink() bo odstranil datoteko
        unlink($file);

        CommitDB::insert([
            "repo_id" => $_POST["repo_id"],
            "branch_id" => BranchDB::getDefault($_POST["repo_id"])["id"],
            "user_id" => $_SESSION["user_id"],
            "parent_commit_id" => null,
            "message" => "Deleted file: " . basename($file)
        ]);

        ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $_POST["repo_id"]);
    }
    
    public static function view() {
        $repo_id = $_GET['repo_id'] ?? null;
        $file = $_GET['file'] ?? null;
        $file_name = basename($file);

        if (!$repo_id || !$file || !file_exists($file)) {
            die("Missing parameters.");
        }

        require_once 'view/file/view.php';
    }
}