<?php

namespace App\Models;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $balance;
    public $created_at;
    public $updated_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'balance' => $this->balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'Name is required';
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        return $errors;
    }
}
