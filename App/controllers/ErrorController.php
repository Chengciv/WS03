<?php

namespace App\Controllers;

class ErrorController
{
    /**
     * Error 404 not found
     */
    public static function notFound($message = 'Page not found')
    {
        http_response_code(404);
        loadView('error', [
            'status' => '404 Error',
            'message' => $message
        ]);
    }

    /**
     * Error 403 Unauthorized error
     */
    public static function unauthorized($message = 'You are not authorized to view this page')
    {
        http_response_code(403);
        loadView('error', [
            'status' => '403 Error',
            'message' => $message
        ]);
    }
}