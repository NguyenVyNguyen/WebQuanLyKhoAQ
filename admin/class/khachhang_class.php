<?php
include_once "database.php";

class khachhang
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
 
    public function insert_khachhang($ten, $dienthoai, $email, $diachi)
    {
        $query = "INSERT INTO tbl_khachhang (khachhang_ten, khachhang_dienthoai, khachhang_email, khachhang_diachi) 
                  VALUES ('$ten', '$dienthoai', '$email', '$diachi')";
        $result = $this->db->insert($query);
        return $result;
    }

    public function show_khachhang()
    {
        $query = "SELECT * FROM tbl_khachhang ORDER BY khachhang_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_khachhang($khachhang_id)
    {
        $query = "SELECT * FROM tbl_khachhang WHERE khachhang_id = '$khachhang_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_khachhang_by_id($khachhang_id) {
        $query = "SELECT * FROM tbl_khachhang WHERE khachhang_id = '$khachhang_id'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    public function update_khachhang($khachhang_id, $ten, $dienthoai, $email, $diachi)
    {
        $query = "UPDATE tbl_khachhang 
                  SET khachhang_ten = '$ten', 
                      khachhang_dienthoai = '$dienthoai',
                      khachhang_email = '$email',
                      khachhang_diachi = '$diachi'
                  WHERE khachhang_id = '$khachhang_id'";
        $result = $this->db->update($query);
        return $result;
    }

    public function delete_khachhang($khachhang_id)
    {
        $query = "DELETE FROM tbl_khachhang WHERE khachhang_id = '$khachhang_id'";
        $result = $this->db->delete($query);
        return $result;
    }

    public function get_total_khachhang_count($search_query = '')
    {
        $query = "SELECT COUNT(*) AS total FROM tbl_khachhang 
                  WHERE khachhang_ten LIKE ? OR khachhang_dienthoai LIKE ? OR khachhang_email LIKE ?";
        $search_param = '%' . $search_query . '%';
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function show_khachhangP($search_query = '', $limit = 15, $offset = 0)
    {
        $query = "SELECT * FROM tbl_khachhang
                  WHERE khachhang_ten LIKE ? OR khachhang_dienthoai LIKE ? OR khachhang_email LIKE ?
                  ORDER BY khachhang_id DESC
                  LIMIT ? OFFSET ?";
        $search_param = '%' . $search_query . '%';
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}
?>
