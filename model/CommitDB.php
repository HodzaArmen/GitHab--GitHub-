<?php
require_once "DBInit.php";

class CommitDB {
    public static function get($id) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT c.*, u.username, b.name as branch_name
                             FROM commits c
                             JOIN users u ON c.user_id = u.id
                             JOIN branches b ON c.branch_id = b.id
                             WHERE c.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByRepo($repoId, $branchName = null, $limit = null) {
        $db = DBInit::getInstance();

        $sql = "SELECT c.*, u.username, b.name as branch_name
            FROM commits c
            JOIN users u ON c.user_id = u.id
            JOIN branches b ON c.branch_id = b.id
            WHERE c.repo_id = ?";

        $params = [$repoId];

        if ($branchName) {
            $sql .= " AND b.name = ?";
            $params[] = $branchName;
        }

        $sql .= " ORDER BY c.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit); 
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    public static function insert($data) {
        $db = DBInit::getInstance();
        $hash = sha1(uniqid($data["repo_id"] . $data["branch_id"] . $data["user_id"], true));
        
        $stmt = $db->prepare("INSERT INTO commits 
                             (repo_id, branch_id, user_id, parent_commit_id, message, hash)
                             VALUES (:repo_id, :branch_id, :user_id, :parent_commit_id, :message, :hash)");
        $stmt->bindParam(":repo_id", $data["repo_id"]);
        $stmt->bindParam(":branch_id", $data["branch_id"]);
        $stmt->bindParam(":user_id", $data["user_id"]);
        $stmt->bindParam(":parent_commit_id", $data["parent_commit_id"], PDO::PARAM_INT);
        $stmt->bindParam(":message", $data["message"]);
        $stmt->bindParam(":hash", $hash);
        $stmt->execute();
        return $db->lastInsertId();
    }
}