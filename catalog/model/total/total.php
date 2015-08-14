<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/total');

		$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => max(0, $total),
			'sort_order' => $this->config->get('total_sort_order')
		);

        // total shop
        $_shopTotal = array();
        foreach ($total_data as $_total) {
            if ($_total['code'] == 'total' || !array_key_exists('shop_id', $_total)) continue;
            if (array_key_exists((string)$_total['shop_id'], $_shopTotal)) {
                $_shopTotal[(string)$_total['shop_id']] += $_total['value'];
            } else {
                $_shopTotal[(string)$_total['shop_id']] = $_total['value'];
            }
        }

        foreach ($_shopTotal as $shop_id => $val) {
            $total_data[] = array(
                'code'       => 'total',
                'title'      => $this->language->get('text_total'),
                'value'      => max(0, $val),
                'sort_order' => $this->config->get('total_sort_order'),
                'shop_id'    => $shop_id
            );
        }
	}
}