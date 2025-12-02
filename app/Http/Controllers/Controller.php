<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function success($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    public function error($data, $message = 'Error', $status = 400)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'data'    => $data
        ], $status);
    }
}
