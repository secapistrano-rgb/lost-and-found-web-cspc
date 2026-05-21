<?php

namespace App\Helpers;

/**
 * SessionHelper - Manages session cleanup and validation
 * 
 * This helper addresses the issue where sessions persist between server restarts,
 * potentially allowing users to bypass login after closing/restarting the server.
 * 
 * Usage:
 * - clearAllSessions(): Force logout all users (useful for development)
 * - clearExpiredSessions(): Remove only expired session files
 * - isSessionExpired(): Check if a specific session has expired
 */
class SessionHelper
{
    /**
     * Clear all session files in the session directory
     * This is useful for development when you want to force logout all users
     */
    public static function clearAllSessions()
    {
        $sessionPath = WRITEPATH . 'session';
        
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/ci_session*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            return count($files);
        }
        
        return 0;
    }
    
    /**
     * Clear expired session files
     */
    public static function clearExpiredSessions()
    {
        $sessionPath = WRITEPATH . 'session';
        $sessionTimeout = 3600; // 1 hour
        $now = time();
        $clearedCount = 0;
        
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/ci_session*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileModTime = filemtime($file);
                    if (($now - $fileModTime) > $sessionTimeout) {
                        unlink($file);
                        $clearedCount++;
                    }
                }
            }
        }
        
        return $clearedCount;
    }
    
    /**
     * Check if a session is valid by checking file age
     */
    public static function isSessionExpired($sessionId)
    {
        $sessionPath = WRITEPATH . 'session';
        $sessionFile = $sessionPath . '/ci_session' . $sessionId;
        $sessionTimeout = 3600; // 1 hour
        
        if (file_exists($sessionFile)) {
            $fileModTime = filemtime($sessionFile);
            return (time() - $fileModTime) > $sessionTimeout;
        }
        
        return true; // File doesn't exist, consider it expired
    }
}