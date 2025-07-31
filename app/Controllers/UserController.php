<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function index()
    {
        $model = new UserModel();
        $q = $this->request->getGet('q');

        if ($q) {
            $model->groupStart();
            $model->like('name', $q);
            $model->orLike('email', $q);
            $model->orLike('department', $q);
            $model->groupEnd();
        }

        $data = [
            'users' => $model->findAll(),
            'q'     => $q,
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $model = new UserModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'department' => $this->request->getPost('department'),
        ];

        $model->save($data);

        return redirect()->to('/users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        return view('users/edit', $data);
    }

    public function view($id)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the user with id: '. $id);
        }

        return view('users/view', $data);
    }

    public function update($id)
    {
        $model = new UserModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'department' => $this->request->getPost('department'),
        ];

        $model->update($id, $data);

        return redirect()->to('/users')->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {
        $model = new UserModel();
        $model->delete($id);

        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }
}
