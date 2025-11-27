<?php

namespace App\Dto;



/**
 * @OA\Schema(
 *     schema="TransactionDto",
 *     type="object",
 *     required={"id", "account_id", "type", "amount", "description", "category_id"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="account_id", type="integer"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="amount", type="integer"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="category_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true)
 * )
 */
class TransactionDto
{
    public int $id;
    public int $account_id;
    public string $type;
    public int $amount;
    public ?string $created_at;
    public string $description;
    public int $category_id;
    public function __construct(array $transaction)
    {
        $this->id = $transaction['id'];
        $this->account_id = $transaction['account_id'];
        $this->type = $transaction['type'];
        $this->amount = $transaction['amount'];
        $this->created_at = $transaction['created_at'] ?? null;
        $this->description = $transaction['description'];
        $this->category_id = $transaction['category_id'];


    }
}
