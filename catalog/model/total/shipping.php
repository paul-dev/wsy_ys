<?php
class ModelTotalShipping extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method']) && isset($this->session->data['shop_shipping_method'])) {
			$total_data[] = array(
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['title'],
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('shipping_sort_order')
			);

            //shop sub shipping
            $sub_total = $this->cart->getShopSubTotal();
            foreach ($sub_total as $shop_id => $val) {
                if (!isset($this->session->data['shop_shipping_method'][$shop_id]) || $this->session->data['shop_shipping_method'][$shop_id]['code'] == 'none.none')
                    continue;
                $total_data[] = array(
                    'code'       => 'shipping',
                    'title'      => $this->session->data['shop_shipping_method'][$shop_id]['title'],
                    'value'      => $this->session->data['shop_shipping_method'][$shop_id]['cost'],
                    'sort_order' => $this->config->get('shipping_sort_order'),
                    'shop_id'    => $shop_id
                );
            }

			if ($this->session->data['shipping_method']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->session->data['shipping_method']['cost'];
		}
	}
}