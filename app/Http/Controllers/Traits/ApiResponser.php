<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;


trait ApiResponser
{
    /**
     * Success Response.
     *
     * @param mixed $data
     * @param int   $code
     * @param $message
     *
     * @return JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK, $message = '', $type = 'success', $event = '')
    {
        return response()->json(
            [
                'type' => $type,
                'message' => $message,
                'code' => $code,
                //Additional//
                'data' => $data,
                'event' => $event,
            ],
            $code
        );
    }




    /**
     * Error Response.
     *
     * @param $message
     * @param int $code
     *
     * @return JsonResponse
     */
    public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(
            [
                'type' => 'error',
                'message' => $message,
                'code' => $code,
                //Additional
                'waktu' => date('d M Y H:i:s'),
            ],
            $code
        );
    }
}
