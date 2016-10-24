<?php
class ControllerModuleRees46 extends Controller {
	public function index($setting) {
		$data['module_id'] = $setting['module_id'];
		$data['type'] = $setting['type'];

		if ($setting['css']) {
			$data['css'] = $setting['css'];
		} else {
			$data['css'] = false;
		}

		if (isset($this->request->get['product_id'])) {
			$item = (int)$this->request->get['product_id'];
		}

		if (isset($this->request->get['path'])) {
			$categories = explode('_', (string)$this->request->get['path']);

			$category = (int)array_pop($categories);
		}

		if ($this->cart->hasProducts()) {
			foreach ($this->cart->getProducts() as $product) {
				$cart[] = $product['product_id'];
			}
		}

		if (isset($this->request->get['search'])) {
			$search_query = $this->request->get['search'];;
		}

		$params = array();

		if ($setting['limit'] > 0) {
			$params['limit'] = (int)$setting['limit'];
		} else {
			$params['limit'] = 6;
		}

		$params['discount'] = (int)$setting['discount'];

		if (!empty($setting['manufacturers']) || !empty($setting['manufacturers_exclude'])) {
			$this->load->model('catalog/manufacturer');
		}

		if (!empty($setting['manufacturers'])) {
			$params['brands'] = array();

			foreach ($setting['manufacturers'] as $manufacturer) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer);

				$params['brands'][] = $manufacturer_info['name'];
			}
		}

		if (!empty($setting['manufacturers_exclude'])) {
			$params['exclude_brands'] = array();

			foreach ($setting['manufacturers_exclude'] as $manufacturer) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer);

				$params['exclude_brands'][] = $manufacturer_info['name'];
			}
		}

		if ($data['type'] == 'interesting') {
			if (isset($item)) {
				$params['item'] = $item;
			}

			$data['params'] = json_encode($params, true);
		} elseif ($data['type'] == 'also_bought') {
			if (isset($item)) {
				$params['item'] = $item;

				$data['params'] = json_encode($params, true);
			}
		} elseif ($data['type'] == 'similar') {
			if (isset($item) && isset($cart)) {
				$params['item'] = $item;
				$params['cart'] = $cart;

				if (isset($categories)) {
					$params['categories'] = $categories;
				}

				$data['params'] = json_encode($params, true);
			}
		} elseif ($data['type'] == 'popular') {
			if (isset($category)) {
				$params['category'] = $category;
			}

			$data['params'] = json_encode($params, true);
		} elseif ($data['type'] == 'see_also') {
			if (isset($cart)) {
				$params['cart'] = $cart;

				$data['params'] = json_encode($params, true);
			}
		} elseif ($data['type'] == 'recently_viewed') {
			$data['params'] = json_encode($params, true);
		} elseif ($data['type'] == 'buying_now') {
			if (isset($item)) {
				$params['item'] = $item;
			}

			if (isset($cart)) {
				$params['cart'] = $cart;
			}

			$data['params'] = json_encode($params, true);
		} elseif ($data['type'] == 'search') {
			if (isset($search_query)) {
				$params['search_query'] = $search_query;

				if (isset($cart)) {
					$params['cart'] = $cart;
				}

				$data['params'] = json_encode($params, true);
			}
		}

		if (isset($data['params'])) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/rees46.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/rees46.tpl', $data);
			} else {
				return $this->load->view('default/template/module/rees46.tpl', $data);
			}
		}
	}

	public function getProducts() {
		if (isset($this->request->get['module_id']) && isset($this->request->get['product_ids'])) {
			$this->load->language('module/rees46');

			$this->load->model('extension/module');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');

			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_more'] = $this->language->get('text_more');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');

			$setting = $this->model_extension_module->getModule($this->request->get['module_id']);

			if ($setting['title'][$this->config->get('config_language_id')] != '') {
				$data['heading_title'] = html_entity_decode($setting['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
			} else {
				$data['heading_title'] = $this->language->get('text_type_' . $setting['type']);
			}

			if ($setting['width']) {
				$width = $setting['width'];
			} else {
				$width = 100;
			}

			if ($setting['height']) {
				$height = $setting['height'];
			} else {
				$height = 100;
			}

			$data['products'] = array();

			$product_ids = explode(',', $this->request->get['product_ids']);

			if (!empty($product_ids)) {
				foreach ($product_ids as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);

					if ($product_info && $product_info['quantity'] > 0) {
						if ($product_info['image']) {
							$image = $this->model_tool_image->resize($product_info['image'], $width, $height);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $width, $height);
						}

						if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
						} else {
							$price = false;
						}

						if ((float)$product_info['special']) {
							$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
						} else {
							$special = false;
						}

						if ($this->config->get('config_tax')) {
							$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
						} else {
							$tax = false;
						}

						if ($this->config->get('config_review_status')) {
							$rating = $product_info['rating'];
						} else {
							$rating = false;
						}

						$data['products'][] = array(
							'product_id'  => $product_info['product_id'],
							'thumb'       => $image,
							'name'        => $product_info['name'],
							'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       => $price,
							'special'     => $special,
							'tax'         => $tax,
							'rating'      => $rating,
							'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'] . '&recommended_by=' . $setting['type'])
						);
					} else {
						$url = 'http://api.rees46.com/import/disable';

						$params['shop_id'] = $this->config->get('rees46_shop_id');
						$params['shop_secret'] = $this->config->get('rees46_secret_key');
						$params['item_ids'] = $product_id;

						$return = $this->curl($url, json_encode($params, true));

						if ($return['info']['http_code'] < 200 || $return['info']['http_code'] >= 300) {
							if ($this->config->get('rees46_log')) {
								$this->log->write('REES46 log: error exclude of recomended product_id = ' . $product_id . ' [' . $return['info']['http_code'] . ']');
							}
						} else {
							if ($this->config->get('rees46_log')) {
								$this->log->write('REES46 log: success exclude of recomended product_id = ' . $product_id);
							}
						}
					}
				}
			}

			if (!empty($data['products'])) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/' . $setting['template'] . '.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/' . $setting['template'] . '.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/module/' . $setting['template'] . '.tpl', $data));
				}
			}
		}
	}

	public function exportOrder($route, $order_id = false) {
		if ($this->config->get('rees46_tracking_status')) {
			if (version_compare(VERSION, '2.2', '<')) {
				$order_id = $route;
			}

			$this->load->model('module/rees46');

			$result = $this->model_module_rees46->getOrder($order_id);

			if (($this->config->get('rees46_status_created') && in_array($result['order_status_id'], $this->config->get('rees46_status_created'))) || ($this->config->get('rees46_status_completed') && in_array($result['order_status_id'], $this->config->get('rees46_status_completed'))) || ($this->config->get('rees46_status_cancelled') && in_array($result['order_status_id'], $this->config->get('rees46_status_cancelled')))) {
				$order_products = array();

				$products = $this->model_module_rees46->getOrderProducts($order_id);

				foreach ($products as $product) {
					$categories = array();

					$categories = $this->model_module_rees46->getProductCategories($product['product_id']);

					$order_products[] = array(
						'id'           => $product['product_id'],
						'price'        => $product['price'],
						'categories'   => $categories,
						'is_available' => $product['stock'],
						'amount'       => $product['quantity']
					);
				}

				$data[] = array(
					'id'         => $order_id,
					'user_id'    => $result['customer_id'],
					'user_email' => $result['email'],
					'date'       => strtotime($result['date_added']),
					'items'      => $order_products
				);

				if (!empty($data)) {
					$params['shop_id'] = $this->config->get('rees46_shop_id');
					$params['shop_secret'] = $this->config->get('rees46_secret_key');
					$params['orders'] = $data;

					$url = 'http://api.rees46.com/import/orders';

					$return = $this->curl($url, json_encode($params, true));

					if ($return['info']['http_code'] < 200 || $return['info']['http_code'] >= 300) {
						if ($this->config->get('rees46_log')) {
							$this->log->write('REES46 log: error autoexport order_id = ' . $order_id . ' [' . $return['info']['http_code'] . ']');
						}
					} else {
						if ($this->config->get('rees46_log')) {
							$this->log->write('REES46 log: success autoexport order_id = ' . $order_id);
						}
					}
				}
			}
		}
	}

	public function exportStatus($route, $order_id = false, $order_status_id = false) {
		if ($this->config->get('rees46_tracking_status')) {
			if (version_compare(VERSION, '2.2', '<')) {
				$order_id = $route['order_id'];
				$order_status_id = $route['order_status_id'];
			}

			if ($this->config->get('rees46_status_created') && in_array($order_status_id, $this->config->get('rees46_status_created'))) {
				$status = 0;
			} elseif ($this->config->get('rees46_status_completed') && in_array($order_status_id, $this->config->get('rees46_status_completed'))) {
				$status = 1;
			} elseif ($this->config->get('rees46_status_cancelled') && in_array($order_status_id, $this->config->get('rees46_status_cancelled'))) {
				$status = 2;
			}

			if (isset($status)) {
				$data[] = array(
					'id'     => $order_id,
					'status' => $status
				);

				$params['shop_id'] = $this->config->get('rees46_shop_id');
				$params['shop_secret'] = $this->config->get('rees46_secret_key');
				$params['orders'] = $data;

				$url = 'http://api.rees46.com/import/sync_orders';

				$return = $this->curl($url, json_encode($params, true));

				if ($return['info']['http_code'] < 200 || $return['info']['http_code'] >= 300) {
					if ($this->config->get('rees46_log')) {
						$this->log->write('REES46 log: error autoexport status = ' . $order_status_id . ' of order_id = ' . $order_id . ' [' . $return['info']['http_code'] . ']');
					}
				} else {
					if ($this->config->get('rees46_log')) {
						$this->log->write('REES46 log: success autoexport status = ' . $order_status_id . ' of order_id = ' . $order_id);
					}
				}
			}
		}
	}

	protected function curl($url, $params) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		$data['result'] = curl_exec($ch);
		$data['info'] = curl_getinfo($ch);

		curl_close($ch);

		return $data;
	}
}