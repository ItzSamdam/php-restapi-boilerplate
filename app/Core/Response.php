<?php

namespace App\Core;

class Response
{
    public function setStatusCode($code)
    {
        http_response_code($code);
    }

    public function json($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function send($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        echo $data;
        exit;
    }

    public function notFound($message = 'Not Found')
    {
        $this->json(['error' => $message], 404);
    }

    public function unauthorized($message = 'Unauthorized')
    {
        $this->json(['error' => $message], 401);
    }

    public function validationError($errors)
    {
        $this->json(['errors' => $errors], 422);
    }

    public function serverError($message = 'Internal Server Error')
    {
        $this->json(['error' => $message], 500);
    }
}
