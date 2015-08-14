<?php
class ModelDesignBanner extends Model {
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image bi LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) WHERE bi.banner_id = '" . (int)$banner_id . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC");

		return $query->rows;
	}

    public function getShopBannerImages($store_id = 0) {
        $banner_id = 0;
        $banner_image_data = array();

        $banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "' AND store_id = '".(int)$store_id."' ORDER BY sort_order ASC");

        foreach ($banner_image_query->rows as $banner_image) {
            $banner_image_description_data = array();

            $banner_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");

            foreach ($banner_image_description_query->rows as $banner_image_description) {
                $banner_image_description_data[$banner_image_description['language_id']] = array('title' => $banner_image_description['title']);
            }

            $banner_image_data[] = array(
                'title'                    => $banner_image_description_data[$this->config->get('config_language_id')]['title'],
                'banner_image_description' => $banner_image_description_data,
                'link'                     => $banner_image['link'],
                'image'                    => $banner_image['image'],
                'sort_order'               => $banner_image['sort_order']
            );
        }

        return $banner_image_data;
    }

    public function editShopBannerImages($data) {
        //$this->event->trigger('pre.admin.banner.edit', $data);

        //$this->db->query("UPDATE " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id in (SELECT banner_image_id FROM " . DB_PREFIX . "banner_image WHERE banner_id = '0' AND store_id='".(int)$this->customer->getShopId()."')");
        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '0' AND store_id='".(int)$this->customer->getShopId()."'");

        if (isset($data['banner_image'])) {
            foreach ($data['banner_image'] as $banner_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '0', store_id='".(int)$this->customer->getShopId()."', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");

                $banner_image_id = $this->db->getLastId();

                foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '0', title = '" .  $this->db->escape($banner_image_description['title']) . "'");
                }
            }
        }

        //$this->event->trigger('post.admin.banner.edit', $banner_id);

        $this->db->query("DELETE FROM " . DB_PREFIX . "shop_block WHERE store_id='".(int)$this->customer->getShopId()."'");

        foreach ($data['block'] as $_block) {
            if (empty($_block['name'])) continue;
            $this->db->query("INSERT INTO " . DB_PREFIX . "shop_block SET store_id='".(int)$this->customer->getShopId()."', name='".$this->db->escape($_block['name'])."', link = '" .  $this->db->escape($_block['link']) . "', image = '" .  $this->db->escape($_block['image']) . "', sort_order = '" . (int)$_block['sort'] . "', category='".join(',', $_block['category'])."', filter='".join(',', $_block['filter'])."', `limit` = '".(int)$_block['limit']."', status='".(int)$_block['status']."'");
        }
    }

    public function getShopBlocks($store_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shop_block WHERE store_id = '".(int)$store_id."' ORDER BY sort_order ASC");
        return $query->rows;
    }
}