<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $id = parent::create($data);
        return $this->find($id);
    }

    public function update($id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        parent::update($id, $data);
        return $this->find($id);
    }

    public function findByEmail($email)
    {
        $result = $this->findBy('email', $email);
        return $result ? new User($result[0]) : null;
    }

    public function updateBalance($userId, $amount)
    {
        $query = "UPDATE {$this->table} SET balance = balance + :amount WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':id', $userId);
        return $stmt->execute();
    }
}
