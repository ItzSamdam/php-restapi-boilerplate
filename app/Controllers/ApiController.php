<?php

namespace App\Controllers;

use App\Core\Response;

class ApiController
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    protected function success($data = null, $message = 'Success', $statusCode = 200)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        $this->response->json($response, $statusCode);
    }

    protected function error($message = 'Error', $statusCode = 400, $errors = [])
    {
        $response = ['success' => false, 'message' => $message];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        $this->response->json($response, $statusCode);
    }

    protected function validateRequiredFields($data, $requiredFields)
    {
        $errors = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = "The {$field} field is required";
            }
        }
        return $errors;
    }
}
