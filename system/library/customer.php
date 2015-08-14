<?php
class Customer {
	private $customer_id;
	private $fullname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $customer_group_id;
	private $address_id;
    private $shop_id;
    private $shop_key;
    private $shop_status = 0;
    private $custom_field = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id'])) {
			$customer_query = $this->db->query("SELECT c.*, s.store_id AS shop_id, s.key AS shop_key, s.status AS shop_status FROM " . DB_PREFIX . "customer AS c LEFT JOIN ".DB_PREFIX."store AS s ON c.customer_id=s.customer_id WHERE c.customer_id = '" . (int)$this->session->data['customer_id'] . "' AND c.status = '1'");

			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->fullname = $customer_query->row['fullname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
                $this->shop_id = $customer_query->row['shop_id'];
                $this->shop_key = $customer_query->row['shop_key'];
                $this->shop_status = $customer_query->row['shop_status'];
                $this->custom_field = unserialize($customer_query->row['custom_field']);

				$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {
		$id_field = false;
        if (preg_match('/^1[1-9]{1}[0-9]{9}$/i', $email)) {
            $id_field = 'c.telephone';
        } elseif ((utf8_strlen($email) <= 96) && preg_match('/^[^\@]+@[^\@]+.[a-z]{2,15}$/i', $email)) {
            $id_field = 'c.email';
        }

        if ($id_field === false) return false;

        if ($override) {
			$customer_query = $this->db->query("SELECT c.*, s.store_id AS shop_id, s.key AS shop_key FROM " . DB_PREFIX . "customer AS c LEFT JOIN ".DB_PREFIX."store AS s ON c.customer_id=s.customer_id WHERE LOWER(".$id_field.") = '" . $this->db->escape(utf8_strtolower($email)) . "' AND c.status = '1'");
		} else {
			$customer_query = $this->db->query("SELECT c.*, s.store_id AS shop_id, s.key AS shop_key FROM " . DB_PREFIX . "customer AS c LEFT JOIN ".DB_PREFIX."store AS s ON c.customer_id=s.customer_id WHERE LOWER(".$id_field.") = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (c.password = SHA1(CONCAT(c.salt, SHA1(CONCAT(c.salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND c.status = '1' AND c.approved = '1'");
		}

		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];

			if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
				$cart = unserialize($customer_query->row['cart']);

				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}
			}

			if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}

				$wishlist = unserialize($customer_query->row['wishlist']);

				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session->data['wishlist'])) {
						$this->session->data['wishlist'][] = $product_id;
					}
				}
			}

			$this->customer_id = $customer_query->row['customer_id'];
			$this->fullname = $customer_query->row['fullname'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
            $this->address_id = $customer_query->row['address_id'];
            $this->shop_id = $customer_query->row['shop_id'];
            $this->shop_key = $customer_query->row['shop_key'];

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

		unset($this->session->data['customer_id']);

		$this->customer_id = '';
		$this->fullname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->customer_id;
	}

    public function isSeller() {
        return $this->shop_id && $this->shop_status;
    }

    public function isActive() {
        return $this->shop_status;
    }

    public function getShopId() {
        return $this->shop_id;
    }

    public function getShopKey() {
        return $this->shop_key;
    }

	public function getId() {
		return $this->customer_id;
	}

	public function getFullName() {
		return $this->fullname;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getFax() {
		return $this->fax;
	}

	public function getNewsletter() {
		return $this->newsletter;
	}

	public function getGroupId() {
		return $this->customer_group_id;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}

    public function getAvatar() {
        return isset($this->custom_field[2]) ? $this->custom_field[2] : '';
    }
}