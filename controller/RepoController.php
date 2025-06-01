<?php
require_once("model/RepoDB.php");
require_once("model/CommitDB.php");
require_once("model/StarDB.php");
require_once("model/ForkDB.php");
require_once("model/BranchDB.php");
require_once("model/IssueDB.php");
require_once("ViewHelper.php");

class RepoController
{
    public static function index()
    {
        $publicRepos = RepoDB::getAllPublic();

        if (isset($_SESSION["user_id"])) {
            $userRepos = RepoDB::getByUser($_SESSION["user_id"]);
            $starredRepos = StarDB::getByUser($_SESSION["user_id"]);
        } else {
            $userRepos = [];
            $starredRepos = [];
        }

        ViewHelper::render("view/repo/list.php", [
            "publicRepos" => $publicRepos,
            "userRepos" => $userRepos,
            "starredRepos" => $starredRepos
        ]);
    }

    public static function showCreateForm()
    {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        ViewHelper::render("view/repo/create.php");
    }

    public static function create()
    {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $required = ["name", "description"];
        $missing = array_diff($required, array_keys($_POST));

        if (!empty($missing)) {
            ViewHelper::render("view/repo/create.php", [
                "error" => "Please fill in all required fields."
            ]);
            return;
        }

        $data = [
            "name" => $_POST["name"],
            "description" => $_POST["description"],
            "is_public" => isset($_POST["is_public"]),
            "user_id" => $_SESSION["user_id"]
        ];

        try {
            $repoId = RepoDB::insert($data);
            BranchDB::createDefault($repoId);

            // Process file uploads
            if (isset($_FILES['files'])) {
                self::uploadFiles($_FILES['files'], $repoId);
            }

            ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $repoId);
        } catch (Exception $e) {
            ViewHelper::render("view/repo/create.php", [
                "error" => "Repository creation failed: " . $e->getMessage(),
                "formData" => $data
            ]);
        }
    }


    private static function uploadFiles($files, $repoId)
    {
        $userId = $_SESSION["user_id"];
        $repoName = $_POST["name"]; // Assuming 'name' is passed in the POST request
        $uploadDir = "uploads/$userId/$repoName/";

        // Create the directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($files['tmp_name'] as $key => $tmp_name) {
            $file_name = $files['name'][$key];
            $file_tmp = $files['tmp_name'][$key];
            $file_error = $files['error'][$key];

            if ($file_error === UPLOAD_ERR_OK) {
                $file_path = $uploadDir . basename($file_name);

                if (move_uploaded_file($file_tmp, $file_path)) {
                    RepoDB::insertFile($repoId, $file_name, $file_path);
                } else {
                    throw new Exception("Error uploading file: $file_name");
                }
            } else {
                throw new Exception("Error with file: $file_name, error code: $file_error");
            }
        }
    }


    public static function detail()
    {
        if (!isset($_GET["id"])) {
            ViewHelper::error404();
            return;
        }

        $repo = RepoDB::get($_GET["id"]);

        if (!$repo) {
            ViewHelper::error404();
            return;
        }

        if (!$repo["is_public"] && (!isset($_SESSION["user_id"]) || $repo["user_id"] != $_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $branches = BranchDB::getByRepo($repo["id"]);
        $commits = CommitDB::getByRepo($repo["id"], $branches[0]["name"], 5);
        $isStarred = isset($_SESSION["user_id"]) ? StarDB::isStarred($_SESSION["user_id"], $repo["id"]) : false;
        $starCount = StarDB::getCount($repo["id"]);
        $forkCount = ForkDB::getCount($repo["id"]);
        $issues = IssueDB::getByRepo($repo["id"], 3);
        $files = RepoDB::getFilesByRepoId($repo["id"]);

        ViewHelper::render("view/repo/detail.php", [
            "repo" => $repo,
            "branches" => $branches,
            "commits" => $commits,
            "isStarred" => $isStarred,
            "starCount" => $starCount,
            "forkCount" => $forkCount,
            "issues" => $issues,
            "files" => $files,
            "canEdit" => isset($_SESSION["user_id"]) && ($repo["user_id"] == $_SESSION["user_id"])
        ]);
    }

    public static function showEditForm()
    {
        if (!isset($_GET["id"])) {
            ViewHelper::error404();
            return;
        }

        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $repo = RepoDB::get($_GET["id"]);

        if (!$repo || $repo["user_id"] != $_SESSION["user_id"]) {
            ViewHelper::error404();
            return;
        }

        ViewHelper::render("view/repo/edit.php", [
            "repo" => $repo
        ]);
    }

    public static function update()
    {
        if (!isset($_POST["id"])) {
            ViewHelper::error404();
            return;
        }

        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $repo = RepoDB::get($_POST["id"]);

        if (!$repo || $repo["user_id"] != $_SESSION["user_id"]) {
            ViewHelper::error404();
            return;
        }

        $data = [
            "id" => $_POST["id"],
            "name" => $_POST["name"],
            "description" => $_POST["description"],
            "is_public" => isset($_POST["is_public"])
        ];

        try {
            RepoDB::update($data);
            ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $repo["id"]);
        } catch (Exception $e) {
            ViewHelper::render("view/repo/edit.php", [
                "repo" => $repo,
                "error" => "Update failed: " . $e->getMessage()
            ]);
        }
    }

    public static function delete()
    {
        if (!isset($_POST["repo_id"])) { // Changed from "id" to "repo_id"
            ViewHelper::error404();
            return;
        }

        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        $repo = RepoDB::get($_POST["repo_id"]); // Changed from "id" to "repo_id"

        if (!$repo || $repo["user_id"] != $_SESSION["user_id"]) {
            ViewHelper::error404();
            return;
        }

        $repoId = $repo["id"];
        $repoName = $repo["name"];
        $userId = $_SESSION["user_id"];

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/githab/uploads/" . $userId . "/" . $repoName;
        self::deleteDirectory($uploadDir);

        RepoDB::delete($repoId);

        ViewHelper::redirect(BASE_URL . "repo");
    }

    private static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            unlink($dir);
            return true;
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }


    public static function star()
    {
        if (!isset($_SESSION["user_id"])) {
            header('Content-Type: application/json');
            echo json_encode(["success" => false, "message" => "Not logged in"]);
            return;
        }

        if (!isset($_POST["repo_id"]) || !isset($_POST["action"])) {
            header('Content-Type: application/json');
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            return;
        }

        $repoId = $_POST["repo_id"];
        $action = $_POST["action"];
        $userId = $_SESSION["user_id"];

        try {
            if ($action == "star") {
                StarDB::add($userId, $repoId);
            } elseif ($action == "unstar") {
                StarDB::remove($userId, $repoId);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["success" => false, "message" => "Invalid action"]);
                return;
            }

            $starCount = StarDB::getCount($repoId);

            header('Content-Type: application/json');
            echo json_encode(["success" => true, "starCount" => $starCount]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    public static function fork()
    {
        if (!isset($_SESSION["user_id"])) {
            ViewHelper::redirect(BASE_URL . "login");
            return;
        }

        if (!isset($_POST["repo_id"])) {
            ViewHelper::error404();
            return;
        }

        $repoId = $_POST["repo_id"];
        $originalRepo = RepoDB::get($repoId);

        if (!$originalRepo) {
            ViewHelper::error404();
            return;
        }

        if (!$originalRepo["is_public"]) {
            ViewHelper::redirect(BASE_URL . "repo");
            return;
        }

        $newRepoData = [
            "user_id" => $_SESSION["user_id"],
            "name" => $originalRepo["name"],
            "description" => $originalRepo["description"],
            "is_public" => $originalRepo["is_public"]
        ];

        try {
            $newRepoId = RepoDB::insert($newRepoData);
            ForkDB::add($_SESSION["user_id"], $newRepoId, $repoId);

            // Copy default branch
            $originalBranch = BranchDB::getDefault($repoId);
            BranchDB::create($newRepoId, $originalBranch["name"], true);

            // Get the files for the original repository
            $files = RepoDB::getFilesByRepoId($repoId);
            error_log("Files: " . print_r($files, true));

            // Define the base directory for uploads
            $uploadBaseDir = $_SERVER['DOCUMENT_ROOT'] . "/githab/";

            // Get the original repository's directory
            $originalRepoDir = $uploadBaseDir . "uploads/" . $originalRepo["user_id"] . "/" . $originalRepo["name"];
            error_log("Original Repo Dir: " . $originalRepoDir);

            // Create the new repository's directory
            $newRepoDir = $uploadBaseDir . "uploads/" . $_SESSION["user_id"] . "/" . $originalRepo["name"];
            error_log("New Repo Dir: " . $newRepoDir);

            if (!is_dir($newRepoDir)) {
                mkdir($newRepoDir, 0777, true);
                error_log("New Repo Dir Created: " . $newRepoDir);
            }

            // Copy the files to the new repository's directory and create new entries in the uploads table
            foreach ($files as $file) {
                $originalFilePath = $uploadBaseDir . $file["file_path"];
                $newFilePath = $newRepoDir . "/" . $file["file_name"];
                error_log("Original File Path: " . $originalFilePath);
                error_log("New File Path: " . $newFilePath);

                if (file_exists($originalFilePath)) {
                    if (copy($originalFilePath, $newFilePath)) {
                        error_log("File Copied: " . $originalFilePath . " to " . $newFilePath);
                        // Create a new entry in the uploads table for the forked repository
                        RepoDB::insertFile($newRepoId, $file["file_name"], "uploads/" . $_SESSION["user_id"] . "/" . $originalRepo["name"] . "/" . $file["file_name"]);
                    } else {
                        error_log("Failed to copy file: " . $originalFilePath . " to " . $newFilePath);
                    }
                } else {
                    error_log("File Does Not Exist: " . $originalFilePath);
                }
            }

            ViewHelper::redirect(BASE_URL . "repo/detail?id=" . $newRepoId);
        } catch (Exception $e) {
            ViewHelper::render("view/repo/detail.php", [
                "repo" => $originalRepo,
                "error" => "Fork failed: " . $e->getMessage()
            ]);
        }
    }
}
