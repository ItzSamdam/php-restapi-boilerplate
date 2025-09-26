<?php

namespace App\Models;

class Transaction
{
    public $id;
    public $user_id;
    public $type; // 'credit', 'debit', 'transfer'
    public $amount;
    public $description;
    public $recipient_id;
    public $status; // 'pending', 'completed', 'failed'
    public $created_at;

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    const TYPE_TRANSFER = 'transfer';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'recipient_id' => $this->recipient_id,
            'status' => $this->status,
            'created_at' => $this->created_at
        ];
    }

    public function validate()
    {
        $errors = [];

        if (!in_array($this->type, [self::TYPE_CREDIT, self::TYPE_DEBIT, self::TYPE_TRANSFER])) {
            $errors['type'] = 'Invalid transaction type';
        }

        if ($this->amount <= 0) {
            $errors['amount'] = 'Amount must be greater than 0';
        }

        if ($this->type === self::TYPE_TRANSFER && empty($this->recipient_id)) {
            $errors['recipient_id'] = 'Recipient is required for transfers';
        }

        return $errors;
    }
}
