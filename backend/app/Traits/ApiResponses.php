<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    function successResponse(mixed $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($data, $statusCode);
    }

    function errorResponse(mixed $data = [], string $message = '', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        if (!$message) {
            $message = Response::$statusTexts[$statusCode];
        }

        $responseData = [
            'message' => $message,
        ];
        if (empty($data)) {
            return new JsonResponse($responseData, $statusCode);
        }
        if (is_array($data) && !array_is_list($data)) {
            $responseData = [...$responseData, ...$data];
            return new JsonResponse($responseData, $statusCode);
        }
        $responseData['data'] = $data;
        return new JsonResponse($responseData, $statusCode);
    }

    function ok(mixed $data): JsonResponse
    {
        return $this->successResponse($data);
    }

    function created(mixed $data): JsonResponse
    {
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    function noContent(): JsonResponse
    {
        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    function badRequest(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_BAD_REQUEST);
    }

    function unauthorized(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_UNAUTHORIZED);
    }


    function forbidden(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_FORBIDDEN);
    }


    function notFound(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

    function conflict(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_CONFLICT);
    }

    function unprocessable(mixed $data, string $message = ''): JsonResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
