<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use App\Config\Database;
use PDO;

abstract class BaseRepository implements RepositoryInterface
{
    protected $table;
    protected $primaryKey = 'id';
    protected $db;
    protected $with = [];

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function all(array $columns = ['*'])
    {
        $columns = implode(', ', $columns);
        $query = "SELECT {$columns} FROM {$this->table}";

        if (!empty($this->with)) {
            $query = $this->applyWith($query);
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id, array $columns = ['*'])
    {
        $columns = implode(', ', $columns);
        $query = "SELECT {$columns} FROM {$this->table} WHERE {$this->primaryKey} = :id";

        if (!empty($this->with)) {
            $query = $this->applyWith($query);
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBy(string $field, $value, array $columns = ['*'])
    {
        $columns = implode(', ', $columns);
        $query = "SELECT {$columns} FROM {$this->table} WHERE {$field} = :value";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findWhere(array $conditions, array $columns = ['*'])
    {
        $columns = implode(', ', $columns);
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $field => $value) {
            $whereClauses[] = "{$field} = :{$field}";
            $params[":{$field}"] = $value;
        }

        $where = implode(' AND ', $whereClauses);
        $query = "SELECT {$columns} FROM {$this->table} WHERE {$where}";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $setClauses = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $setClauses[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }

        $set = implode(', ', $setClauses);
        $query = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :id";

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function paginate($perPage = 15, array $columns = ['*'])
    {
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $perPage;
        $columns = implode(', ', $columns);

        // Count total
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table}";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get data
        $query = "SELECT {$columns} FROM {$this->table} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'pagination' => [
                'total' => (int)$total,
                'per_page' => (int)$perPage,
                'current_page' => (int)$page,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        $this->with = $relations;
        return $this;
    }

    protected function applyWith($query)
    {
        // This would be extended in child repositories for specific relationships
        return $query;
    }

    public function count()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function exists($id)
    {
        $query = "SELECT 1 FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
}
