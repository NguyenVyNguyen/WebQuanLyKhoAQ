<?php
include_once "database.php";

class Hoadonxuat {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function insert_hoadonxuat($nhanvien_id, $khachhang_id) {
        $nhanvien_id = $this->db->link->real_escape_string($nhanvien_id);
        $khachhang_id = $this->db->link->real_escape_string($khachhang_id);

        $ngayxuat = date('Y-m-d H:i:s');
        
        $conn = $this->db->getConnection();

        if ($conn) {
            $query = "INSERT INTO tbl_hoadonxuat (nhanvien_id, khachhang_id, ngayxuat) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            
            if ($stmt) {
                $stmt->bind_param("iis", $nhanvien_id, $khachhang_id, $ngayxuat);
                $stmt->execute();
                $last_id = $conn->insert_id;
                $stmt->close();
                return $last_id;
            } else {
                error_log("Prepare failed for insert_hoadonxuat: " . $conn->error);
                return false;
            }
        } else {
            error_log("Database connection is null for insert_hoadonxuat.");
            return false;
        }
    }

    public function insert_chitiet($hoadonxuat_id, $bienthe_id, $sanpham_id, $soluong, $dongia, $thanhtien) {
        $hoadonxuat_id = $this->db->link->real_escape_string($hoadonxuat_id);
        $bienthe_id = $this->db->link->real_escape_string($bienthe_id);
        $sanpham_id = $this->db->link->real_escape_string($sanpham_id);
        $soluong = $this->db->link->real_escape_string($soluong);
        $dongia = $this->db->link->real_escape_string($dongia);
        $thanhtien = $this->db->link->real_escape_string($thanhtien);

        $conn = $this->db->getConnection();

        if ($conn) {
            $query = "INSERT INTO tbl_chitiethoadonxuat (hoadonxuat_id, bienthe_id, sanpham_id, soluong, dongia, thanhtien) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("iiiidd", $hoadonxuat_id, $bienthe_id, $sanpham_id, $soluong, $dongia, $thanhtien); 
                $stmt->execute();
                $result = $stmt->affected_rows;
                $stmt->close();
                return $result > 0;
            } else {
                error_log("Prepare failed for insert_chitiet: " . $conn->error);
                return false;
            }
        } else {
            error_log("Database connection is null for insert_chitiet.");
            return false;
        }
    }

    public function show_hoadon() {
        $query = "SELECT h.hoadonxuat_id, h.ngayxuat, k.khachhang_ten, n.nhanvien_ten
                  FROM tbl_hoadonxuat AS h
                  INNER JOIN tbl_khachhang AS k ON h.khachhang_id = k.khachhang_id
                  INNER JOIN tbl_nhanvien AS n ON h.nhanvien_id = n.nhanvien_id
                  ORDER BY h.ngayxuat DESC";
        return $this->db->select($query);
    }

    public function get_tongtien_donhang($hoadonxuat_id) {
        $conn = $this->db->getConnection();
        if ($conn) {
            $query = "SELECT SUM(thanhtien) AS tong_tien FROM tbl_chitiethoadonxuat WHERE hoadonxuat_id = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $hoadonxuat_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $stmt->close();
                    return $row['tong_tien'];
                }
                $stmt->close();
            } else {
                error_log("Prepare failed for get_tongtien_donhang: " . $conn->error);
            }
        } else {
            error_log("Database connection is null for get_tongtien_donhang.");
        }
        return 0;
    }

    public function get_donhang_info($hoadonxuat_id) {
        $conn = $this->db->getConnection();
        if ($conn) {
            $query = "SELECT h.hoadonxuat_id, h.ngayxuat, k.khachhang_ten, n.nhanvien_ten
                      FROM tbl_hoadonxuat AS h
                      INNER JOIN tbl_khachhang AS k ON h.khachhang_id = k.khachhang_id
                      INNER JOIN tbl_nhanvien AS n ON h.nhanvien_id = n.nhanvien_id
                      WHERE h.hoadonxuat_id = ?"; 
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $hoadonxuat_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $info = $result->fetch_assoc();
                    $stmt->close();
                    return $info;
                }
                $stmt->close();
            } else {
                error_log("Prepare failed for get_donhang_info: " . $conn->error);
            }
        } else {
            error_log("Database connection is null for get_donhang_info.");
        }
        return null;
    }

    public function get_chitiet_donhang($hoadonxuat_id) { 
        $conn = $this->db->getConnection();
        if ($conn) {
            $query = "SELECT 
                        c.soluong, 
                        c.dongia, 
                        c.thanhtien,
                        sp.sanpham_ma,
                        sp.sanpham_tieude,
                        bt.mau_ten,
                        bt.size_ten
                    FROM tbl_chitiethoadonxuat AS c
                    INNER JOIN tbl_bienthe_sanpham AS bt ON c.bienthe_id = bt.bienthe_id
                    INNER JOIN tbl_sanpham AS sp ON c.sanpham_id = sp.sanpham_id
                    WHERE c.hoadonxuat_id = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $hoadonxuat_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                return $result;
            } else {
                error_log("Prepare failed for get_chitiet_donhang: " . $conn->error);
            }
        } else {
            error_log("Database connection is null for get_chitiet_donhang.");
        }
        return false;
    }

    public function update_soluong_tonkho($bienthe_id, $soluong_xuat) {
        $conn = $this->db->getConnection();
        if ($conn) {
            $query = "UPDATE tbl_bienthe_sanpham SET soluong = soluong - ? WHERE bienthe_id = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("ii", $soluong_xuat, $bienthe_id);
                $stmt->execute();
                $result = $stmt->affected_rows;
                $stmt->close();
                return $result > 0;
            } else {
                error_log("Prepare failed for update_soluong_tonkho: " . $conn->error);
                return false;
            }
        } else {
            error_log("Database connection is null for update_soluong_tonkho.");
            return false;
        }
    }

    public function delete_hoadonxuat($hoadonxuat_id) {
        $hoadonxuat_id = $this->db->link->real_escape_string($hoadonxuat_id);
        $conn = $this->db->getConnection();

        if ($conn) {
            $conn->begin_transaction();
            try {
                $query_delete_chitiet = "DELETE FROM tbl_chitiethoadonxuat WHERE hoadonxuat_id = ?";
                $stmt_chitiet = $conn->prepare($query_delete_chitiet);
                if ($stmt_chitiet) {
                    $stmt_chitiet->bind_param("i", $hoadonxuat_id);
                    $stmt_chitiet->execute();
                    $stmt_chitiet->close();
                } else {
                    throw new Exception("Prepare failed for delete_chitiet: " . $conn->error);
                }

                $query_delete_hoadon = "DELETE FROM tbl_hoadonxuat WHERE hoadonxuat_id = ?";
                $stmt_hoadon = $conn->prepare($query_delete_hoadon);
                if ($stmt_hoadon) {
                    $stmt_hoadon->bind_param("i", $hoadonxuat_id);
                    $stmt_hoadon->execute();
                    $deleted_rows = $stmt_hoadon->affected_rows;
                    $stmt_hoadon->close();
                } else {
                    throw new Exception("Prepare failed for delete_hoadon: " . $conn->error);
                }
                
                $conn->commit();
                return $deleted_rows > 0;
            } catch (Exception $e) {
                $conn->rollback();
                error_log("Error deleting hoadonxuat (ID: $hoadonxuat_id): " . $e->getMessage());
                return false;
            }
        } else {
            error_log("Database connection is null for delete_hoadonxuat.");
            return false;
        }
    }

    public function get_all_donhang($start, $limit, $search_query)
    {
        $search_query = $this->db->link->real_escape_string($search_query);
        $query = "SELECT tbl_hoadonxuat.*, tbl_khachhang.khachhang_ten, tbl_nhanvien.nhanvien_ten, SUM(tbl_chitiethoadonxuat.thanhtien) AS tongtien
                    FROM tbl_hoadonxuat
                    LEFT JOIN tbl_khachhang ON tbl_hoadonxuat.khachhang_id = tbl_khachhang.khachhang_id
                    LEFT JOIN tbl_nhanvien ON tbl_hoadonxuat.nhanvien_id = tbl_nhanvien.nhanvien_id 
                    LEFT JOIN tbl_chitiethoadonxuat ON tbl_hoadonxuat.hoadonxuat_id = tbl_chitiethoadonxuat.hoadonxuat_id";
                
        if (!empty($search_query)) {
            $query .= " WHERE tbl_hoadonxuat.hoadonxuat_id LIKE '%$search_query%'
                        OR tbl_khachhang.khachhang_ten LIKE '%$search_query%'
                        OR tbl_nhanvien.nhanvien_ten LIKE '%$search_query%'
                        OR tbl_hoadonxuat.ngayxuat LIKE '%$search_query%'";
        }

        $query .= " GROUP BY tbl_hoadonxuat.hoadonxuat_id";
        
        $query .= " ORDER BY tbl_hoadonxuat.hoadonxuat_id DESC LIMIT $start, $limit";
        
        $result = $this->db->select($query);
        return $result;
    }

    public function get_all_donhang_count($search_query)
    {
        $search_query = $this->db->link->real_escape_string($search_query);
        $query = "SELECT COUNT(DISTINCT tbl_hoadonxuat.hoadonxuat_id) as total
                    FROM tbl_hoadonxuat
                    LEFT JOIN tbl_khachhang ON tbl_hoadonxuat.khachhang_id = tbl_khachhang.khachhang_id
                    LEFT JOIN tbl_nhanvien ON tbl_hoadonxuat.nhanvien_id = tbl_nhanvien.nhanvien_id";

        if (!empty($search_query)) {
            $query .= " WHERE tbl_hoadonxuat.hoadonxuat_id LIKE '%$search_query%'
                        OR tbl_khachhang.khachhang_ten LIKE '%$search_query%'
                        OR tbl_nhanvien.nhanvien_ten LIKE '%$search_query%'
                        OR tbl_hoadonxuat.ngayxuat LIKE '%$search_query%'";
        }
        $result = $this->db->select($query);
        return $result->fetch_assoc()['total'];
    }
    
}
?>
