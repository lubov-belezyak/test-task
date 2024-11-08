<?php

namespace Src\Models;

use Src\Services\Database;

class User
{
    protected $db;
    private $table = 'users';
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;

    public function __construct()
    {
        $this->db = (new Database())->getPdo();
    }

    public function validate($data, $id = null){
        $errors = [];

        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        }
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is invalid';
        } else {
            $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
            if ($id) {
                $query .= " AND id != :id";
            }
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $data['email']);
            if ($id) {
                $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors['email'] = 'Email already exists';
            }
        }

        if (empty($data['password']) && !$id) {
            $errors['password'] = 'Password is required';
        } elseif (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long';
        }

        return $errors;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT id, first_name, last_name, email FROM " . $this->table);
        return $stmt->fetchAll();
    }

    // Получаем пользователя по ID
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Создаем нового пользователя
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (first_name, last_name, middle_name, email, password) 
                                    VALUES (:first_name, :last_name, :middle_name, :email, :password)");
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':middle_name', $data['middle_name']);
        $stmt->bindParam(':email', $data['email']);
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $passwordHash);
        return $stmt->execute();
    }

    // Обновляем данные пользователя
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE " . $this->table . " SET first_name = :first_name, last_name = :last_name, 
                                    middle_name = :middle_name, email = :email, password = :password
                                    WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':middle_name', $data['middle_name']);
        $stmt->bindParam(':email', $data['email']);
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $passwordHash);
        return $stmt->execute();
    }

    // Удаляем пользователя
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}