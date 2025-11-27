<?php
namespace App\Dto;
/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="ErrorResponse",
 *     description="error response interface",
 *     @OA\Property(property="error", type="string", example="short description"),
 *     @OA\Property(property="detail", type="string", example="detail")
 * )
 */
class ErrorResponseDto {
    public string $error;
    public string $detail;
    public function __construct($error, $detail) {
        $this->error = $error;
        $this->detail = $detail;
    }
}
