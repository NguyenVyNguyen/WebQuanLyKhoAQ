<?php
include_once "database.php";

class product {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function show_products_only() {
        $query = "SELECT * FROM tbl_sanpham ORDER BY sanpham_id DESC";
        return $this->db->select($query);
    }

    public function show_cartegory(){
       $query = "SELECT * FROM tbl_danhmuc ORDER BY danhmuc_id DESC";
       $result = $this -> db ->select($query);
       return $result;
    }

    public function show_products_variant() {
        $query = "SELECT sp.*, bienthe.*
                  FROM tbl_sanpham AS sp
                  INNER JOIN tbl_bienthe_sanpham AS bienthe ON sp.sanpham_id = bienthe.sanpham_id
                  ORDER BY sp.sanpham_id DESC";
        return $this->db->select($query);
    }

    public function insert_product($post_data) {
        $product_data = [
            'sanpham_ma'     => $post_data['sanpham_ma'],
            'danhmuc_id'     => $post_data['danhmuc_id'],
            'sanpham_tieude' => $post_data['sanpham_tieude'],
            'sanpham_gia'    => $post_data['sanpham_gia'],
        ];

        $sanpham_id = $this->insert_product_main($product_data);

        if ($sanpham_id) {
            if (!empty($post_data['variants'])) {
                foreach ($post_data['variants'] as $variant_data) {
                    $variant_data['sanpham_id'] = $sanpham_id;
                    $this->insert_product_variant($variant_data);
                }
            }
            return true;
        }
        return false;
    }

    private function insert_product_main($data) {
        $sanpham_ma     = mysqli_real_escape_string($this->db->link, $data['sanpham_ma']);
        $danhmuc_id     = intval($data['danhmuc_id']);
        $sanpham_tieude = mysqli_real_escape_string($this->db->link, $data['sanpham_tieude']);
        $sanpham_gia    = floatval($data['sanpham_gia']);
        
        $query = "INSERT INTO tbl_sanpham (sanpham_ma, danhmuc_id, sanpham_tieude, sanpham_gia) 
                  VALUES ('$sanpham_ma', '$danhmuc_id', '$sanpham_tieude', '$sanpham_gia')";
        
        $result = $this->db->insert($query);
        if ($result) {
            return $this->db->link->insert_id;
        }
        return false;
    }

    private function insert_product_variant($data) {
        $sanpham_id = intval($data['sanpham_id']);
        $mau_ten        = mysqli_real_escape_string($this->db->link, $data['mau_ten']);
        $size_ten       = mysqli_real_escape_string($this->db->link, $data['size_ten']);
        $soluong    = intval($data['soluong']);
        
        $query = "INSERT INTO  tbl_bienthe_sanpham (sanpham_id, mau_ten, size_ten, soluong) 
                  VALUES ('$sanpham_id', '$mau_ten', '$size_ten', '$soluong')";
        
        return $this->db->insert($query);
    }

    public function get_product_with_variants($id, $is_bienthe_id = false) {
        $id = $this->db->link->real_escape_string($id);

        if ($is_bienthe_id) {
            $query_bienthe_to_sanpham = "SELECT sanpham_id FROM tbl_bienthe_sanpham WHERE bienthe_id = '$id'";
            $result_bienthe_to_sanpham = $this->db->select($query_bienthe_to_sanpham);
            if (!$result_bienthe_to_sanpham || $result_bienthe_to_sanpham->num_rows == 0) {
                return false;
            }
            $row = $result_bienthe_to_sanpham->fetch_assoc();
            $sanpham_id = $row['sanpham_id'];
        } else {
            $sanpham_id = $id;
        }

        $query_product = "SELECT * FROM tbl_sanpham WHERE sanpham_id = '$sanpham_id'";
        $result_product = $this->db->select($query_product);
        if (!$result_product || $result_product->num_rows == 0) {
            return false;
        }
        $product_info = $result_product->fetch_assoc();

        $query_variants = "SELECT *
                           FROM tbl_bienthe_sanpham 
                           WHERE sanpham_id = '$sanpham_id'";
        $result_variants = $this->db->select($query_variants);
        $variants = [];
        if ($result_variants && $result_variants->num_rows > 0) {
            while ($row = $result_variants->fetch_assoc()) {
                $variants[] = $row;
            }
        }
        $product_info['variants'] = $variants;
        return $product_info;
    }
    
