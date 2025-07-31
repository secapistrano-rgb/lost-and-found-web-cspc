<?php namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ClaimModel;

class ItemController extends BaseController
{
    protected $itemModel;
    protected $claimModel;
    protected $uploadPath;
    protected $categories;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->itemModel = new ItemModel();
        $this->claimModel = new ClaimModel();
        $this->uploadPath = FCPATH . 'uploads/';
        $this->categories = [
            'Electronics', 'Documents', 'Clothing', 'Accessories', 'Books', 'Others'
        ];
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $statusFilter = $this->request->getGet('status');
        $conditionFilter = $this->request->getGet('condition');

        $builder = new ItemModel();

        if ($search) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('description', $search)
                    ->groupEnd();
        }

        if ($category) {
            $builder->where('category', $category);
        }

        if ($statusFilter) {
            $builder->where('status', $statusFilter);
        }

        if ($conditionFilter) {
            $builder->where('condition', $conditionFilter);
        }

        $allItems = $builder->orderBy('created_at', 'desc')->findAll();
        
        // Add claim information to each item
        foreach ($allItems as &$item) {
            // Get claim count
            $item['claim_count'] = $this->claimModel->where('item_id', $item['id'])->countAllResults();
            
            // Get verified claim if exists
            $verifiedClaim = $this->claimModel
                ->where('item_id', $item['id'])
                ->where('status', 'verified')
                ->first();
            
            if ($verifiedClaim) {
                $item['verified_claim'] = $verifiedClaim;
            }
        }

        return view('dashboard/items', [
            'allItems' => $allItems,
            'categories' => $this->categories,
            'search' => $search,
            'selectedCategory' => $category,
            'selectedStatus' => $statusFilter,
            'selectedCondition' => $conditionFilter,
        ]);
    }

    public function create()
    {
        return view('items/create', ['categories' => $this->categories]);
    }

    public function edit($id = null)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }
        
        return view('items/edit', [
            'item' => $item,
            'categories' => $this->categories
        ]);
    }

    public function store()
    {
        $validationRules = [
            'name' => 'required|min_length[3]',
            'description' => 'required|min_length[5]',
            'category' => 'required|min_length[2]',
            'status' => 'required|in_list[pending,claimed]',
            'condition' => 'required|in_list[found,lost]',
            'found_date' => 'required|valid_date[Y-m-d]',
            'email' => 'permit_empty|valid_email',
            'image' => 'uploaded[image]|is_image[image]|max_size[image,2048]'
        ];

        if (!$this->validate($validationRules)) {
            return view('items/create', [
                'validation' => $this->validator,
                'categories' => $this->categories
            ]);
        }

        $imageFile = $this->request->getFile('image');
        $imageName = null;
        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move($this->uploadPath, $imageName);
        }

        $this->itemModel->save([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'status' => $this->request->getPost('status'),
            'condition' => $this->request->getPost('condition'),
            'image' => $imageName,
            'email' => $this->request->getPost('email'),
            'date' => $this->request->getPost('found_date')
        ]);

        session()->setFlashdata('success', 'Item created successfully.');
        return redirect()->to('/items');
    }

    public function update($id = null)
    {
        $validationRules = [
            'name'        => 'required|min_length[3]',
            'description' => 'required|min_length[5]',
            'category'    => 'required|min_length[2]',
            'status'      => 'required|in_list[pending,claimed]',
            'condition'   => 'required|in_list[found,lost]',
            'found_date'  => 'required|valid_date[Y-m-d]',
            'email'       => 'permit_empty|valid_email',
            'image'       => 'permit_empty|is_image[image]|max_size[image,2048]'
        ];

        if (!$this->validate($validationRules)) {
            $item = $this->itemModel->find($id);
            return view('items/edit', [
                'validation' => $this->validator, 
                'item' => $item,
                'categories' => $this->categories
            ]);
        }

        $item = $this->itemModel->find($id);
        $imageFile = $this->request->getFile('image');
        $imageName = $item['image'];

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            if ($imageName && file_exists($this->uploadPath . $imageName)) {
                unlink($this->uploadPath . $imageName);
            }
            $imageName = $imageFile->getRandomName();
            $imageFile->move($this->uploadPath, $imageName);
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category'    => $this->request->getPost('category'),
            'status'      => $this->request->getPost('status'),
            'condition'   => $this->request->getPost('condition'),
            'date'        => $this->request->getPost('found_date'),
            'email'       => $this->request->getPost('email'),
            'image'       => $imageName,
        ];

        $this->itemModel->update($id, $data);

        session()->setFlashdata('success', 'Item updated successfully.');
        return redirect()->to('/items/view/' . $id);
    }

    public function delete($id = null)
    {
        $item = $this->itemModel->find($id);
        if ($item) {
            if ($item['image'] && file_exists($this->uploadPath . $item['image'])) {
                unlink($this->uploadPath . $item['image']);
            }
            $this->itemModel->delete($id);
            session()->setFlashdata('success', 'Item deleted successfully.');
        }
        return redirect()->to('/items');
    }

    public function view($id = null)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }
        
        // Get all claims for this item
        $claims = $this->claimModel
            ->where('item_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        return view('items/view', [
            'item' => $item,
            'claims' => $claims
        ]);
    }
}
