<?php

namespace App\Controllers;

use App\Models\AdminRoleModel;

class AdminRole extends BaseController
{
    public function index()
    {
        $model = new AdminRoleModel();

        // Automatically add/update logged-in user to roles
        $session_email = session()->get('email');
        if ($session_email) {
            $user_role = $model->where('email', $session_email)->first();
            $session_pic = session()->get('profile_pic');

            if (!$user_role) {
                // User does not exist, create them
                $model->save([
                    'name'        => session()->get('name'),
                    'email'       => $session_email,
                    'role'        => 'Admin', // Default role
                    'profile_pic' => $session_pic
                ]);
            } elseif ($user_role['profile_pic'] !== $session_pic) {
                // User exists, but profile picture has changed, update it
                $model->update($user_role['id'], ['profile_pic' => $session_pic]);
            }
        }

        $q = $this->request->getGet('q');
        $data['q'] = $q;

        if ($q) {
            $model->groupStart()
                  ->like('name', $q)
                  ->orLike('email', $q)
                  ->orLike('role', $q)
                  ->groupEnd();
        }

        $data['roles'] = $model->findAll();
        return view('admin_role/index', $data);
    }



    public function edit($id)
    {
        $model = new AdminRoleModel();
        $data['role'] = $model->find($id);
        return view('admin_role/edit', $data);
    }

    public function update($id)
    {
        $model = new AdminRoleModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ];
        $model->update($id, $data);
        return redirect()->to('/admin_role');
    }

    public function delete($id)
    {
        $model = new AdminRoleModel();
        $role_to_delete = $model->find($id);
        $session_email = session()->get('email');

        if ($role_to_delete && strtolower(trim($role_to_delete['email'])) === strtolower(trim($session_email))) {
            return redirect()->to('/admin_role')->with('error', 'You cannot delete your own role.');
        }

        if ($model->delete($id)) {
            return redirect()->to('/admin_role')->with('success', 'Role deleted successfully.');
        } else {
            return redirect()->to('/admin_role')->with('error', 'Failed to delete role.');
        }
    }

    public function view($id)
    {
        $model = new AdminRoleModel();
        $data['role'] = $model->find($id);
        return view('admin_role/view', $data);
    }
}
