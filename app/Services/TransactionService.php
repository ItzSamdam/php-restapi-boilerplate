<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\TransactionRepository;
use App\Models\Transaction;

class TransactionService
{
    private $userRepository;
    private $transactionRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->transactionRepository = new TransactionRepository();
    }

    public function processTransaction(Transaction $transaction)
    {
        $validationErrors = $transaction->validate();
        if (!empty($validationErrors)) {
            throw new \Exception(json_encode($validationErrors));
        }

        $user = $this->userRepository->find($transaction->user_id);
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Start transaction
        $this->transactionRepository->db->beginTransaction();

        try {
            switch ($transaction->type) {
                case Transaction::TYPE_DEBIT:
                    if ($user['balance'] < $transaction->amount) {
                        throw new \Exception('Insufficient funds');
                    }
                    $this->userRepository->updateBalance($transaction->user_id, -$transaction->amount);
                    break;

                case Transaction::TYPE_CREDIT:
                    $this->userRepository->updateBalance($transaction->user_id, $transaction->amount);
                    break;

                case Transaction::TYPE_TRANSFER:
                    $recipient = $this->userRepository->find($transaction->recipient_id);
                    if (!$recipient) {
                        throw new \Exception('Recipient not found');
                    }
                    if ($user['balance'] < $transaction->amount) {
                        throw new \Exception('Insufficient funds for transfer');
                    }

                    $this->userRepository->updateBalance($transaction->user_id, -$transaction->amount);
                    $this->userRepository->updateBalance($transaction->recipient_id, $transaction->amount);
                    break;
            }

            // Create transaction record
            $transactionData = $transaction->toArray();
            $transactionData['status'] = Transaction::STATUS_COMPLETED;
            unset($transactionData['id']);

            $this->transactionRepository->create($transactionData);

            $this->transactionRepository->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->transactionRepository->db->rollBack();
            throw $e;
        }
    }

    public function getUserTransactions($userId, $filters = [])
    {
        return $this->transactionRepository->getUserTransactions($userId, $filters);
    }

    public function getDailyStats($userId, $date)
    {
        $dailyTotal = $this->transactionRepository->getDailyTotal($userId, $date);
        $transactions = $this->transactionRepository->findWhere([
            'user_id' => $userId,
            'status' => Transaction::STATUS_COMPLETED
        ]);

        return [
            'daily_total' => $dailyTotal,
            'transaction_count' => count($transactions),
            'average_amount' => count($transactions) > 0 ? $dailyTotal / count($transactions) : 0
        ];
    }
}
