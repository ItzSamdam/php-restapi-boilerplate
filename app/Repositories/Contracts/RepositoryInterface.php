<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all(array $columns = ['*']);
    public function find($id, array $columns = ['*']);
    public function findBy(string $field, $value, array $columns = ['*']);
    public function findWhere(array $conditions, array $columns = ['*']);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate($perPage = 15, array $columns = ['*']);
    public function with($relations);
    public function count();
    public function exists($id);
}
