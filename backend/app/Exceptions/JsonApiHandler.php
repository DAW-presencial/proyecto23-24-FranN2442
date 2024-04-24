<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;


class JsonApiHandler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            return response()->json(
                [
                    'error' => [
                        'message' => $exception->getMessage(),
                        'status_code' => $exception->getStatusCode()
                    ]
                ],
                $exception->getStatusCode(),
                ['Content-Type' => 'application/vnd.api+json']
            );
        }

        return parent::render($request, $exception);
    }
}
