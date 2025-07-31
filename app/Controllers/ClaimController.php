<?php

namespace App\Controllers;

use App\Models\ClaimModel;
use App\Models\ItemModel;

class ClaimController extends BaseController
{
    protected $claimModel;
    protected $itemModel;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->claimModel = new ClaimModel();
        $this->itemModel = new ItemModel();
    }

    public function index()
    {
        $claims = $this->claimModel
            ->select('claims.*, items.name as item_name, items.category as item_category, items.image as item_image')
            ->join('items', 'items.id = claims.item_id', 'left')
            ->orderBy('claims.created_at', 'DESC')
            ->findAll();

        return view('claims/index', ['claims' => $claims]);
    }

    public function create()
    {
        $items = $this->itemModel
            ->where('status', 'pending') // Items that are available to be claimed
            ->findAll();

        if (empty($items)) {
            session()->setFlashdata('warning', 'No items are available for claiming right now.');
            return redirect()->to('/items');
        }

        return view('claims/create', ['items' => $items]);
    }

    public function store()
    {
        $rules = [
            'item_id' => 'required|is_natural_no_zero',
            'claimant_name' => 'required|min_length[3]',
            'claimant_contact' => 'required|numeric|min_length[10]',
            'claimant_email' => 'required|valid_email',
            'claim_description' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            $items = $this->itemModel->where('condition', 'found')->findAll();
            return view('claims/create', [
                'validation' => $this->validator,
                'items' => $items
            ]);
        }

        $this->claimModel->save([
            'item_id' => $this->request->getPost('item_id'),
            'claimant_name' => $this->request->getPost('claimant_name'),
            'claimant_contact' => $this->request->getPost('claimant_contact'),
            'claimant_email' => $this->request->getPost('claimant_email'),
            'claim_description' => $this->request->getPost('claim_description'),
            'status' => 'pending'
        ]);

        session()->setFlashdata('success', 'Claim submitted successfully.');
        return redirect()->to('/claims');
    }

    public function edit($id = null)
    {
        $claim = $this->getClaimWithItemDetails($id);
        return view('claims/edit', ['claim' => $claim]);
    }

    public function update($id = null)
    {
        $rules = [
            'status' => 'required|in_list[pending,verified,rejected]',
        ];

        if (!$this->validate($rules)) {
            return view('claims/edit', [
                'validation' => $this->validator,
                'claim' => $this->getClaimWithItemDetails($id)
            ]);
        }

        $status = $this->request->getPost('status');

        $this->claimModel->update($id, [
            'status' => $status
        ]);

        // Also update the item status if the claim is verified
        if ($status === 'verified') {
            $claim = $this->claimModel->find($id);
            $this->itemModel->update($claim['item_id'], ['status' => 'claimed']);
        }

        session()->setFlashdata('success', 'Claim updated successfully.');
        return redirect()->to('/claims');
    }

    public function delete($id = null)
    {
        if ($this->claimModel->find($id)) {
            $this->claimModel->delete($id);
            session()->setFlashdata('success', 'Claim deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Claim not found.');
        }
        return redirect()->to('/claims');
    }

    public function view($id = null)
    {
        $claim = $this->getClaimWithItemDetails($id);
        return view('claims/view', ['claim' => $claim]);
    }

    private function getClaimWithItemDetails($claimId)
    {
        $claim = $this->claimModel
            ->select('claims.*, items.name as item_name, items.description as item_description, items.category as item_category, items.image as item_image, items.found_date as item_found_date')
            ->join('items', 'items.id = claims.item_id', 'left')
            ->where('claims.id', $claimId)
            ->first();

        if (!$claim) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Claim not found');
        }
        return $claim;
    }
}
