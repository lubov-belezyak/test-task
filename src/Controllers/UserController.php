<?php

namespace Src\Controllers;
use Src\Models\User;

class UserController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function index(){
        $users = $this->userModel->getAll();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function show($id){
        $user = $this->userModel->findById($id);
        if($user){
            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode([
                'error' => [
                    'message' => 'User not found'
                ]
            ]);
        }
    }

    public function store($data){
        $errors = $this->userModel->validate($data);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }
        $result = $this->userModel->create($data);
        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    }

    public function update($data, $id){
        $existingUser = $this->userModel->findById($id);
        if (!$existingUser) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        $errors = $this->userModel->validate($data, $id);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $result = $this->userModel->update($id, $data);
        http_response_code(200);
        echo json_encode(['message' => 'User updated successfully']);
    }

    public function delete($id){
        $existingUser = $this->userModel->findById($id);
        if (!$existingUser) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        $result = $this->userModel->delete($id);
        http_response_code(200);
        echo json_encode(['message' => 'User deleted successfully']);
    }
}