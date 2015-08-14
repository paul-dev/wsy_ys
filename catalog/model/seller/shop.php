<?php
class ModelSellerShop extends Model {
	public function addStore($data) {
		$this->event->trigger('pre.admin.store.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "store SET customer_id='".(int)$this->customer->getId()."', name = '" . $this->db->escape($data['config_name']) . "', `key` = '" . $this->db->escape($data['config_key']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "', date_added = NOW()");

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE store_id = '0'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_route['layout_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "', store_id = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.add', $store_id);

		return $store_id;
	}

	public function editStore($store_id, $data) {
		$this->event->trigger('pre.admin.store.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `key` = '" . $this->db->escape($data['config_key']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "' WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.edit', $store_id);
	}

	public function deleteStore($store_id) {
		$this->event->trigger('pre.admin.store.delete', $store_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.delete', $store_id);
	}

	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT s.*, c.fullname, c.custom_field, (SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_to_store WHERE store_id = '".(int)$store_id."' ) AS total_product, (SELECT SUM(op.quantity) as total FROM " . DB_PREFIX . "order AS o INNER JOIN " . DB_PREFIX . "order_product AS op ON o.order_id=op.order_id AND o.parent_id>0 AND o.order_status_id IN (2,3,5,15) WHERE o.store_id = '".(int)$store_id."' ) AS total_sell FROM " . DB_PREFIX . "store s LEFT JOIN " . DB_PREFIX . "customer c ON s.customer_id = c.customer_id WHERE s.store_id = '" . (int)$store_id . "'");

		return $query->row;
	}

    public function getStoreByKey($store_key) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE `key` = '" . $store_key . "'");

        return $query->row;
    }

	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

    public function getShops($data = array()) {
        $store_data = array();
        $sql = "SELECT s.*, t.`value` as shop_image FROM " . DB_PREFIX . "store as s LEFT JOIN " . DB_PREFIX . "setting as t ON s.store_id=t.store_id";
        $sql .= " INNER JOIN " . DB_PREFIX . "product_to_store as p2s ON s.store_id=p2s.store_id INNER JOIN " . DB_PREFIX . "product as p ON p.product_id=p2s.product_id";
        if (!empty($data['filter_category_id'])) {
            $implode = array();
            foreach (explode(',', $data['filter_category_id']) as $category_id) {
                $implode[] = (int)$category_id;
            }

            if (!empty($data['filter_sub_category'])) {
                $sql .= " INNER JOIN " . DB_PREFIX . "product_to_category p2c ON p.product_id=p2c.product_id INNER JOIN " . DB_PREFIX . "category_path cp  ON (cp.category_id = p2c.category_id)";
                $sql .= " AND cp.path_id IN (" . implode(',', $implode) . ")";
            } else {
                //$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
                $sql .= " INNER JOIN " . DB_PREFIX . "product_to_category as p2c ON p.product_id=p2c.product_id AND p2c.category_id IN (" . implode(',', $implode) . ")";
            }
        }

        $sql .= " WHERE t.code='config' and t.`key`='config_logo' GROUP BY s.store_id ORDER BY p.viewed DESC";

        if (isset($data['limit'])) {
            $sql .= " LIMIT 0, ".(int)$data['limit'];
        } else {
            $sql .= " LIMIT 0, 20";
        }

        $query = $this->db->query($sql);

        //$store_data = $query->rows;
        foreach ($query->rows as $store) {
            $store_info = $this->getStore($store['store_id']);
            $store_data[] = array_merge($store_info, $store);
        }

        return $store_data;
    }

	public function getTotalStores() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");

		return $query->row['total'];
	}

