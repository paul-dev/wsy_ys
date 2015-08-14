<?php
class ModelAccountChat extends Model {
	public function addMessage($data = array()) {
		$query = $this->db->query("INSERT INTO `" . DB_PREFIX . "live_chat` SET from_id = '" . (int)$data['from_id'] . "', to_id = '" . (int)$data['to_id'] . "', text = '".$this->db->escape($data['text'])."', date_added = NOW()");

        $msg_id = $this->db->getLastId();

		return $msg_id;
	}

    public function getMessages($customer_id) {
        $query = $this->db->query("SELECT m.*, c.fullname, c.custom_field FROM `" . DB_PREFIX . "live_chat` m LEFT JOIN `" . DB_PREFIX . "customer` c ON m.from_id = c.customer_id WHERE m.to_id = '" . (int)$customer_id . "' AND m.status = 0 ORDER BY m.date_added, m.msg_id LIMIT 5");

        return $query->rows;
    }

    public function getHistoryUsers($customer_id) {
        $user_from = array();
        $query = $this->db->query("SELECT DISTINCT m.from_id, m.to_id, c.fullname, c.custom_field FROM `" . DB_PREFIX . "live_chat` m LEFT JOIN `" . DB_PREFIX . "customer` c ON m.from_id = c.customer_id WHERE m.to_id = '" . (int)$customer_id . "' AND m.from_id != m.to_id AND m.status = 1 AND m.to_delete = 0 ORDER BY m.date_added DESC");

        foreach ($query->rows as $row) {
            $user_from[$row['from_id']] = $row;
        }

        $query = $this->db->query("SELECT DISTINCT m.to_id, m.from_id, c.fullname, c.custom_field FROM `" . DB_PREFIX . "live_chat` m LEFT JOIN `" . DB_PREFIX . "customer` c ON m.to_id = c.customer_id WHERE m.from_id = '" . (int)$customer_id . "' AND m.from_id != m.to_id AND m.from_delete = 0 ORDER BY m.date_added DESC");

        foreach ($query->rows as $row) {
            if (!array_key_exists($row['to_id'], $user_from)) $user_from[$row['to_id']] = $row;
        }

        return $user_from;
    }

    public function getHistoryMessages($user_id) {
        $sql = "SELECT m.* FROM `" . DB_PREFIX . "live_chat` m WHERE (m.from_id = '" . (int)$this->customer->getId() . "' AND m.to_id = '".(int)$user_id."' AND m.from_delete = '0') OR (m.from_id = '".(int)$user_id."' AND m.to_id = '".(int)$this->customer->getId()."' AND m.to_delete = '0' AND m.status = '1')";

        $sql .= " ORDER BY m.date_added DESC, m.msg_id DESC LIMIT 6";
        //echo $sql.'<br/>';
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function updateMessagesStatus($msgIds = array()) {
        if (is_array($msgIds) && !empty($msgIds)) {
            $this->db->query("UPDATE `" . DB_PREFIX . "live_chat` SET status = 1 WHERE msg_id IN (".join(',', $msgIds).")");
        }

        return;
    }
}