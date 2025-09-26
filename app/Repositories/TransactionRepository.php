<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';

    public function create(array $data)
    {
        $id = parent::create($data);
        return $this->find($id);
    }

    public function getUserTransactions($userId, $filters = [])
    {
        $where = "user_id = :user_id";
        $params = [':user_id' => $userId];

        if (isset($filters['type'])) {
            $where .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }

        if (isset($filters['start_date'])) {
            $where .= " AND created_at >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (isset($filters['end_date'])) {
            $where .= " AND created_at <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $query = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        $transactions = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $transactions[] = new Transaction($row);
        }

        return $transactions;
    }

    public function getDailyTotal($userId, $date)
    {
        $query = "SELECT SUM(amount) as total FROM {$this->table} 
                  WHERE user_id = :user_id AND DATE(created_at) = :date 
                  AND status = :status";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':user_id' => $userId,
            ':date' => $date,
            ':status' => Transaction::STATUS_COMPLETED
        ]);

        return (float)($stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0);
    }
}
