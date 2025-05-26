<?php
require_once "DBInit.php";

class IssueDB {
    public static function get($id) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT i.*, u.username as author_name, r.name as repo_name
                             FROM issues i
                             JOIN users u ON i.user_id = u.id
                             JOIN repositories r ON i.repo_id = r.id
                             WHERE i.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByRepo($repoId, $limit = null, $status = null) {
        $db = DBInit::getInstance();
        
        $sql = "SELECT i.*, u.username as author_name
            FROM issues i
            JOIN users u ON i.user_id = u.id
            WHERE i.repo_id = ?";
        
        $params = [$repoId];
        
        if ($status) {
            $sql .= " AND i.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY i.created_at DESC";
        
        if ($limit !== null) {
            // dodamo LIMIT direktno kot integer, brez bind parametra
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    public static function getComments($issueId) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT ic.*, u.username as author_name
                             FROM issue_comments ic
                             JOIN users u ON ic.user_id = u.id
                             WHERE ic.issue_id = ?
                             ORDER BY ic.created_at ASC");
        $stmt->execute([$issueId]);
        return $stmt->fetchAll();
    }

    public static function insert($data) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO issues 
                             (repo_id, user_id, title, description)
                             VALUES (:repo_id, :user_id, :title, :description)");
        $stmt->bindParam(":repo_id", $data["repo_id"]);
        $stmt->bindParam(":user_id", $data["user_id"]);
        $stmt->bindParam(":title", $data["title"]);
        $stmt->bindParam(":description", $data["description"]);
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function addComment($issueId, $userId, $comment) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO issue_comments 
                             (issue_id, user_id, comment)
                             VALUES (?, ?, ?)");
        $stmt->execute([$issueId, $userId, $comment]);
        return $db->lastInsertId();
    }

    public static function updateStatus($issueId, $status) {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("UPDATE issues SET 
                             status = ?,
                             closed_at = CASE WHEN ? = 'closed' THEN NOW() ELSE NULL END
                             WHERE id = ?");
        $stmt->execute([$status, $status, $issueId]);
        return $stmt->rowCount();
    }
}