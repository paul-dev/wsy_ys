<?php
class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		$this->event->trigger('pre.review.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET order_id = '".(int)$data['order_id']."', author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', order_product_id = '" . (int)$data['order_product_id'] . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', status = '1', date_added = NOW()");

		$review_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "store_review SET store_id = '".(int)$data['store_id']."', order_id = '".(int)$data['order_id']."', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', rating_product = '" . (int)$data['rating_product'] . "', rating_quality = '" . (int)$data['rating_quality'] . "', rating_service = '" . (int)$data['rating_service'] . "', rating_deliver = '" . (int)$data['rating_deliver'] . "', status = 1, date_added = NOW()");

        if ($this->config->get('config_review_mail')) {
			$this->load->language('mail/review');
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_product'), $this->db->escape(strip_tags($product_info['name']))) . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), $this->db->escape(strip_tags($data['name']))) . "\n";
			$message .= sprintf($this->language->get('text_rating'), $this->db->escape(strip_tags($data['rating']))) . "\n";
			$message .= $this->language->get('text_review') . "\n";
			$message .= $this->db->escape(strip_tags($data['text'])) . "\n\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.review.add', $review_id);
	}

    public function getReviewsByStoreId($store_id, $start = 0, $limit = 20, $filter = array()) {
        $sql = "SELECT r.*, op.name, op.model, c.custom_field FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "order_product op ON r.order_product_id = op.order_product_id LEFT JOIN " . DB_PREFIX . "customer c ON r.customer_id = c.customer_id LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (r.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$store_id . "' AND r.status = '1'";

        if (isset($filter['filter_rating']) && !empty($filter['filter_rating'])) {
            $sql .= " AND r.rating = '" . (int)$filter['filter_rating'] . "'";
        }

        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $sql .= " ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalReviewsByStoreId($store_id, $filter = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (r.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$store_id . "' AND r.status = '1'";

        if (isset($filter['filter_rating']) && !empty($filter['filter_rating'])) {
            $sql .= " AND r.rating = '" . (int)$filter['filter_rating'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20, $filter = array()) {
		$sql = "SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added, c.custom_field FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "customer c ON r.customer_id = c.customer_id LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (isset($filter['filter_rating']) && !empty($filter['filter_rating'])) {
            $sql .= " AND r.rating = '" . (int)$filter['filter_rating'] . "'";
        }

        if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

        $sql .= " ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id, $filter = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        if (isset($filter['filter_rating']) && !empty($filter['filter_rating'])) {
            $sql .= " AND r.rating = '" . (int)$filter['filter_rating'] . "'";
        }
        $query = $this->db->query($sql);

		return $query->row['total'];
	}
}