    public function get_single_variant($bienthe_id) {
        $bienthe_id = $this->db->link->real_escape_string($bienthe_id);
        $query = "SELECT * FROM tbl_bienthe_sanpham WHERE bienthe_id = '$bienthe_id'";
        $result = $this->db->select($query);
        if ($result) {
            return $result->fetch_assoc();
        }
        return false;
    }

    public function delete_product($bienthe_id) {
        $delete_variants_query = "DELETE FROM tbl_bienthe_sanpham WHERE bienthe_id = '$bienthe_id'";
        $this->db->delete($delete_variants_query);
    }

    public function delete_product_all($sanpham_id) {
        $sanpham_id = $this->db->link->real_escape_string($sanpham_id);
        try {
            $this->db->link->begin_transaction();

            $delete_variants_query = "DELETE FROM tbl_bienthe_sanpham WHERE sanpham_id = '$sanpham_id'";
            $this->db->delete($delete_variants_query);

            $delete_product_query = "DELETE FROM tbl_sanpham WHERE sanpham_id = '$sanpham_id'";
            $result = $this->db->delete($delete_product_query);
            $this->db->link->commit();

            return $result;
        } catch (Exception $e) {
            $this->db->link->rollback();
            return false;
        }
    }

    public function update_product($sanpham_id, $danhmuc_id, $sanpham_tieude, $sanpham_gia) {
        $query = "UPDATE tbl_sanpham 
                  SET sanpham_tieude = '$sanpham_tieude', 
                      sanpham_gia = '$sanpham_gia', 
                      danhmuc_id = '$danhmuc_id'
                  WHERE sanpham_id = '$sanpham_id'";
        return $this->db->update($query);
    }

    public function update_variant_quantity($bienthe_id, $soluong) {
        $query = "UPDATE tbl_bienthe_sanpham SET soluong = '$soluong' WHERE bienthe_id = '$bienthe_id'";
        return $this->db->update($query);
    }

    public function insert_variant($sanpham_id, $mau_ten, $size_ten, $soluong) {
        $sanpham_id = $this->db->link->real_escape_string($sanpham_id);
        $mau_ten = $this->db->link->real_escape_string($mau_ten);
        $size_ten = $this->db->link->real_escape_string($size_ten);
        $soluong = $this->db->link->real_escape_string($soluong);
        $check_query = "SELECT * FROM tbl_bienthe_sanpham WHERE sanpham_id = '$sanpham_id' AND mau_ten = '$mau_ten' AND size_ten = '$size_ten'";
        $result = $this->db->select($check_query);
        
        if ($result && $result->num_rows > 0) {
            return false;
        } else {
            $insert_query = "INSERT INTO tbl_bienthe_sanpham (sanpham_id, mau_ten, size_ten, soluong) VALUES ('$sanpham_id', '$mau_ten', '$size_ten', '$soluong')";
            return $this->db->insert($insert_query);
        }
    }

    public function get_sanpham_info_by_id($sanpham_id) {
        $sanpham_id = $this->db->link->real_escape_string($sanpham_id);
        $query = "SELECT sanpham_tieude FROM tbl_sanpham WHERE sanpham_id = '$sanpham_id'";
        return $this->db->select($query);
    }

