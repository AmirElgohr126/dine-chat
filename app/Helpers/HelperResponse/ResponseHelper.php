<?php

use Illuminate\Http\JsonResponse;

function finalResponse($message = "success", // success or failed
                                $statusCode = 200,
                                $data = null,
                                $pagnation = null,
                                $errors = null) : JsonResponse
    {
        return response()->json([
            "message" => $message,
            "data" => $data,
            'pagination' => $pagnation,
            'errors' => $errors
        ],$statusCode);
    }


function pagnationResponse($model) : array
    {
        return [
            'current_page' => $model->currentPage(),
            'last_page' => $model->lastPage(),
            'total' => $model->total(),
            'per_page' => $model->perPage(),
        ];
    }
?>
