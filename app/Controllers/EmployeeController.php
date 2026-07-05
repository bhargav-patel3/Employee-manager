<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class EmployeeController extends BaseController
{
    protected EmployeeModel $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    /**
     * GET / - show the main page (list is loaded via AJAX)
     */
    public function index()
    {
        return view('employees/index');
    }

    /**
     * GET /employees - list all employees as JSON
     */
    public function list()
    {
        $employees = $this->employeeModel->orderBy('id', 'DESC')->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $employees,
        ]);
    }

    /**
     * POST /employees - create a new employee
     */
    public function create()
    {
        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'role' => trim((string) $this->request->getPost('role')),
        ];

        if (! $this->employeeModel->save($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Please correct the errors below.',
                'errors'  => $this->employeeModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Employee added successfully.',
            'data'    => $this->employeeModel->find($this->employeeModel->getInsertID()),
        ]);
    }

    /**
     * PUT/POST /employees/(:num) - update an employee
     */
    public function update($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (! $employee) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Employee not found.',
            ]);
        }

        $data = [
            'name' => trim((string) $this->request->getVar('name')),
            'role' => trim((string) $this->request->getVar('role')),
        ];

        if (! $this->employeeModel->update($id, $data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Please correct the errors below.',
                'errors'  => $this->employeeModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Employee updated successfully.',
            'data'    => $this->employeeModel->find($id),
        ]);
    }

    /**
     * DELETE /employees/(:num) - delete an employee
     */
    public function delete($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (! $employee) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Employee not found.',
            ]);
        }

        $this->employeeModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Employee deleted successfully.',
        ]);
    }
}
