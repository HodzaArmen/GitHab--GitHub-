<?php
require_once "DBInit.php";

class BranchDB {
    public static function get($id) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM branches WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByRepo($repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM branches WHERE repo_id = ? ORDER BY is_default DESC, name ASC");
        $stmt->execute([$repoId]);
        return $stmt->fetchAll();
    }

    public static function getDefault($repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM branches WHERE repo_id = ? AND is_default = TRUE");
        $stmt->execute([$repoId]);
        return $stmt->fetch();
    }

    public static function create($repoId, $name, $isDefault = false) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO branches (repo_id, name, is_default) 
                             VALUES (:repo_id, :name, :is_default)");
        $stmt->bindParam(":repo_id", $repoId);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":is_default", $isDefault, PDO::PARAM_BOOL);
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function createDefault($repoId) {
        return self::create($repoId, "main", true);
    }
}