	public function getTotalStoresByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByLanguage($language) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCurrency($currency) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByInformationId($information_id) {
		$account_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

    public function getStoreRatings($store_id = 0, $data = array()) {
        $sql = "SELECT AVG(rating_product) AS rating_product, AVG(rating_quality) AS rating_quality, AVG(rating_service) AS rating_service, AVG(rating_deliver) AS rating_deliver FROM " . DB_PREFIX . "store_review WHERE store_id = '" . (int)$store_id . "'";

        if (isset($data['filter_start_date'])) {
            $sql .= " AND date_added >= '".$this->db->escape($data['filter_start_date'])."'";
        } else {
            $date = date('Y-m-d H:i:s', strtotime('-90 day'));
            $sql .= " AND date_added >= '".$this->db->escape($date)."'";
        }

        $query = $this->db->query($sql);

        $data = array(
            'rating_product' => '5.0',
            'rating_quality' => '5.0',
            'rating_service' => '5.0',
            'rating_deliver' => '5.0',
            'rating_average' => '5.0'
        );

        $avg = 0;
        foreach ($query->row AS $_key => $rating) {
            $data[$_key] = $rating ? number_format($rating, 1) : '5.0';
            $avg += $rating ? $rating : 5;
        }

        if ($avg > 0) $data['rating_average'] = number_format($avg / 4, 1);

        return $data;
    }

    public function getAverageRatings($data = array()) {
        $sql = "SELECT AVG(rating_product) AS rating_product, AVG(rating_quality) AS rating_quality, AVG(rating_service) AS rating_service, AVG(rating_deliver) AS rating_deliver FROM " . DB_PREFIX . "store_review sr LEFT JOIN " . DB_PREFIX . "store s ON sr.store_id=s.store_id WHERE s.status = '1'";

        if (isset($data['filter_start_date'])) {
            $sql .= " AND sr.date_added >= '".$this->db->escape($data['filter_start_date'])."'";
        } else {
            $date = date('Y-m-d H:i:s', strtotime('-90 day'));
            $sql .= " AND sr.date_added >= '".$this->db->escape($date)."'";
        }

        $query = $this->db->query($sql);

        $data = array(
            'rating_product' => '5.0',
            'rating_quality' => '5.0',
            'rating_service' => '5.0',
            'rating_deliver' => '5.0',
            'rating_average' => '5.0'
        );

        $avg = 0;
        foreach ($query->row AS $_key => $rating) {
            $data[$_key] = $rating ? number_format($rating, 1) : '5.0';
            $avg += $rating ? $rating : 5;
        }

        if ($avg > 0) $data['rating_average'] = number_format($avg / 4, 1);

        return $data;
    }

    public function getStoreTotalScores($store_id = 0, $data = array()) {
        $score_ratings = array(
            'rating_product' => array(),
            'rating_quality' => array(),
            'rating_service' => array(),
            'rating_deliver' => array(),
        );

        foreach ($score_ratings as $key => $val) {
            $sql = "SELECT COUNT(*) AS total, ".$key." AS code FROM " . DB_PREFIX . "store_review WHERE store_id = '" . (int)$store_id ."'";
            if (isset($data['filter_start_date'])) {
                $sql .= " AND date_added >= '".$this->db->escape($data['filter_start_date'])."'";
            } else {
                $date = date('Y-m-d H:i:s', strtotime('-90 day'));
                $sql .= " AND date_added >= '".$this->db->escape($date)."'";
            }
            $sql .= " GROUP BY ".$key;

            $query = $this->db->query($sql);
            foreach ($query->rows as $row) {
                $score_ratings[$key][$row['code']] = $row['total'];
            }
            for ($i=1; $i<=5; $i++) {
                if (!isset($score_ratings[$key][$i])) $score_ratings[$key][$i] = 0;
            }
            krsort($score_ratings[$key]);
        }

        return $score_ratings;
    }

    public function getStoreTotalReviews($store_id = 0, $data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store_review WHERE store_id = '" . (int)$store_id . "'";

        if (isset($data['filter_start_date'])) {
            $sql .= " AND date_added >= '".$this->db->escape($data['filter_start_date'])."'";
        } else {
            $date = date('Y-m-d H:i:s', strtotime('-90 day'));
            $sql .= " AND date_added >= '".$this->db->escape($date)."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}