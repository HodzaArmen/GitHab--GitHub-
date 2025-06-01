<?php
require_once "DBInit.php";

class RepoDB
{
    public static function get($id)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT r.*, u.username 
                             FROM repositories r
                             JOIN users u ON r.user_id = u.id
                             WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByUser($userId)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT r.*, u.username 
                             FROM repositories r
                             JOIN users u ON r.user_id = u.id
                             WHERE r.user_id = ?
                             ORDER BY r.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function getAllPublic()
    {
        $db = DBInit::getInstance();
        $stmt = $db->query("SELECT r.*, u.username 
                           FROM repositories r
                           JOIN users u ON r.user_id = u.id
                           WHERE r.is_public = TRUE
                           ORDER BY r.created_at DESC");
        return $stmt->fetchAll();
    }

    public static function insert($data)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO repositories 
                             (user_id, name, description, is_public) 
                             VALUES (:user_id, :name, :description, :is_public)");
        $stmt->bindParam(":user_id", $data["user_id"]);
        $stmt->bindParam(":name", $data["name"]);
        $stmt->bindParam(":description", $data["description"]);
        $stmt->bindParam(":is_public", $data["is_public"], PDO::PARAM_BOOL);
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function update($data)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("UPDATE repositories SET 
                             name = :name,
                             description = :description,
                             is_public = :is_public
                             WHERE id = :id");
        $stmt->bindParam(":id", $data["id"]);
        $stmt->bindParam(":name", $data["name"]);
        $stmt->bindParam(":description", $data["description"]);
        $stmt->bindParam(":is_public", $data["is_public"], PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public static function delete($id)
    {
        $db = DBInit::getInstance();

        // Delete all files associated with the repository
        $stmt = $db->prepare("DELETE FROM uploads WHERE repo_id = ?");
        $stmt->execute([$id]);

        // Delete fork count, if this repo was forked
        $stmt = $db->prepare("DELETE FROM forks WHERE parent_repo_id = ?");
        $stmt->execute([$id]);

        // Now delete the repository itself
        $stmt = $db->prepare("DELETE FROM stars WHERE repo_id = ?");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM forks WHERE repo_id = ?");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM issue_comments WHERE issue_id IN (SELECT id FROM issues WHERE repo_id = ?)");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM issues WHERE repo_id = ?");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM commits WHERE repo_id = ?");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM branches WHERE repo_id = ?");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM repositories WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

    public static function insertFile($repoId, $fileName, $filePath)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO uploads (repo_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->execute([$repoId, $fileName, $filePath]);
    }

    public static function getFilesByRepoId($repoId)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM uploads WHERE repo_id = ?");
        $stmt->execute([$repoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
