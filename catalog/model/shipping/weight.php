<?php
class ModelShippingWeight extends Model {
	public function getQuote($address, $store_id = false) {
        if ($store_id === false) $store_id = $this->config->get('config_store_id');

        $this->load->model('seller/setting');

        $_settings = $this->model_seller_setting->getSetting('weight', $store_id);

		$this->load->language('shipping/weight');

		$quote_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");

		foreach ($query->rows as $result) {
			if (isset($_settings['weight_' . $result['geo_zone_id'] . '_status']) && $_settings['weight_' . $result['geo_zone_id'] . '_status']) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}

			if ($status) {
				//$cost = '';
				$weight = $this->cart->getShopWeight($store_id);

                foreach (array('express', 'postage', 'ems') as $code) {
                    $firstWeight = $_settings['weight_' . $code . '_' . $result['geo_zone_id'] . '_first_weight'];
                    $firstPrice = $_settings['weight_' . $code . '_' . $result['geo_zone_id'] . '_first_price'];
                    $nextWeight = $_settings['weight_' . $code . '_' . $result['geo_zone_id'] . '_next_weight'];
                    $nextPrice = $_settings['weight_' . $code . '_' . $result['geo_zone_id'] . '_next_price'];

                    if (!$firstWeight || !$firstPrice || !$nextWeight || !$nextPrice) continue;

                    $cost = false;
                    if ($firstWeight >= $weight) {
                        $cost = $firstPrice;
                    } else {
                        $weight_add = $weight - $firstWeight;
                        $cost = $firstPrice + (ceil($weight_add / $nextWeight) * $nextPrice);
                    }

                    if ($cost !== false) {
                        $quote_data['weight_' . $code . '_' . $result['geo_zone_id']] = array(
                            'code'         => 'weight.weight_' . $code . '_' . $result['geo_zone_id'],
                            'title'        => $this->language->get('text_'.$code) . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
                            'cost'         => $cost,
                            'tax_class_id' => $_settings['weight_tax_class_id'],
                            'text'         => $this->currency->format($this->tax->calculate($cost, $_settings['weight_tax_class_id'], $this->config->get('config_tax')))
                        );
                    }
                }

				/*$rates = explode(',', $this->config->get('weight_' . $result['geo_zone_id'] . '_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((string)$cost != '') {
					$quote_data['weight_' . $result['geo_zone_id']] = array(
						'code'         => 'weight.weight_' . $result['geo_zone_id'],
						'title'        => $result['name'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('weight_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('weight_tax_class_id'), $this->config->get('config_tax')))
					);
				}*/
			}
		}

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'weight',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $_settings['weight_sort_order'],
				'error'      => false
			);
		}

		return $method_data;
	}
}