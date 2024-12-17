<?php
class SystemLogger {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    public function logActivity($userId, $activityType, $description) {
        $ip_address = $this->getClientIP();
        
        $query = "INSERT INTO system_activities (user_id, activity_type, description, ip_address) 
                 VALUES (?, ?, ?, ?)";
                 
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isss", $userId, $activityType, $description, $ip_address);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error logging system activity: " . $e->getMessage());
            return false;
        }
    }
    
    private function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    // Helper methods for common activities
    public function logLogin($userId) {
        return $this->logActivity($userId, 'login', 'User logged in successfully');
    }
    
    public function logLogout($userId) {
        return $this->logActivity($userId, 'logout', 'User logged out');
    }
    
    public function logUserCreation($adminId, $newUserId) {
        $description = "Admin created new user (ID: $newUserId)";
        return $this->logActivity($adminId, 'user_creation', $description);
    }
    
    public function logUserUpdate($adminId, $updatedUserId) {
        $description = "Admin updated user (ID: $updatedUserId)";
        return $this->logActivity($adminId, 'user_update', $description);
    }
    
    public function logUserDeletion($adminId, $deletedUserId) {
        $description = "Admin deleted user (ID: $deletedUserId)";
        return $this->logActivity($adminId, 'user_deletion', $description);
    }
    
    public function logProfileUpdate($userId) {
        return $this->logActivity($userId, 'profile_update', 'User updated their profile');
    }
    
    public function logSettingsChange($userId, $settingName) {
        $description = "Settings changed: $settingName";
        return $this->logActivity($userId, 'settings_change', $description);
    }
}
?>
