<?php

namespace App\Libraries;

/**
 * Hardcoded Email Templates
 * 
 * Provides email templates as hardcoded PHP arrays instead of database storage
 * This replaces the EmailTemplateManager for systems without admin interface
 */
class HardcodedEmailTemplates
{
    /**
     * Get all available email templates
     * 
     * @return array All email templates
     */
    public function getAllTemplates()
    {
        return [
            // 2. Lost Item Reporting
            'lost_item_confirmation' => [
                'type' => 'lost_item_confirmation',
                'name' => 'Lost Item Report Confirmation',
                'subject' => 'Lost Item Report Confirmation – {{item_name}}',
                'body' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Lost Item Report Confirmation</title>
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                        <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                    </div>
                    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                        <h2 style="color: #667eea; margin-top: 0;">Lost Item Report Confirmation – {{item_name}}</h2>
                        
                        <p><strong>Hello {{user_name}},</strong></p>
                        
                        <p>Your lost item report has been recorded.</p>
                        
                        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
                            <h3 style="margin-top: 0; color: #333;">Item Details:</h3>
                            <p><strong>Item:</strong> {{item_name}}</p>
                            <p><strong>Category:</strong> {{category}}</p>
                            <p><strong>Date Reported:</strong> {{date_reported}}</p>
                        </div>
                        
                        <p>We will notify you once a potential match is found.</p>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                            <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                        </div>
                    </div>
                </body>
                </html>'
            ],
            
            // 3. Found Item Reporting
            'found_item_confirmation' => [
                'type' => 'found_item_confirmation',
                'name' => 'Found Item Report Confirmation',
                'subject' => 'Found Item Report Confirmation – {{item_name}}',
                'body' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Found Item Report Confirmation</title>
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                        <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                    </div>
                    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                        <h2 style="color: #28a745; margin-top: 0;">Found Item Report Confirmation – {{item_name}}</h2>
                        
                        <p><strong>Hello {{finder_name}},</strong></p>
                        
                        <p>Thank you for reporting a found item.</p>
                        
                        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">
                            <h3 style="margin-top: 0; color: #333;">Item Details:</h3>
                            <p><strong>Item:</strong> {{item_name}}</p>
                            <p><strong>Category:</strong> {{category}}</p>
                            <p><strong>Location:</strong> {{location}}</p>
                        </div>
                        
                        <p>We will notify you once a claim has been made for this item.</p>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                            <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                        </div>
                    </div>
                </body>
                </html>'
            ],
            
            // 4a. Claim Approved
            'claim_approved' => [
                'type' => 'claim_approved',
                'name' => 'Claim Approved Notification',
                'subject' => 'Claim Approved – {{item_name}}',
                'body' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Claim Approved</title>
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                        <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                    </div>
                    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                        <h2 style="color: #28a745; margin-top: 0;">✓ Claim Approved – {{item_name}}</h2>
                        
                        <p><strong>Hello {{claimant_name}},</strong></p>
                        
                        <p style="font-size: 16px;">Congratulations! Your claim request has been <strong style="color: #28a745;">APPROVED</strong>.</p>
                        
                        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">
                            <h3 style="margin-top: 0; color: #333;">📋 Claim Details</h3>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr><td style="padding: 8px 0;"><strong>Item:</strong></td><td style="padding: 8px 0;">{{item_name}}</td></tr>
                                <tr><td style="padding: 8px 0;"><strong>Match ID:</strong></td><td style="padding: 8px 0; font-family: monospace; font-size: 12px;">{{match_id}}</td></tr>
                                <tr><td style="padding: 8px 0;"><strong>Date Approved:</strong></td><td style="padding: 8px 0;">{{date_approved}}</td></tr>
                                <tr><td style="padding: 8px 0;"><strong>Status:</strong></td><td style="padding: 8px 0;"><span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Awaiting Pickup</span></td></tr>
                            </table>
                        </div>
                        
                        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 20px; border-radius: 8px; margin: 20px 0;">
                            <h3 style="margin-top: 0; color: #856404;">📍 Next Steps - Item Release</h3>
                            <p style="margin: 10px 0; font-size: 15px;">Please proceed to the <strong>Supreme Student Council (SSC) Office</strong> to claim your item:</p>
                            <div style="background: white; padding: 15px; border-radius: 6px; margin-top: 15px;">
                                <p style="margin: 5px 0;"><strong>📌 Location:</strong> SSC Office, CSPC Campus</p>
                                <p style="margin: 5px 0;"><strong>🗺️ Coordinates:</strong> 13°24\'23.2"N 123°22\'31.4"E</p>
                                <p style="margin: 5px 0;"><strong>🕐 Office Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM</p>
                            </div>
                            <p style="margin-top: 15px; font-size: 14px;"><strong>⚠️ Important:</strong> Please bring a valid ID and proof of ownership when claiming your item.</p>
                        </div>
                        
                        <p style="color: #666; font-size: 14px;">If you have any questions, please contact the SSC Office or reply to this email.</p>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                            <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                        </div>
                    </div>
                </body>
                </html>'
            ],
            
            // 4b. Claim Rejected
            'claim_rejected' => [
                'type' => 'claim_rejected',
                'name' => 'Claim Rejected Notification',
                'subject' => 'Claim Rejected – {{item_name}}',
                'body' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Claim Rejected</title>
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                    <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                        <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                    </div>
                    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                        <h2 style="color: #dc3545; margin-top: 0;">Claim Rejected – {{item_name}}</h2>
                        
                        <p><strong>Hello {{claimant_name}},</strong></p>
                        
                        <p>We regret to inform you that your claim for the item has been <strong style="color: #dc3545;">REJECTED</strong>.</p>
                        
                        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545;">
                            <h3 style="margin-top: 0; color: #333;">Claim Details:</h3>
                            <p><strong>Item:</strong> {{item_name}}</p>
                            <p><strong>Reason:</strong> Another claimant has been approved.</p>
                        </div>
                        
                        <p>You may still report or claim other items in the portal.</p>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                            <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                        </div>
                    </div>
                </body>
                </html>'
            ],
             
             // 4c. Finder Notification (Approved Claim)
             'finder_notification_approved' => [
                 'type' => 'finder_notification_approved',
                 'name' => 'Finder Notification - Approved Claim',
                 'subject' => 'Your Reported Item Has Been Claimed – {{item_name}}',
                 'body' => '
                 <!DOCTYPE html>
                 <html>
                 <head>
                     <meta charset="UTF-8">
                     <meta name="viewport" content="width=device-width, initial-scale=1.0">
                     <title>Your Reported Item Has Been Claimed</title>
                 </head>
                 <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                     <div style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                         <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                     </div>
                     <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                         <h2 style="color: #17a2b8; margin-top: 0;">Your Reported Item Has Been Claimed – {{item_name}}</h2>
                         
                         <p><strong>Hello {{finder_name}},</strong></p>
                         
                         <p>The item you reported as found has been successfully claimed.</p>
                         
                         <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #17a2b8;">
                             <h3 style="margin-top: 0; color: #333;">Item Details:</h3>
                             <p><strong>Item:</strong> {{item_name}}</p>
                             <p><strong>Date Claimed:</strong> {{date_claimed}}</p>
                         </div>
                         
                         <p>Thank you for your honesty and contribution.</p>
                         
                         <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                             <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                         </div>
                     </div>
                 </body>
                 </html>'
             ],
             
             // 4d. Claim Resolved (Item Returned)
             'claim_resolved' => [
                 'type' => 'claim_resolved',
                 'name' => 'Claim Resolved - Item Returned',
                 'subject' => 'Claim Resolved – {{item_name}}',
                 'body' => '
                 <!DOCTYPE html>
                 <html>
                 <head>
                     <meta charset="UTF-8">
                     <meta name="viewport" content="width=device-width, initial-scale=1.0">
                     <title>Claim Resolved</title>
                 </head>
                 <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                     <div style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                         <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                     </div>
                     <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                         <h2 style="color: #6f42c1; margin-top: 0;">Claim Resolved – {{item_name}}</h2>
                         
                         <p><strong>Hello {{claimant_name}},</strong></p>
                         
                         <p>Your claim for the item has been marked as <strong style="color: #6f42c1;">RESOLVED</strong>.</p>
                         
                         <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #6f42c1;">
                             <h3 style="margin-top: 0; color: #333;">Resolution Details:</h3>
                             <p><strong>Item:</strong> {{item_name}}</p>
                             <p><strong>Status:</strong> Item officially returned</p>
                         </div>
                         
                         <p>Thank you for using the Lost & Found Management System.</p>
                         
                         <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                             <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                         </div>
                     </div>
                 </body>
                 </html>'
             ],
             
             // 4e. Finder Notification (Resolved)
             'finder_notification_resolved' => [
                 'type' => 'finder_notification_resolved',
                 'name' => 'Finder Notification - Item Returned',
                 'subject' => 'Item Returned – {{item_name}}',
                 'body' => '
                 <!DOCTYPE html>
                 <html>
                 <head>
                     <meta charset="UTF-8">
                     <meta name="viewport" content="width=device-width, initial-scale=1.0">
                     <title>Item Returned</title>
                 </head>
                 <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                     <div style="background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                         <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                     </div>
                     <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                         <h2 style="color: #fd7e14; margin-top: 0;">Item Returned – {{item_name}}</h2>
                         
                         <p><strong>Hello {{finder_name}},</strong></p>
                         
                         <p>The item you reported as found has been successfully returned to its rightful owner.</p>
                         
                         <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #fd7e14;">
                             <h3 style="margin-top: 0; color: #333;">Return Details:</h3>
                             <p><strong>Item:</strong> {{item_name}}</p>
                             <p><strong>Date Returned:</strong> {{date_returned}}</p>
                         </div>
                         
                         <p>We appreciate your cooperation and honesty.</p>
                         
                         <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                             <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                         </div>
                     </div>
                 </body>
                 </html>'
             ],
             
             // 5. Match Management
             'match_notification_old' => [
                 'type' => 'match_notification_old',
                 'name' => 'Match Found Notification (Old)',
                 'subject' => 'Possible Match Found – {{lost_item_name}}',
                 'body' => '
                 <!DOCTYPE html>
                 <html>
                 <head>
                     <meta charset="UTF-8">
                     <meta name="viewport" content="width=device-width, initial-scale=1.0">
                     <title>Possible Match Found</title>
                 </head>
                 <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                     <div style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                         <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Lost & Found Management System</h1>
                     </div>
                     <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                         <h2 style="color: #ffc107; margin-top: 0;">Possible Match Found – {{lost_item_name}}</h2>
                         
                         <p><strong>Hello {{user_name}},</strong></p>
                         
                         <p>A potential match has been detected between your lost item and a reported found item.</p>
                         
                         <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;">
                             <h3 style="margin-top: 0; color: #333;">Match Details:</h3>
                             <p><strong>Lost Item:</strong> {{lost_item_name}}</p>
                             <p><strong>Found Item:</strong> {{found_item_name}}</p>
                             <p><strong>Match Score:</strong> {{match_score}}%</p>
                         </div>
                         
                         <p>Please log in to your account to review the match and proceed with claiming if applicable.</p>
                         
                         <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                             <p style="margin: 0; color: #666;"><strong>CSPC Lost & Found Management System</strong></p>
                         </div>
                     </div>
                 </body>
                 </html>'
             ],
             
             'role_creation' => [
                'type' => 'role_creation',
                'name' => 'Role Creation Notification',
                'subject' => 'Welcome to CSPC Lost and Found - Your Account Details',
                'body' => '<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Welcome to CSPC Lost and Found</title><style>body{margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;background-color:#f5f5f5;line-height:1.6}.email-container{max-width:600px;margin:0 auto;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1)}.header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:40px 30px;text-align:center;color:white}.header h1{margin:0;font-size:28px;font-weight:600;text-shadow:0 2px 4px rgba(0,0,0,0.2)}.header p{margin:10px 0 0 0;font-size:16px;opacity:0.9;font-weight:300}.content{padding:40px 30px}.welcome-text{font-size:18px;color:#333;margin-bottom:30px;font-weight:500}.description{color:#666;margin-bottom:30px;font-size:16px}.credentials-section{background-color:#f8f9fa;border-radius:8px;padding:25px;margin:30px 0;border-left:4px solid #667eea}.credentials-title{font-size:16px;font-weight:600;color:#333;margin-bottom:20px}.credential-item{margin-bottom:12px;font-size:15px}.credential-label{font-weight:600;color:#555;display:inline-block;width:80px}.credential-value{color:#333;font-family:"Courier New",monospace;background-color:#fff;padding:2px 6px;border-radius:4px;border:1px solid #e0e0e0}.security-notice{background-color:#fff3cd;border:1px solid #ffeaa7;border-radius:8px;padding:20px;margin:25px 0}.security-notice h3{margin:0 0 10px 0;color:#856404;font-size:16px}.security-notice p{margin:0;color:#856404;font-size:14px}.action-button{display:inline-block;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;text-decoration:none;padding:15px 30px;border-radius:8px;font-weight:600;font-size:16px;text-align:center;margin:20px 0;box-shadow:0 4px 15px rgba(102,126,234,0.3)}.footer{text-align:center;padding:30px;color:#666;font-size:14px;border-top:1px solid #eee}.footer p{margin:5px 0}</style></head><body><div class="email-container"><div class="header"><h1>🏠 Welcome to CSPC Lost and Found</h1><p>Your gateway to excellence</p></div><div class="content"><div class="welcome-text">Welcome, {user_name}! 🎉</div><div class="description">Your account has been created successfully. We are excited to have you join our community.</div><div class="credentials-section"><div class="credentials-title">🔐 Your Account Credentials</div><div class="credential-item"><span class="credential-label">Email:</span> <span class="credential-value">{user_email}</span></div><div class="credential-item"><span class="credential-label">Password:</span> <span class="credential-value">{user_password}</span></div><div class="credential-item"><span class="credential-label">Role:</span> <span class="credential-value">{user_role}</span></div></div><div class="security-notice"><h3>⚠️ Security Important</h3><p>For your security, please change your password immediately after your first login. Never share your credentials with anyone.</p></div><div style="text-align:center"><p>Ready to get started? Click below to access your account, or contact our support team if you need any assistance.</p><a href="{login_url}" class="action-button">Access My Account</a></div></div><div class="footer"><p><strong>CSPC Lost and Found System</strong></p><p>Camarines Sur Polytechnic Colleges</p><p>This is an automated message. Please do not reply to this email.</p></div></div></body></html>'
            ],
            
            'match_notification' => [
                'type' => 'match_notification',
                'name' => 'Match Found Notification',
                'subject' => 'Potential Match Found for Your Item - {item_name}',
                'body' => '<html><body><h2>Potential Match Found!</h2><p>Dear {user_name},</p><p>Great news! We found a potential match for your {item_type} item:</p><h3>Your {item_type} Item:</h3><ul><li><strong>Item Name:</strong> {your_item_name}</li><li><strong>Description:</strong> {your_item_description}</li><li><strong>Report ID:</strong> {your_item_id}</li></ul><h3>Potential Match:</h3><ul><li><strong>Item Name:</strong> {match_item_name}</li><li><strong>Description:</strong> {match_item_description}</li><li><strong>Match Score:</strong> {match_score}%</li><li><strong>Date {match_action}:</strong> {match_date}</li></ul><p>To proceed with claiming this item, please log into the Lost and Found system and submit a claim with verification details.</p><p><a href="{claim_url}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Submit Claim</a></p><p>Best regards,<br>CSPC Lost and Found System</p></body></html>'
            ],
            
            // Support Ticket Reply
            'support_ticket_reply' => [
                'type' => 'support_ticket_reply',
                'name' => 'Support Ticket Reply',
                'subject' => 'Reply to Your Support Ticket - {{ticket_subject}}',
                'body' => '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Support Ticket Reply</title>
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                    <div style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                        <h1 style="color: white; margin: 0; font-size: 24px;">CSPC Help & Support</h1>
                    </div>
                    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd;">
                        <h2 style="color: #007bff; margin-top: 0;">Reply to Your Support Ticket</h2>
                        
                        <p><strong>Hello {{user_name}},</strong></p>
                        
                        <p>We have replied to your support ticket. Here are the details:</p>
                        
                        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #007bff;">
                            <h3 style="margin-top: 0; color: #333;">Ticket Information:</h3>
                            <p><strong>Ticket ID:</strong> #{{ticket_id}}</p>
                            <p><strong>Subject:</strong> {{ticket_subject}}</p>
                            <p><strong>Status:</strong> {{ticket_status}}</p>
                            <p><strong>Priority:</strong> {{ticket_priority}}</p>
                        </div>
                        
                        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">
                            <h3 style="margin-top: 0; color: #333;">Our Reply:</h3>
                            <p><strong>From:</strong> {{responder_name}} ({{responder_email}})</p>
                            <p><strong>Date:</strong> {{reply_date}}</p>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 15px;">
                                {{reply_message}}
                            </div>
                        </div>
                        
                        <p>If you need further assistance, please reply to this ticket or contact our support team.</p>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ticket_url}}" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">View Ticket</a>
                        </div>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                            <p style="margin: 0; color: #666;"><strong>CSPC Help & Support Team</strong></p>
                            <p style="margin: 5px 0 0 0; color: #999; font-size: 14px;">This is an automated message. Please do not reply directly to this email.</p>
                        </div>
                    </div>
                </body>
                </html>'
            ]
        ];
    }
    
    /**
     * Get email template by type
     * 
     * @param string $type Template type
     * @return array|null Template data or null if not found
     */
    public function getTemplate($type)
    {
        // Map new type names to existing template types for compatibility
        $typeMapping = [
            'item_reported' => 'lost_item_confirmation',
            'match_found' => 'match_notification'
        ];
        
        $templateType = $typeMapping[$type] ?? $type;
        $templates = $this->getAllTemplates();
        
        return $templates[$templateType] ?? null;
    }
    
    /**
     * Render template with placeholder replacement
     * 
     * @param string $type Template type
     * @param array $data Data for placeholder replacement
     * @return array|null Rendered template with subject and body
     */
    public function renderTemplate($type, $data = [])
    {
        $template = $this->getTemplate($type);
        
        if (!$template) {
            return null;
        }
        
        $subject = $this->replacePlaceholders($template['subject'], $data);
        $body = $this->replacePlaceholders($template['body'], $data);
        
        return [
            'subject' => $subject,
            'body' => $body
        ];
    }
    
    /**
     * Replace placeholders in text with actual data
     * 
     * @param string $text Text with placeholders
     * @param array $data Data for replacement
     * @return string Text with placeholders replaced
     */
    public function replacePlaceholders($text, $data = [])
    {
        if (empty($data)) {
            return $text;
        }
        
        foreach ($data as $key => $value) {
            // Handle both {key} and {{key}} placeholder formats
            // Replace {{key}} first to avoid conflicts
            $placeholder2 = '{{' . $key . '}}';
            $placeholder1 = '{' . $key . '}';
            
            $text = str_replace($placeholder2, $value, $text);
            $text = str_replace($placeholder1, $value, $text);
        }
        
        return $text;
    }
    
    /**
     * Get available template types
     * 
     * @return array Available template types with descriptions
     */
    public function getTemplateTypes()
    {
        return [
            'lost_item_confirmation' => 'Lost Item Report Confirmation',
            'found_item_confirmation' => 'Found Item Report Confirmation',
            'claim_approved' => 'Claim Approved Notification',
            'claim_rejected' => 'Claim Rejected Notification',
            'match_notification' => 'Item Match Found Notification'
        ];
    }
}