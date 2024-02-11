<?php

namespace Otodev\Utils;

/**
 * Class ResponseUtil
 * @package Otodev\Utils
 */
class ResponseUtil
{
    const RESPONSE = ['success', 'data', 'code', 'message'];
    const SUCCESS = ['success', 'data', 'code', 'message'];
    const ERROR = ['success', 'code', 'message'];

    /**
     * @param     $message
     * @param     $data
     * @param int $code
     *
     * @return array
     */
    public static function response($message, $data, $code = 200)
    {
        return ['success' => true, 'data' => $data, 'code' => $code, 'message' => $message];
    }

    /**
     * @param       $message
     * @param array $data
     * @param int $code
     *
     * @return array
     */
    public static function error($message, array $data = [], $code = 423)
    {
        return ['success' => false, 'data' => $data, 'code' => $code, 'message' => $message];
    }

    /**
     * @param     $message
     * @param int $code
     *
     * @return array
     */
    public static function success($message, $code = 200)
    {
        return ['success' => true, 'message' => $message, 'code' => $code, 'data' => []];
    }
}
