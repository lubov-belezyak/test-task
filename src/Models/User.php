<?php

namespace Src\Models;

use Src\Services\Database;

class User
{
    private $table = 'users';
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email FROM " . $this->table . " WHERE id=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (first_name, last_name, middle_name, email, password) VALUES (:first_name, :last_name, :middle_name, :email, :password)");
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':middle_name' => $data['middle_name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE " . $this->table . " SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name, email = :email WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':middle_name' => $data['middle_name'],
            ':email' => $data['email'],
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table ." WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}