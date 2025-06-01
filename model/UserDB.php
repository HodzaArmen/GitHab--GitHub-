<?php
require_once "DBInit.php";

class UserDB
{
    public static function get($id)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByUsername($username)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public static function getByEmail($email)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function getAll()
    {
        $db = DBInit::getInstance();
        $stmt = $db->query("SELECT * FROM users ORDER BY username");
        return $stmt->fetchAll();
    }

    public static function insert($data)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("INSERT INTO users (username, password, email, full_name) 
                             VALUES (:username, :password, :email, :full_name)");
        $stmt->bindParam(":username", $data["username"]);
        $stmt->bindParam(":password", $data["password"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":full_name", $data["full_name"]);
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("UPDATE users SET 
                             username = :username,
                             email = :email,
                             full_name = :full_name,
                             bio = :bio
                             WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":username", $data["username"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":full_name", $data["full_name"]);
        $stmt->bindParam(":bio", $data["bio"]);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function getAllWithRepoCount()
    {
        $db = DBInit::getInstance();
        $stmt = $db->prepare("SELECT u.*, COUNT(r.id) AS repo_count
                         FROM users u
                         LEFT JOIN repositories r ON u.id = r.user_id
                         GROUP BY u.id");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}