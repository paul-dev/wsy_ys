<?php
class ModelAccountWishlist extends Model {
	public function getProductWishlist() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_customer WHERE customer_id = '" . (int)$this->customer->getId() . "'");
        $data = array();
        foreach ($query->rows as $result) {
            $data[$result['product_id']] = array(
                'product_id' => $result['product_id']
            );
        }
        return $data;
    }

    public function getShopWishlist() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_to_customer WHERE customer_id = '" . (int)$this->customer->getId() . "'");
        $data = array();
        foreach ($query->rows as $result) {
            $data[$result['store_id']] = array(
                'store_id' => $result['store_id']
            );
        }
        return $data;
    }

    public function addProductWishlist ($product_id = 0) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_customer SET customer_id = '" . (int)$this->customer->getId() . "', product_id = '".(int)$product_id."'");

        return;
    }

    public function addShopWishlist ($store_id = 0) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "store_to_customer SET customer_id = '" . (int)$this->customer->getId() . "', store_id = '".(int)$store_id."'");

        return;
    }

    public function removeProductWishlist($product_id = 0) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_customer WHERE product_id = '" . (int)$product_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

        return;
    }

    public function removeShopWishlist($store_id = 0) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "store_to_customer WHERE store_id = '" . (int)$store_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

        return;
    }

	public function getTotalProductWished($product_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_customer WHERE product_id = '" . (int)$product_id . "'");

		return $query->row['total'];
	}

    public function getTotalShopWished($store_id = 0) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store_to_customer WHERE store_id = '" . (int)$store_id . "'");

        return $query->row['total'];
    }
}