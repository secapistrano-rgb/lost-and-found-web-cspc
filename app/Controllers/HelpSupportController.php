<?php

namespace App\Controllers;

use App\Models\SupportTicketModel;
use App\Models\SupportResponseModel;
use App\Models\UserModel;
use App\Libraries\EmailNotificationService;
use App\Helpers\ActivityLogger;
use CodeIgniter\Controller;

class HelpSupportController extends Controller
{
    protected $supportTicketModel;
    protected $supportResponseModel;
    protected $userModel;
    protected $notificationService;
    protected $activityLogger;

    public function __construct()
    {
        $this->supportTicketModel = new SupportTicketModel();
        $this->supportResponseModel = new SupportResponseModel();
        $this->userModel = new UserModel();
        $this->notificationService = new EmailNotificationService();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    // Main dashboard
    public function index()
    {
        $ticketStats = $this->supportTicketModel->getTicketStatistics();
        
        $recentTickets = $this->supportTicketModel->getRecentTickets(5);
        
        $data = [
            'title' => 'Help & Support Dashboard',
            'open_tickets' => $ticketStats['open'],
            'in_progress_tickets' => $ticketStats['in_progress'],
            'total_tickets' => $ticketStats['total'],
            'recent_tickets' => $recentTickets
        ];
        
        // Debug: Log recent tickets for debugging
        log_message('debug', 'Recent tickets count: ' . count($recentTickets));
        log_message('debug', 'Recent tickets data: ' . json_encode($recentTickets));

        // Log dashboard view activity
        $this->activityLogger->log(
            'Help & Support',
            'dashboard_view',
            'Viewed Help & Support dashboard',
            ['severity' => 'low']
        );

        return view('help_support/dashboard', $data);
    }

    // ==================== SUPPORT TICKETS MANAGEMENT ====================
    
    public function tickets()
    {
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');
        
        // Build query with filters
        $builder = $this->supportTicketModel;
        
        if ($status) {
            $builder = $builder->where('status', $status);
        }
        
        if ($category) {
            $builder = $builder->where('category', $category);
        }
        
        $tickets = $builder->orderBy('created_at', 'DESC')
                          ->paginate(10, 'tickets');
        
        // Get ticket statistics
        $stats = $this->supportTicketModel->getTicketStatistics();
        
        $data = [
            'title' => 'Support Tickets',
            'tickets' => $tickets,
            'current_status' => $status,
            'current_category' => $category,
            'stats' => $stats,
            'pager' => $this->supportTicketModel->pager
        ];

        return view('help_support/tickets/index', $data);
    }

    public function viewTicket($id)
    {
        $ticket = $this->supportTicketModel->find($id);
        if (!$ticket) {
            session()->setFlashdata('error', 'Ticket not found.');
            return redirect()->to('/help-support/tickets');
        }

        // Get responses for this ticket
        $responses = $this->supportResponseModel->where('ticket_id', $id)
                                               ->orderBy('created_at', 'ASC')
                                               ->findAll();

        $data = [
            'title' => 'Ticket Details',
            'ticket' => $ticket,
            'responses' => $responses,
            'replies' => $responses  // Alias for backward compatibility
        ];

        return view('help_support/tickets/view', $data);
    }

    public function createTicket()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'user_name' => 'permit_empty|string',
                'user_email' => 'required|valid_email',
                'subject' => 'required|min_length[5]',
                'description' => 'required|min_length[20]',
                'category' => 'required|in_list[technical_issue,account_problem,matching_system,item_report_issue,notification_problem,other]',
                'priority' => 'required|in_list[low,medium,high,urgent]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'user_name' => $this->request->getPost('user_name'),
                    'user_email' => $this->request->getPost('user_email'),
                    'subject' => $this->request->getPost('subject'),
                    'description' => $this->request->getPost('description'),
                    'category' => $this->request->getPost('category'),
                    'priority' => $this->request->getPost('priority'),
                    'status' => 'open'
                ];

                // Handle file upload
                $file = $this->request->getFile('attachment');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = WRITEPATH . 'uploads/support_tickets';
                    
