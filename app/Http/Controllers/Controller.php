<?php

namespace App\Http\Controllers;

abstract class Controller
{

    public function getApiResponse($data, string $message = ''): array
    {

        $response = [];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        if (is_bool($data)) {
            $response['status'] = $data;
            return $response;
        } else {
            $response = ['data' => $data];
        }

        if (empty($response['status'])) {
            $response['status'] = true;
        }

        return $response;

    }

}
