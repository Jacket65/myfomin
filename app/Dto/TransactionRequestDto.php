<?php

namespace App\Dto;
/* @OA\Schema(
 *     schema="Transaction",
 *     type="object",
 *     title="Transaction",
 *     description="Transaction with related category and account data",
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="account_id", type="integer", example=2),
 *     @OA\Property(property="type", type="string", example="spend"),
 *     @OA\Property(property="amount", type="number", format="float", example=100.0),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-31 14:55:30"),
 *     @OA\Property(property="description", type="string", example="hello"),
 *     @OA\Property(property="category_id", type="integer", example=2),
 *
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category"
    *     ),
 *
 *     @OA\Property(
 *         property="account",
 *         ref="#/components/schemas/Account"
    *     )
 * )
 */
class TransactionRequestDto {

    public string $type;
    public int $account_id;
    public int $category_id;
    public int $amount;
    public ?string $description;

    public function __construct(array $data) {
        $this->type = $data['type'];
        $this->account_id = $data['account_id'];
        $this->category_id = $data['category_id'];
        $this->amount = $data['amount'];
        $this->description = $data['description'] ?? null;
    }


}
