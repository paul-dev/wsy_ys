<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}

    public function getCity($city_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_city WHERE id = '" . (int)$city_id . "' AND status = '1'");

        return $query->row;
    }

    public function getCitysByZoneCode($zone_code) {
        $city_data = $this->cache->get('city.' . $zone_code);

        if (!$city_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_city WHERE province_code = '" . $this->db->escape($zone_code) . "' AND status = '1' ORDER BY code");

            $city_data = $query->rows;

            $this->cache->set('city.' . $zone_code, $city_data);
        }

        return $city_data;
    }

    public function getAreasByCityCode($city_code) {
        $area_data = $this->cache->get('area.' . $city_code);

        if (!$area_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_area WHERE city_code = '" . $this->db->escape($city_code) . "' AND status = '1' ORDER BY code");

            $area_data = $query->rows;

            $this->cache->set('area.' . $city_code, $area_data);
        }

        return $area_data;
    }
}