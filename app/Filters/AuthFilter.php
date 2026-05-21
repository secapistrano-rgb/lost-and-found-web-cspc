<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Immediately check if user is logged in (most critical check first)
        if (!$session->get('logged_in')) {
            // Clear any output buffer to prevent content flash
            if (ob_get_level()) {
                ob_end_clean();
            }
            return redirect()->to('/login');
        }
        
        // Check for server restart and force session cleanup
        $this->checkServerRestart();
        
        // Check session timeout (additional security layer)
        $lastActivity = $session->get('last_activity');
        $sessionTimeout = 3600; // 1 hour in seconds (matches session expiration)
        
        if ($lastActivity && (time() - $lastActivity > $sessionTimeout)) {
            // Session has timed out, destroy it and redirect to login
            if (ob_get_level()) {
                ob_end_clean();
            }
            $session->destroy();
            return redirect()->to('/login')->with('message', 'Your session has expired. Please log in again.');
        }
        
        // Check if session has server restart marker
        $serverStartTime = $session->get('server_start_time');
        $currentServerStart = $this->getServerStartTime();
        
        if (!$serverStartTime || $serverStartTime !== $currentServerStart) {
            // Session was created before server restart, force logout
            if (ob_get_level()) {
                ob_end_clean();
            }
            $session->destroy();
            return redirect()->to('/login')->with('message', 'Server was restarted. Please log in again.');
        }
        
        // Update last activity time
        $session->set('last_activity', time());
        
        // Verify user data exists
        if (!$session->get('user_data')) {
            // Invalid session, destroy and redirect
            if (ob_get_level()) {
                ob_end_clean();
            }
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Invalid session. Please log in again.');
        }
    }
    
    /**
     * Check for server restart and clear sessions if needed
     */
    private function checkServerRestart()
    {
        $markerFile = WRITEPATH . 'server_start_marker.txt';
        $currentStart = time();
        
        if (!file_exists($markerFile)) {
            // First request after server start, create marker and clear all sessions
            file_put_contents($markerFile, $currentStart);
            \App\Helpers\SessionHelper::clearAllSessions();
        } else {
            // Check if any session files predate the server start
            $serverStartTime = file_get_contents($markerFile);
            $this->clearPreRestartSessions($serverStartTime);
        }
    }
    
    /**
     * Get server start time from marker file
     */
    private function getServerStartTime()
    {
        $markerFile = WRITEPATH . 'server_start_marker.txt';
        if (file_exists($markerFile)) {
            return file_get_contents($markerFile);
        }
        
        // Create marker file if it doesn't exist
        $startTime = time();
        file_put_contents($markerFile, $startTime);
        return $startTime;
    }
    
    /**
     * Clear sessions that predate server restart
     */
    private function clearPreRestartSessions($serverStartTime)
    {
        $sessionPath = WRITEPATH . 'session';
        
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/ci_session*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.html') {
                    // Clear sessions that were modified before server start time
                    if (filemtime($file) < filemtime(WRITEPATH . 'server_start_marker.txt')) {
                        unlink($file);
                    }
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Prevent browser caching of authenticated pages
        // This prevents users from using the back button to access pages after logout
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Cache-Control', 'post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        
        return $response;
    }
}
