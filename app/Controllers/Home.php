<?php namespace App\Controllers;

use App\Models\ItemModel;

class Home extends BaseController
{
    public function index()
{
    // Load your custom dashboard view with dynamic data
    $itemModel = new \App\Models\ItemModel();

    $data = [
        'pendingCount' => $itemModel->where('status', 'pending')->countAllResults(),
        'claimedCount' => $itemModel->where('status', 'claimed')->countAllResults(),
    ];

    return view('dashboard/home', $data);
}

}
