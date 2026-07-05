<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['name', 'role'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[150]',
        'role' => 'required|min_length[2]|max_length[150]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Name is required.',
            'min_length' => 'Name must be at least 2 characters.',
        ],
        'role' => [
            'required'   => 'Role is required.',
            'min_length' => 'Role must be at least 2 characters.',
        ],
    ];

    protected $skipValidation = false;
}
