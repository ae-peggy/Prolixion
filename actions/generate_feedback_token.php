<?php
require_once '../db/database.php';

class FeedbackTokenManager {
    private $conn;
    private const TOKEN_EXPIRY_HOURS = 5;

    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }

    public function generateToken($target_user_id) {
        // Generate a cryptographically secure token
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_EXPIRY_HOURS . ' hours'));

        // Store token in database
        $stmt = $this->conn->prepare("
            INSERT INTO feedback_tokens 
            (token, target_user_id, created_at, expires_at) 
            VALUES (?, ?, NOW(), ?)
        ");
        $stmt->bind_param("sis", $token, $target_user_id, $expiry);
        
        if ($stmt->execute()) {
            $stmt->close();
            return $token;
        }

        $stmt->close();
        return false;
    }

    public function validateToken($token, $target_user_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM feedback_tokens 
            WHERE token = ? 
            AND target_user_id = ? 
            AND expires_at > NOW() 
            AND used = 0
        ");
        $stmt->bind_param("si", $token, $target_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $is_valid = $result->num_rows > 0;
        $stmt->close();

        return $is_valid;
    }

    public function markTokenAsUsed($token) {
        $stmt = $this->conn->prepare("
            UPDATE feedback_tokens 
            SET used = 1 
            WHERE token = ?
        ");
        $stmt->bind_param("s", $token);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Cleanup expired tokens
    public function cleanupExpiredTokens() {
        $stmt = $this->conn->prepare("
            DELETE FROM feedback_tokens 
            WHERE expires_at < NOW()
        ");
        $stmt->execute();
        $stmt->close();
    }
}

// Ensure the feedback_tokens table exists
$create_table_sql = "
CREATE TABLE IF NOT EXISTS feedback_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) NOT NULL UNIQUE,
    target_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT 0,
    FOREIGN KEY (target_user_id) REFERENCES user(user_id)
)";
$conn->query($create_table_sql);
