<?php
require_once "DBInit.php";

class ForkDB {
    public static function add($userId, $repoId, $parentRepoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO forks (user_id, repo_id, parent_repo_id) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $repoId, $parentRepoId]);
        return $stmt->rowCount();
    }

    public static function getCount($repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM forks WHERE parent_repo_id = ?");
        $stmt->execute([$repoId]);
        $result = $stmt->fetch();
        return $result["count"];
    }

    public static function getByUser($userId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT r.*, u.username 
                             FROM forks f
                             JOIN repositories r ON f.repo_id = r.id
                             JOIN users u ON r.user_id = u.id
                             WHERE f.user_id = ?
                             ORDER BY f.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}