                    // Create directory if it doesn't exist
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    if ($file->move($uploadPath, $newName)) {
                        $data['attachment_url'] = 'writable/uploads/support_tickets/' . $newName;
                        $data['attachment_filename'] = $file->getClientName();
                    }
                }

                if ($ticketId = $this->supportTicketModel->insert($data)) {
                    // Log ticket creation activity
                    $this->activityLogger->log(
                        'Help & Support',
                        'ticket_create',
                        'Created support ticket: ' . $data['subject'],
                        [
                            'severity' => 'medium',
                            'entity_type' => 'support_ticket',
                            'entity_id' => $ticketId,
                            'additional_data' => [
                                'category' => $data['category'],
                                'priority' => $data['priority'],
                                'user_email' => $data['user_email']
                            ]
                        ]
                    );
                    
                    session()->setFlashdata('success', 'Support ticket created successfully!');
                    return redirect()->to('/help-support/tickets/view/' . $ticketId);
                } else {
                    session()->setFlashdata('error', 'Failed to create ticket.');
                }
            } else {
                $errors = $this->validator->getErrors();
                $errorMessage = 'Validation failed: ';
                foreach ($errors as $field => $error) {
                    $errorMessage .= $field . ': ' . $error . ' ';
                }
                session()->setFlashdata('error', $errorMessage);
            }
        }

        $data = [
            'title' => 'Create New Ticket'
        ];
        return view('help_support/tickets/create', $data);
    }

    public function replyTicket($id)
    {
        $ticket = $this->supportTicketModel->find($id);
        if (!$ticket) {
            session()->setFlashdata('error', 'Ticket not found.');
            return redirect()->to('/help-support/tickets');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'message' => 'required|min_length[10]',
                'reply_email' => 'required|valid_email'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'ticket_id' => $id,
                    'responder_email' => $this->request->getPost('reply_email') ?? 'cscp.lostnfound@gmail.com',
                    'responder_name' => session()->get('user_name') ?? 'CSCP Support',
                    'responder_type' => session()->get('user_role') === 'super_admin' ? 'super_admin' : 'support_staff',
                    'message' => $this->request->getPost('message'),
                    'is_internal' => $this->request->getPost('is_internal') ? 1 : 0
                ];

                $responseId = $this->supportResponseModel->createResponse($data);
                
                if ($responseId) {
                    log_message('info', 'Support ticket response created successfully with ID: ' . $responseId);
                    
                    // Update ticket last response
                    $this->supportTicketModel->updateLastResponse($id);
                    
                    // Update ticket status if provided
                    $newStatus = $this->request->getPost('status');
                    if ($newStatus && in_array($newStatus, ['open', 'in_progress', 'pending_user', 'resolved', 'closed'])) {
                        $this->supportTicketModel->update($id, ['status' => $newStatus]);
                        $ticket['status'] = $newStatus; // Update local ticket data for email
                    }
                    
                    // Log ticket reply activity
                    $this->activityLogger->log(
                        'Help & Support',
                        'ticket_reply',
                        'Replied to support ticket: ' . $ticket['subject'],
                        [
                            'severity' => 'medium',
                            'entity_type' => 'support_ticket',
                            'entity_id' => $id,
                            'additional_data' => [
                                'is_internal' => $data['is_internal'],
                                'responder_type' => $data['responder_type'],
                                'new_status' => $newStatus ?? $ticket['status']
                            ]
                        ]
                    );
                    


                    // Send email notification to user (only if not internal reply)
                    if (!$data['is_internal']) {
                        try {
                            $emailService = new \App\Libraries\EmailNotificationService();
                            
                            $placeholders = [
                                'user_name' => $ticket['user_name'],
                                'ticket_id' => $ticket['id'],
                                'ticket_subject' => $ticket['subject'],
                                'ticket_status' => ucfirst($ticket['status']),
                                'ticket_priority' => ucfirst($ticket['priority']),
                                'responder_name' => $data['responder_name'],
                                'responder_email' => $data['responder_email'],
                                'reply_date' => date('Y-m-d H:i:s'),
                                'reply_message' => nl2br(htmlspecialchars($data['message'])),
                                'ticket_url' => site_url('/help-support/tickets/view/' . $id)
                            ];
                            
                            $emailSent = $emailService->sendTemplatedEmail(
                                'support_ticket_reply',
                                $ticket['user_email'],
                                $ticket['user_name'],
                                $placeholders,
                                $data['responder_name']
                            );
                            
                            if ($emailSent) {
                                session()->setFlashdata('success', 'Reply sent successfully and user has been notified via email!');
                            } else {
                                session()->setFlashdata('success', 'Reply sent successfully, but email notification failed.');
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'Failed to send email notification for ticket reply: ' . $e->getMessage());
                            session()->setFlashdata('success', 'Reply sent successfully, but email notification failed.');
                        }
                    } else {
                        session()->setFlashdata('success', 'Internal reply sent successfully!');
                    }

                    return redirect()->to('/help-support/tickets/view/' . $id);
                } else {
                    $errors = $this->supportResponseModel->errors();
                    log_message('error', 'Failed to create support ticket response: ' . json_encode($errors));
                    log_message('error', 'Response data: ' . json_encode($data));
                    session()->setFlashdata('error', 'Failed to send reply. ' . (is_array($errors) ? implode(', ', $errors) : $errors));
                }
            } else {
                $errors = $this->validator->getErrors();
                $errorMessage = 'Validation failed: ';
                foreach ($errors as $field => $error) {
                    $errorMessage .= $field . ': ' . $error . ' ';
                }
                session()->setFlashdata('error', $errorMessage);
            }
        }

        $data = [
            'title' => 'Reply to Ticket',
            'ticket' => $ticket
        ];
        return view('help_support/tickets/reply', $data);
    }

    public function updateTicketStatus($id)
    {
        $ticket = $this->supportTicketModel->find($id);
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['open', 'in_progress', 'pending_user', 'resolved', 'closed'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $updateData = ['status' => $status];
        
        if ($status === 'resolved') {
            $updateData['resolved_at'] = date('Y-m-d H:i:s');
            $updateData['resolved_by'] = session()->get('user_email') ?? 'admin';
        }

        if ($this->supportTicketModel->update($id, $updateData)) {
            // Log ticket status update activity
            $this->activityLogger->log(
                'Help & Support',
                'ticket_status_update',
                'Updated ticket status to: ' . $status . ' for ticket: ' . $ticket['subject'],
                [
                    'severity' => 'medium',
                    'entity_type' => 'support_ticket',
                    'entity_id' => $id,
                    'old_values' => ['status' => $ticket['status']],
                    'new_values' => ['status' => $status],
                    'additional_data' => [
                        'resolved_by' => $updateData['resolved_by'] ?? null
                    ]
                ]
            );
            
            return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    public function editTicket($id)
    {
        $ticket = $this->supportTicketModel->find($id);
        if (!$ticket) {
            session()->setFlashdata('error', 'Ticket not found.');
            return redirect()->to('/help-support/tickets');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'user_name' => 'permit_empty|string',
                'user_email' => 'required|valid_email',
                'subject' => 'required|min_length[5]',
                'description' => 'required|min_length[20]',
                'category' => 'required|in_list[technical_issue,account_problem,matching_system,item_report_issue,notification_problem,other]',
                'priority' => 'required|in_list[low,medium,high,urgent]',
                'status' => 'required|in_list[open,in_progress,pending_user,resolved,closed]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'user_name' => $this->request->getPost('user_name'),
                    'user_email' => $this->request->getPost('user_email'),
                    'subject' => $this->request->getPost('subject'),
                    'description' => $this->request->getPost('description'),
                    'category' => $this->request->getPost('category'),
                    'priority' => $this->request->getPost('priority'),
                    'status' => $this->request->getPost('status'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($this->supportTicketModel->update($id, $data)) {
                    // Log ticket update activity
                    $this->activityLogger->log(
                        'Help & Support',
                        'ticket_update',
                        'Updated support ticket: ' . $data['subject'],
                        [
                            'severity' => 'medium',
                            'entity_type' => 'support_ticket',
                            'entity_id' => $id,
                            'old_values' => [
                                'subject' => $ticket['subject'],
                                'category' => $ticket['category'],
                                'priority' => $ticket['priority'],
                                'status' => $ticket['status']
                            ],
                            'new_values' => [
                                'subject' => $data['subject'],
                                'category' => $data['category'],
                                'priority' => $data['priority'],
                                'status' => $data['status']
                            ]
                        ]
                    );
                    
                    session()->setFlashdata('success', 'Ticket updated successfully!');
                    return redirect()->to('/help-support/tickets/view/' . $id);
                } else {
                    session()->setFlashdata('error', 'Failed to update ticket.');
                }
            } else {
                $errors = $this->validator->getErrors();
                $errorMessage = 'Validation failed: ';
                foreach ($errors as $field => $error) {
                    $errorMessage .= $field . ': ' . $error . ' ';
                }
                session()->setFlashdata('error', $errorMessage);
            }
        }

        $data = [
            'title' => 'Edit Ticket',
            'ticket' => $ticket
        ];
        return view('help_support/tickets/edit', $data);
    }

    public function deleteTicket($id)
    {
        $ticket = $this->supportTicketModel->find($id);
        if (!$ticket) {
            session()->setFlashdata('error', 'Ticket not found.');
            return redirect()->to('/help-support/tickets');
        }

        // Delete associated responses first
        $this->supportResponseModel->where('ticket_id', $id)->delete();
        
        // Delete the ticket
        if ($this->supportTicketModel->delete($id)) {
            // Log ticket deletion activity
            $this->activityLogger->log(
                'Help & Support',
                'ticket_delete',
                'Deleted support ticket: ' . $ticket['subject'],
                [
                    'severity' => 'high',
                    'entity_type' => 'support_ticket',
                    'entity_id' => $id,
                    'additional_data' => [
                        'category' => $ticket['category'],
                        'priority' => $ticket['priority'],
                        'status' => $ticket['status'],
                        'user_email' => $ticket['user_email']
                    ]
                ]
            );
            
            session()->setFlashdata('success', 'Ticket deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete ticket.');
        }

        return redirect()->to('/help-support/tickets');
    }

    
    
}