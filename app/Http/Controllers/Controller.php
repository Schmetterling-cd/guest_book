<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

abstract class Controller
{

    public function getApiResponse($data, string $message = ''): array
    {

        if (is_bool($data)) {
            $response = ['status' => $data];
        } else {
            $response = ['data' => $data];
        }

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return $response;

    }

}
