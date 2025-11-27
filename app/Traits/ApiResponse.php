<?php

namespace App\Traits;

use App\Dto\ErrorResponseDto;

trait ApiResponse
{
    protected function respond($data, $status)
    {
        return response()->json($data, $status);
    }

    protected function respondNodata()
    {
        return response()->noContent();
    }

    protected function error($error, $detail, $status)
    {
        $errorDto = new ErrorResponseDto($error, $detail);
        return $this->respond([
            $errorDto
        ], $status);
    }
}
