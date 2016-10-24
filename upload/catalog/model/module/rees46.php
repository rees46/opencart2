<?php
class ModelModuleRees46 extends Model {
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT order_id, customer_id, email, order_status_id, date_added FROM `" . DB_PREFIX . "order`");

		if ($query->num_rows) {
			return $query->row;
		} else {
			return;
		}
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT op.product_id, op.price, op.quantity, p.quantity AS stock FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) WHERE op.order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
}