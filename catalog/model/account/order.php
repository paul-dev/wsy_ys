<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'fullname'                => $order_query->row['fullname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_fullname'        => $order_query->row['payment_fullname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address'         => $order_query->row['payment_address'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
                'payment_area'            => $order_query->row['payment_area'],
                'payment_area_id'         => $order_query->row['payment_area_id'],
                'payment_city'            => $order_query->row['payment_city'],
                'payment_city_id'         => $order_query->row['payment_city_id'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
                'payment_custom_field'    => unserialize($order_query->row['payment_custom_field']),
                'payment_address_format'  => $order_query->row['payment_address_format'],
                'payment_method'          => $order_query->row['payment_method'],
                'payment_url'             => $order_query->row['payment_url'],
				'shipping_fullname'       => $order_query->row['shipping_fullname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address'        => $order_query->row['shipping_address'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
                'shipping_area'           => $order_query->row['shipping_area'],
                'shipping_area_id'        => $order_query->row['shipping_area_id'],
                'shipping_city'           => $order_query->row['shipping_city'],
                'shipping_city_id'        => $order_query->row['shipping_city_id'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
                'shipping_custom_field'   => unserialize($order_query->row['shipping_custom_field']),
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method'         => $order_query->row['shipping_method'],
                'shipping_type_code'      => $order_query->row['shipping_type_code'],
                'shipping_type_name'      => $order_query->row['shipping_type_name'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;
		}
	}

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

        $query = $this->db->query("SELECT o.order_id, o.store_id, o.parent_id, o.order_status_id, o.payment_url, o.fullname, o.shipping_fullname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id > 0 AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);
        //$query = $this->db->query("SELECT o.order_id, o.fullname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

    public function getShopOrders($parent_id = 0) {
        $query = $this->db->query("SELECT o.order_id, o.fullname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.parent_id = '".(int)$parent_id."' AND o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id ASC");

        return $query->rows;
    }

	public function getOrderProduct($order_id, $order_product_id) {
		$query = $this->db->query("SELECT op.*, r.return_id FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "return r ON op.order_product_id=r.order_product_id WHERE op.order_id = '" . (int)$order_id . "' AND op.order_product_id = '" . (int)$order_product_id . "'");

		return $query->row;
	}

    public function getOrderProductBy($order_id, $order_product_id) {
        $query = $this->db->query("SELECT op.*, r.review_id FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "review AS r ON op.order_id=r.order_id AND op.order_product_id=r.order_product_id WHERE op.order_id = '" . (int)$order_id . "' AND op.order_product_id = '" . (int)$order_product_id . "' LIMIT 1");

        return $query->row;
    }

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT op.*, pr.return_id, pr.return_status_id, rs.name as return_status, r.review_id, p.image FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "return AS pr ON op.order_product_id=pr.order_product_id LEFT JOIN " . DB_PREFIX . "return_status AS rs ON pr.return_status_id=rs.return_status_id AND rs.language_id = '".(int)$this->config->get('config_language_id')."' LEFT JOIN " . DB_PREFIX . "review AS r ON op.order_id=r.order_id AND op.order_product_id=r.order_product_id LEFT JOIN " . DB_PREFIX . "product AS p ON op.product_id = p.product_id WHERE op.order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

    public function getShopOrderProducts($order_ids) {
        $query = $this->db->query("SELECT op.*, pr.return_id, pr.return_status_id, rs.name as return_status, p.image FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "return AS pr ON op.order_product_id=pr.order_product_id LEFT JOIN " . DB_PREFIX . "return_status AS rs ON pr.return_status_id=rs.return_status_id AND rs.language_id = '".(int)$this->config->get('config_language_id')."' LEFT JOIN " . DB_PREFIX . "product AS p ON op.product_id = p.product_id WHERE op.order_id IN ('" . join("','", $order_ids) . "')");

        return $query->rows;
    }

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getTotalOrders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id > 0");

		return $query->row['total'];
	}

	public function getTotalOrderProductsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
        $shop_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order AS o LEFT JOIN " . DB_PREFIX . "order_product AS p ON o.order_id=p.order_id WHERE o.parent_id = '" . (int)$order_id . "'");

		return $query->row['total'] + $shop_query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
}