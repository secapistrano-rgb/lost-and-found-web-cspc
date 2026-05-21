<?php

namespace App\Models;

use CodeIgniter\Model;

class ClaimModel extends Model
{
    protected $table = 'claims';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'item_id',
        'match_id', 
        'claimant_id',
        'claim_type',
        'status',
        'proof_of_ownership',
        'image',
        'google_user_id'
    ];

    protected $validationRules = [
        'match_id' => 'required',
        'claimant_id' => 'required|valid_email',
        'claim_type' => 'required',
        'proof_of_ownership' => 'required'
    ];

    protected $validationMessages = [
        'match_id' => [
            'required' => 'Match ID is required.'
        ],
        'claimant_id' => [
            'required' => 'Claimant email is required.',
            'valid_email' => 'Please provide a valid email address.'
        ],
        'claim_type' => [
            'required' => 'Claim type is required.'
        ],
        'proof_of_ownership' => [
            'required' => 'Proof of ownership is required.'
        ]
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
    
    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    // Get claims by match ID
    public function getClaimsByMatch($matchId)
    {
        return $this->where('match_id', $matchId)->findAll();
    }

    // Get claims by item ID (legacy support)
    public function getClaimsByItem($itemId)
    {
        return $this->where('item_id', $itemId)->findAll();
    }

    // Get claims by status
    public function getClaimsByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    // Get pending claims count
    public function getPendingClaimsCount()
    {
        return $this->where('status', 'pending')->countAllResults();
    }

    // Update claim status
    public function updateClaimStatus($claimId, $status)
    {
        return $this->update($claimId, ['status' => $status]);
    }

    // Search claims
    public function searchClaims($searchTerm)
    {
        return $this->like('claimant_id', $searchTerm)
                   ->orLike('proof_of_ownership', $searchTerm)
                   ->findAll();
    }

    // Get claims with match details
    public function getClaimsWithMatches()
    {
        return $this->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, matches.lost_item_title, matches.found_item_title, 
                             matches.score, matches.status as match_status')
                   ->join('matches', 'matches.id = claims.match_id', 'left')
                   ->orderBy('claims.created_at', 'DESC')
                   ->findAll();
    }

    // Get claim statistics
    public function getClaimStats()
    {
        return [
            'total' => $this->countAll(),
            'pending' => $this->where('status', 'pending')->countAllResults(false),
            'approved' => $this->where('status', 'approved')->countAllResults(false),
            'rejected' => $this->where('status', 'rejected')->countAllResults(false),
            'resolved' => $this->where('status', 'resolved')->countAllResults(false)
        ];
    }
}
