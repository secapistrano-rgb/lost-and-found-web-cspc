<?php

if (!function_exists('activity_logger')) {
    function activity_logger() {
        return \App\Helpers\ActivityLogger::getInstance();
    }
}

if (!function_exists('log_activity')) {
    function log_activity($module, $action, $description, $userId = null, $entityType = null, $entityId = null, $severity = 'medium', $oldValues = null, $newValues = null) {
        $logger = activity_logger();
        return $logger->log($module, $action, $description, $userId, $entityType, $entityId, $severity, $oldValues, $newValues);
    }
}