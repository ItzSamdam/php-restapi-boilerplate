<?php

namespace App\Core;

class Request
{
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function getBody()
    {
        $body = [];

        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->getMethod() === 'POST' || $this->getMethod() === 'PUT' || $this->getMethod() === 'DELETE') {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            foreach ($input as $key => $value) {
                $body[$key] = is_array($value) ? $value : filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    public function getHeaders()
    {
        return getallheaders();
    }

    public function getAuthorizationHeader()
    {
        $headers = $this->getHeaders();
        return $headers['Authorization'] ?? $headers['authorization'] ?? null;
    }
}
