<?php

namespace Otodev\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Otodev\Utils\ResponseUtil;

trait HasApiResponse
{

    /**
     * @param        $result
     * @param string $message
     * @param int $statusCode
     * @param int $code
     *
     * @return JsonResponse
     */
    public function responseWithCode($result, $message = '', $statusCode = 200, $code = 200)
    {
        return Response::json(ResponseUtil::response($message, $result, $statusCode), $code);
    }

    /**
     * @param        $result
     * @param string $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function response($result, $message = '', $statusCode = 200)
    {
        return Response::json(ResponseUtil::response($message, $result, $statusCode), $statusCode);
    }

    /**
     * @param       $message
     * @param array $result
     *
     * @return JsonResponse
     */
    public function unauthorized($message, $result = [])
    {
        return Response::json(ResponseUtil::error($message, $result, 401), 401);
    }

    /**
     * @param       $message
     * @param array $result
     * @param int $statusCode
     * @param int $code
     *
     * @return JsonResponse
     */
    public function error($message, $result = [], $statusCode = 423, $code = 423)
    {
        return Response::json(ResponseUtil::error($message, $result, $statusCode), $code);
    }

    /**
     * @param     $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    public function success($message, $statusCode = 200)
    {
        return Response::json(ResponseUtil::success($message, $statusCode), 200);
    }
}
