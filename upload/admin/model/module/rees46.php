<?php
class ModelModuleRees46 extends Model {
	public function getOrders($data = array()) {
		$sql = "SELECT order_id, customer_id, email, order_status_id, date_added FROM `" . DB_PREFIX . "order` WHERE DATE(date_added) > DATE_SUB(NOW(), INTERVAL 6 MONTH)";

		$rees46_statuses = array();

		if ($this->config->get('rees46_status_created')) {
			$rees46_statuses = array_merge($rees46_statuses, $this->config->get('rees46_status_created'));
		}

		if ($this->config->get('rees46_status_completed')) {
			$rees46_statuses = array_merge($rees46_statuses, $this->config->get('rees46_status_completed'));
		}

		if ($this->config->get('rees46_status_cancelled')) {
			$rees46_statuses = array_merge($rees46_statuses, $this->config->get('rees46_status_cancelled'));
		}

		if (!empty($rees46_statuses)) {
			$implode = array();

			foreach ($rees46_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " AND (" . implode(" OR ", $implode) . ")";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " ORDER BY order_id ASC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		}
	}

	public function getTotalOrders() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE DATE(date_added) > DATE_SUB(NOW(), INTERVAL 6 MONTH)";

		$rees46_statuses = array();

		if ($this->config->get('rees46_status_created')) {
			$rees46_statuses = array_merge($rees46_statuses, $this->config->get('rees46_status_created'));
		}

		if ($this->config->get('rees46_status_completed')) {
			$rees46_statuses = array_merge($rees46_statuses, $this->config->get('rees46_status_completed'));
		}

		if ($this->config->get('rees46_status_cancelled')) {
			$rees46_statuses = array_merge($rees46_statuses, $this->config->get('rees46_status_cancelled'));
		}

		if (!empty($rees46_statuses)) {
			$implode = array();

			foreach ($rees46_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " AND (" . implode(" OR ", $implode) . ")";
			}

			$sql .= " ORDER BY order_id ASC";

			$query = $this->db->query($sql);

			return $query->row['total'];
		}
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT op.product_id, op.price, op.quantity, p.quantity AS stock FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) WHERE op.order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getAllCategories() {
		$query = $this->db->query("SELECT c.category_id, c.parent_id, cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}

	public function getProduct($prev_id) {
		$query = $this->db->query("SELECT p.product_id, p.quantity, p.price, p.tax_class_id, p.image, p.model, pd.name, pd.description, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id > '" . (int)$prev_id . "' AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.product_id ASC LIMIT 1");

		return $query->row;
	}
}