    public function insert_multiple_variants($sanpham_id, $variants) {
        $this->db->link->begin_transaction();
        try {
            foreach ($variants as $variant) {
                $mau_ten = $variant['mau_ten'];
                $size_ten = $variant['size_ten'];
                $soluong = $variant['soluong'];

                $check_query = "SELECT bienthe_id FROM tbl_bienthe_sanpham WHERE sanpham_id = ? AND mau_ten = ? AND size_ten = ?";
                $check_stmt = $this->db->link->prepare($check_query);
                if (!$check_stmt) {
                    $this->db->link->rollback();
                    return false;
                }
                $check_stmt->bind_param("iss", $sanpham_id, $mau_ten, $size_ten);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows > 0) {
                    $this->db->link->rollback();
                    return false; 
                }

                $insert_query = "INSERT INTO tbl_bienthe_sanpham (sanpham_id, mau_ten, size_ten, soluong) VALUES (?, ?, ?, ?)";
                $insert_stmt = $this->db->link->prepare($insert_query);
                if (!$insert_stmt) {
                    $this->db->link->rollback();
                    return false;
                }
                $insert_stmt->bind_param("issi", $sanpham_id, $mau_ten, $size_ten, $soluong);
                if (!$insert_stmt->execute()) {
                    $this->db->link->rollback();
                    return false;
                }
            }
            $this->db->link->commit();
            return true;
        } catch (Exception $e) {
            $this->db->link->rollback();
            return false;
        }
    }

    public function get_variant_info($bienthe_id) {
        $query = "SELECT 
            tbl_bienthe_sanpham.bienthe_id, 
            tbl_bienthe_sanpham.sanpham_id,
            tbl_bienthe_sanpham.mau_ten,
            tbl_bienthe_sanpham.size_ten,
            tbl_bienthe_sanpham.soluong,
            tbl_sanpham.sanpham_tieude,
            tbl_sanpham.sanpham_ma,
            tbl_sanpham.sanpham_gia
        FROM tbl_bienthe_sanpham 
        INNER JOIN tbl_sanpham ON tbl_bienthe_sanpham.sanpham_id = tbl_sanpham.sanpham_id
        WHERE tbl_bienthe_sanpham.bienthe_id = '$bienthe_id'";

        $result = $this->db->select($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function get_products_variants_by_ids($variant_ids) {
            if (empty($variant_ids)) {
                return false;
            }
            $ids_string = implode(',', array_map('intval', $variant_ids));
            $query = "SELECT 
                tbl_bienthe_sanpham.bienthe_id, 
                tbl_bienthe_sanpham.sanpham_id,
                tbl_bienthe_sanpham.mau_ten,
                tbl_bienthe_sanpham.size_ten,
                tbl_bienthe_sanpham.soluong,
                tbl_sanpham.sanpham_tieude,
                tbl_sanpham.sanpham_ma,
                tbl_sanpham.sanpham_gia
            FROM tbl_bienthe_sanpham 
            INNER JOIN tbl_sanpham ON tbl_bienthe_sanpham.sanpham_id = tbl_sanpham.sanpham_id
            WHERE tbl_bienthe_sanpham.bienthe_id IN ($ids_string)";

            $result = $this->db->select($query);

            return $result; 
        }
    
    public function get_total_products_count($search_query = '')
    {
        $query = "SELECT COUNT(DISTINCT tbl_sanpham.sanpham_id) AS total FROM tbl_sanpham 
                  INNER JOIN tbl_bienthe_sanpham ON tbl_sanpham.sanpham_id = tbl_bienthe_sanpham.sanpham_id
                  WHERE tbl_sanpham.sanpham_tieude LIKE ?";
        $search_param = '%' . $search_query . '%';
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }    

    public function get_total_variants_count($search_query = '')
    {
        $query = "SELECT COUNT(tbl_bienthe.bienthe_id) AS total FROM tbl_bienthe_sanpham AS tbl_bienthe
                  INNER JOIN tbl_sanpham ON tbl_bienthe.sanpham_id = tbl_sanpham.sanpham_id
                  WHERE tbl_sanpham.sanpham_tieude LIKE ?";
        $search_param = '%' . $search_query . '%';
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function show_products_variantP($search_query = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT sp.sanpham_id, sp.sanpham_tieude, sp.sanpham_ma, sp.sanpham_gia, 
                         bt.bienthe_id, bt.soluong, bt.mau_ten, bt.size_ten
                  FROM tbl_bienthe_sanpham AS bt
                  JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  WHERE sp.sanpham_tieude LIKE ?
                  ORDER BY sp.sanpham_id, bt.bienthe_id
                  LIMIT ? OFFSET ?";
        $search_param = '%' . $search_query . '%';
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("sii", $search_param, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}
?>
