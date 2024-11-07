<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Services\Database;

class UserController
{
    private $user;

    public function __construct()
    {
        $this->user = new User(new Database());
    }

    public function index()
    {
        return $this->user->getAll();
    }

    public function store($data)
    {
        return $this->user->create($data);
    }

    public function show($id)
    {
        return $this->user->find($id);
    }

    public function update($id, $data)
    {
        return $this->user->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->user->delete($id);
    }
}