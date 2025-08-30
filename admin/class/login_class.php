<?php
include "database.php";

class Login
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function check_login($user_name, $user_password)
    {
        // Prevent SQL Injection
        $user_name = $this->db->link->real_escape_string($user_name);
        $user_password = $this->db->link->real_escape_string($user_password);
        
        // Use a secure password hashing method in production!
        // For this example, we'll use a simple text comparison.
        // The real-world implementation should use password_verify().
        
        // Correct query to join tbl_account and tbl_nhanvien
        $query = "SELECT 
                    tbl_nhanvien.nhanvien_id, 
                    tbl_account.account_username, 
                    tbl_nhanvien.nhanvien_ten
                  FROM tbl_account
                  INNER JOIN tbl_nhanvien ON tbl_account.account_id = tbl_nhanvien.account_id
                  WHERE tbl_account.account_username = '$user_name' AND tbl_account.account_password = '$user_password'";
                  
        $result = $this->db->select($query);

        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            return $user_data; // Return user data upon successful login
        } else {
            return false; // Return false on failure
        }
    }
}
?>