<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
		$this->event->trigger('pre.customer.add.address', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$this->customer->getId() . "', fullname = '" . $this->db->escape($data['fullname']) . "', company = '" . $this->db->escape($data['company']) . "', address = '" . $this->db->escape($data['address']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', area_id = '" . (int)$data['area_id'] . "', city_id = '" . (int)$data['city_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "'");

		$address_id = $this->db->getLastId();
		
		//edit mcc
		$total_address = $this->getTotalAddresses();
		
		if($total_address == 1) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
		}else{

			if (!empty($data['default'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			}
		
		}
		//end mcc

		$this->event->trigger('post.customer.add.address', $address_id);

		return $address_id;
	}

	public function editAddress($address_id, $data) {
		$this->event->trigger('pre.customer.edit.address', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "address SET fullname = '" . $this->db->escape($data['fullname']) . "', company = '" . $this->db->escape($data['company']) . "', address = '" . $this->db->escape($data['address']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', area_id = '" . (int)$data['area_id'] . "', city_id = '" . (int)$data['city_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}

		$this->event->trigger('post.customer.edit.address', $address_id);
	}

	public function deleteAddress($address_id) {
		$this->event->trigger('pre.customer.delete.address', $address_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.delete.address', $address_id);
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT a.*, zc.name as city_name, zc.code as city_code, za.name as area_name, za.code as area_code FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "zone_city zc ON a.city_id = zc.id LEFT JOIN " . DB_PREFIX . "zone_area za ON a.area_id=za.id WHERE a.address_id = '" . (int)$address_id . "' AND a.customer_id = '" . (int)$this->customer->getId() . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'fullname'      => $address_query->row['fullname'],
				'company'        => $address_query->row['company'],
				'address'      => $address_query->row['address'],
				'postcode'       => $address_query->row['postcode'],
                'area_id'        => $address_query->row['area_id'],
                'area_name'      => $address_query->row['area_name'],
                'area_code'      => $address_query->row['area_code'],
                'city_id'        => $address_query->row['city_id'],
                'city_name'      => $address_query->row['city_name'],
                'city_code'      => $address_query->row['city_code'],
                'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query->row['custom_field'])
			);

			return $address_data;
		} else {
			return false;
		}
	}

	public function getAddresses() {
		$address_data = array();

		$query = $this->db->query("SELECT a.*, zc.name as city_name, zc.code as city_code, za.name as area_name, za.code as area_code FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "zone_city zc ON a.city_id = zc.id LEFT JOIN " . DB_PREFIX . "zone_area za ON a.area_id=za.id WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'fullname'      => $result['fullname'],
				'company'        => $result['company'],
				'address'      => $result['address'],
				'postcode'       => $result['postcode'],
                'area_id'        => $result['area_id'],
                'area_name'      => $result['area_name'],
                'area_code'      => $result['area_code'],
                'city_id'        => $result['city_id'],
                'city_name'      => $result['city_name'],
                'city_code'      => $result['city_code'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($result['custom_field'])

			);
		}

		return $address_data;
	}

	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}