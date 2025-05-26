<?php
require_once "DBInit.php";

class StarDB {
    public static function add($userId, $repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO stars (user_id, repo_id) VALUES (?, ?)");
        $stmt->execute([$userId, $repoId]);
        return $stmt->rowCount();
    }

    public static function remove($userId, $repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("DELETE FROM stars WHERE user_id = ? AND repo_id = ?");
        $stmt->execute([$userId, $repoId]);
        return $stmt->rowCount();
    }

    public static function isStarred($userId, $repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM stars WHERE user_id = ? AND repo_id = ?");
        $stmt->execute([$userId, $repoId]);
        $result = $stmt->fetch();
        return $result["count"] > 0;
    }

    public static function getCount($repoId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM stars WHERE repo_id = ?");
        $stmt->execute([$repoId]);
        $result = $stmt->fetch();
        return $result["count"];
    }

    public static function getByUser($userId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT r.*, u.username 
                             FROM stars s
                             JOIN repositories r ON s.repo_id = r.id
                             JOIN users u ON r.user_id = u.id
                             WHERE s.user_id = ?
                             ORDER BY s.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}