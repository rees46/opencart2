<?php
class ControllerExtensionModuleRees46 extends Controller {
	public function index($setting) {
		if ($this->config->get('rees46_tracking_status')) {
			$data['module_id'] = $setting['module_id'];
			$data['type'] = $setting['type'];

			if ($setting['template'] == 'rees46_basic') {
				$data['css'] = true;
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
				$params['limit'] = 4;
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

				if (isset($cart)) {
					$params['cart'] = $cart;
				}

				$data['params'] = json_encode($params, true);
			} elseif ($data['type'] == 'also_bought') {
				if (isset($item)) {
					$params['item'] = $item;

					if (isset($cart)) {
						$params['cart'] = $cart;
					}

					$data['params'] = json_encode($params, true);
				}
			} elseif ($data['type'] == 'similar') {
				if (isset($item) && isset($cart)) {
					$params['item'] = $item;
					$params['cart'] = $cart;

					$data['params'] = json_encode($params, true);
				}
			} elseif ($data['type'] == 'popular') {
				if (isset($category)) {
					$params['category'] = $category;
				}

				if (isset($cart)) {
					$params['cart'] = $cart;
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
			} elseif ($data['type'] == 'supply') {
				if (isset($item)) {
					$params['item'] = $item;
				}

				if (isset($cart)) {
					$params['cart'] = $cart;
				}

				$data['params'] = json_encode($params, true);
			}

			if (isset($data['params'])) {
				return $this->load->view('extension/module/rees46', $data);
			}
		}
	}

	public function getProducts() {
		if (isset($this->request->get['module_id']) && isset($this->request->get['product_ids'])) {
			$this->load->language('extension/module/rees46');

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

						if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$price = false;
						}

						if ((float)$product_info['special']) {
							$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$special = false;
						}

						if ($this->config->get('config_tax')) {
							$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
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
							'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
							'price'       => $price,
							'special'     => $special,
							'tax'         => $tax,
							'rating'      => $rating,
							'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'] . '&recommended_by=' . $setting['type'])
						);
					} else {
						$url = 'http://api.rees46.com/import/disable';

						$params['shop_id'] = $this->config->get('rees46_store_key');
						$params['shop_secret'] = $this->config->get('rees46_secret_key');
						$params['item_ids'] = $product_id;

						$return = $this->curl($url, json_encode($params, true));

						if ($return['info']['http_code'] < 200 || $return['info']['http_code'] >= 300) {
							if ($this->config->get('rees46_log')) {
								$this->log->write('REES46 [error]: Error exclude of recomended product_id [' . $product_id . '] [' . $return['info']['http_code'] . ']');
							}
						} else {
							if ($this->config->get('rees46_log')) {
								$this->log->write('REES46 [success]: Excluded of recomended product_id [' . $product_id . ']');
							}
						}
					}
				}
			}

			if (!empty($data['products'])) {
				$this->response->setOutput($this->load->view('extension/module/' . $setting['template'], $data));
			}
		}
	}

	public function exportStatus($route, $order_id, $order_status_id) {
		if ($this->config->get('rees46_tracking_status')) {
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

				$params['shop_id'] = $this->config->get('rees46_store_key');
				$params['shop_secret'] = $this->config->get('rees46_secret_key');
				$params['orders'] = $data;

				$url = 'http://api.rees46.com/import/sync_orders';

				$return = $this->curl($url, json_encode($params, true));

				if ($return['info']['http_code'] < 200 || $return['info']['http_code'] >= 300) {
					if ($this->config->get('rees46_log')) {
						$this->log->write('REES46 [error]: Error autoexport status [' . $order_status_id . '] of order_id [' . $order_id . '] [' . $return['info']['http_code'] . ']');
					}
				} else {
					if ($this->config->get('rees46_log')) {
						$this->log->write('REES46 [success]: Autoexport status [' . $order_status_id . '] of order_id [' . $order_id . ']');
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