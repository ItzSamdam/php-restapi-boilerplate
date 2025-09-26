<?php

namespace App\Controllers;

use App\Services\TransactionService;
use App\Models\Transaction;
use App\Core\Request;

class TransactionController extends ApiController
{
    private $transactionService;

    public function __construct()
    {
        parent::__construct();
        $this->transactionService = new TransactionService();
    }

    public function create(Request $request)
    {
        try {
            $data = $request->getBody();

            $requiredFields = ['user_id', 'type', 'amount'];
            $errors = $this->validateRequiredFields($data, $requiredFields);

            if (!empty($errors)) {
                return $this->error('Validation failed', 422, $errors);
            }

            $transaction = new Transaction($data);
            $this->transactionService->processTransaction($transaction);

            return $this->success(null, 'Transaction processed successfully', 201);
        } catch (\Exception $e) {
            $errors = json_decode($e->getMessage(), true) ?: $e->getMessage();
            return $this->error('Transaction failed', 400, $errors);
        }
    }

    public function getUserTransactions(Request $request, $userId)
    {
        try {
            $filters = $request->getBody();
            $transactions = $this->transactionService->getUserTransactions($userId, $filters);

            $transactionData = array_map(function ($transaction) {
                return $transaction->toArray();
            }, $transactions);

            return $this->success($transactionData, 'Transactions retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve transactions', 500);
        }
    }

    public function getStats(Request $request, $userId)
    {
        try {
            $data = $request->getBody();
            $date = $data['date'] ?? date('Y-m-d');

            $stats = $this->transactionService->getDailyStats($userId, $date);
            return $this->success($stats, 'Stats retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve stats', 500);
        }
    }
}
