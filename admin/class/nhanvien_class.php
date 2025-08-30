<?php
include_once "database.php";

class NhanVien {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function insert_nhanvien($username, $password, $role, $ten, $dienthoai, $email, $diachi, $isactive = 1) {
    $username = mysqli_real_escape_string($this->db->link, $username);

    $check = $this->db->select("SELECT * FROM tbl_account WHERE account_username = '$username' LIMIT 1");
    if ($check && $check->num_rows > 0) {
        return "Tài khoản đã tồn tại, vui lòng chọn username khác!";
    }

    $hashed_password = $password;

    $query_account = "INSERT INTO tbl_account (account_username, account_password, account_role) 
                      VALUES ('$username', '$hashed_password', '$role')";
    $account_id = $this->db->insert_return_id($query_account);

    if ($account_id) {
        $query_nv = "INSERT INTO tbl_nhanvien 
                        (account_id, nhanvien_ten, nhanvien_dienthoai, nhanvien_email, nhanvien_diachi, nhanvien_isactive) 
                     VALUES 
                        ('$account_id', '$ten', '$dienthoai', '$email', '$diachi', '$isactive')";
        $result = $this->db->insert($query_nv);
        header('Location:nhanvienlist.php');
        return $result;
    } else {
        return false;
    }
}

    public function show_nhanvien() {
    $query = "SELECT nv.nhanvien_id, 
                     nv.nhanvien_ten, 
                     nv.nhanvien_dienthoai,
                     nv.nhanvien_email,
                     nv.nhanvien_diachi,
                     nv.nhanvien_isactive,
                     acc.account_username,
                     acc.account_password, 
                     acc.account_role 
              FROM tbl_nhanvien nv
              LEFT JOIN tbl_account acc ON nv.account_id = acc.account_id
              ORDER BY nv.nhanvien_id DESC";
    return $this->db->select($query);
    }

    public function get_nhanvien($nhanvien_id) {
        $query = "SELECT nv.*, 
                         acc.account_username, 
                         acc.account_password, 
                         acc.account_role
                  FROM tbl_nhanvien nv
                  JOIN tbl_account acc ON nv.account_id = acc.account_id
                  WHERE nv.nhanvien_id = '$nhanvien_id'";
        return $this->db->select($query);
    }

    public function get_nhanvien_by_id($nhanvien_id) {
        $query = "SELECT * FROM tbl_nhanvien WHERE nhanvien_id = '$nhanvien_id'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    public function update_nhanvien($nhanvien_id, $username, $password, $role, $ten, $dienthoai, $email, $diachi, $isactive) {
        $query_get = "SELECT account_id FROM tbl_nhanvien WHERE nhanvien_id = '$nhanvien_id'";
        $result_get = $this->db->select($query_get);
        if ($result_get) {
            $row = $result_get->fetch_assoc();
            $account_id = $row['account_id'];

            $password_sql = "";
            if (!empty($password)) {
                $hashed_password = $password;
                $password_sql = ", account_password = '$hashed_password'";
            }
            $query_acc = "UPDATE tbl_account 
                          SET account_username = '$username',
                              account_role = '$role'
                              $password_sql
                          WHERE account_id = '$account_id'";
            $this->db->update($query_acc);

            $query_nv = "UPDATE tbl_nhanvien 
                         SET nhanvien_ten = '$ten',
                             nhanvien_dienthoai = '$dienthoai',
                             nhanvien_email = '$email',
                             nhanvien_diachi = '$diachi',
                             nhanvien_isactive = '$isactive'
                         WHERE nhanvien_id = '$nhanvien_id'";
            $result = $this->db->update($query_nv);

            header('Location:nhanvienlist.php');
            return $result;
        }
        return false;
    }

    public function delete_nhanvien($nhanvien_id) {
    $query_get = "SELECT account_id FROM tbl_nhanvien WHERE nhanvien_id = '$nhanvien_id'";
    $result_get = $this->db->select($query_get);

    if ($result_get) {
        $row = $result_get->fetch_assoc();
        $account_id = $row['account_id'];
        $this->db->delete("DELETE FROM tbl_nhanvien WHERE nhanvien_id = '$nhanvien_id'");
        $this->db->delete("DELETE FROM tbl_account WHERE account_id = '$account_id'");
        $alert = "<span class='alert-style'>Xóa thành công</span>";
        return $alert;
    } else {
        $alert = "<span class='alert-style'>Không tìm thấy nhân viên</span>";
        return $alert;
    }
    }

    public function get_total_nhanvien_count($search_query = '')
    {

        if (!$this->db->link) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total FROM tbl_nhanvien";
        $params = [];

        if (!empty($search_query)) {
            $query .= " WHERE nhanvien_ten LIKE ? OR nhanvien_email LIKE ? OR nhanvien_dienthoai LIKE ?";
            $search_param = '%' . $search_query . '%';
            $params = [$search_param, $search_param, $search_param];
        }

        try {
            $stmt = $this->db->link->prepare($query);
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (Exception $e) {
            return 0;
        }
    }

    public function show_nhanvienP($items_per_page, $offset, $search_query = '')
    {
        if (!$this->db->link) {
            return null;
        }

        $query = "SELECT tbl_nhanvien.*, tbl_account.account_username, tbl_account.account_password, tbl_account.account_role 
                  FROM tbl_nhanvien
                  LEFT JOIN tbl_account ON tbl_nhanvien.account_id = tbl_account.account_id";
        $params = [];
        $types = '';

        if (!empty($search_query)) {
            $query .= " WHERE nhanvien_ten LIKE ? OR nhanvien_email LIKE ? OR nhanvien_dienthoai LIKE ? OR tbl_account.account_username LIKE ?";
            $search_param = '%' . $search_query . '%';
            $params = [$search_param, $search_param, $search_param, $search_param];
            $types = 'ssss';
        }

        $query .= " LIMIT ? OFFSET ?";
        $params[] = $items_per_page;
        $params[] = $offset;
        $types .= 'ii';

        try {
            $stmt = $this->db->link->prepare($query);
            if ($stmt && !empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            return $stmt->get_result();
        } catch (Exception $e) {
            return null;
        }
    }

}
?>
