<?php
class ControllerModuleRees46 extends Controller {
	private $error = array();
	private $xml_name = 'rees46.xml';

	public function index() {
		if (version_compare(VERSION, '2.2', '<')) {
			$ssl = 'SSL';
		} else {
			$ssl = true;
		}

		$this->load->language('module/rees46');

		$this->load->model('setting/setting');
		$this->load->model('extension/module');
		$this->load->model('catalog/manufacturer');
		$this->load->model('localisation/language');
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/currency');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!empty($this->request->post['module'])) {
				foreach ($this->request->post['module'] as $key => $module) {
					if (!isset($module['module_id'])) {
						$module_data = $this->request->post['module'][$key];

						$module_data['module_id'] = $this->model_extension_module->addModule('rees46', $module_data);

						$this->model_extension_module->editModule($module_data['module_id'], $module_data);
					} else {
						$this->model_extension_module->editModule($module['module_id'], $this->request->post['module'][$key]);
					}
				}
			}

			if (!empty($this->request->post['delete'])) {
				foreach ($this->request->post['delete'] as $delete) {
					$this->model_extension_module->deleteModule($delete);
				}
			}

			if ($this->request->post['setting']['rees46_xml_status'] == 0 && is_file(DIR_DOWNLOAD . $this->xml_name)) {
				unlink(DIR_DOWNLOAD . $this->xml_name);
			}

			$this->model_setting_setting->editSetting('rees46', $this->request->post['setting']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], $ssl));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_xml'] = $this->language->get('tab_xml');
		$data['tab_orders'] = $this->language->get('tab_orders');
		$data['tab_subscribers'] = $this->language->get('tab_subscribers');
		$data['tab_webpush'] = $this->language->get('tab_webpush');
		$data['tab_modules'] = $this->language->get('tab_modules');
		$data['tab_help'] = $this->language->get('tab_help');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_export'] = $this->language->get('button_export');
		$data['button_check'] = $this->language->get('button_check');
		$data['button_generate'] = $this->language->get('button_generate');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_module'] = $this->language->get('text_tab_module');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_documentation_url'] = $this->language->get('text_documentation_url');
		$data['text_type_interesting'] = $this->language->get('text_type_interesting');
		$data['text_type_also_bought'] = $this->language->get('text_type_also_bought');
		$data['text_type_similar'] = $this->language->get('text_type_similar');
		$data['text_type_popular'] = $this->language->get('text_type_popular');
		$data['text_type_see_also'] = $this->language->get('text_type_see_also');
		$data['text_type_recently_viewed'] = $this->language->get('text_type_recently_viewed');
		$data['text_type_buying_now'] = $this->language->get('text_type_buying_now');
		$data['text_type_search'] = $this->language->get('text_type_search');
		$data['text_template_default'] = $this->language->get('text_template_default');
		$data['text_template_basic'] = $this->language->get('text_template_basic');
		$data['text_template_bestseller'] = $this->language->get('text_template_bestseller');
		$data['text_template_featured'] = $this->language->get('text_template_featured');
		$data['text_template_latest'] = $this->language->get('text_template_latest');
		$data['text_template_special'] = $this->language->get('text_template_special');
		$data['text_autocomplete'] = $this->language->get('text_autocomplete');
		$data['text_subscribers'] = $this->language->get('text_subscribers');
		$data['text_customers'] = $this->language->get('text_customers');
		$data['text_info_1'] = $this->language->get('text_info_1');
		$data['text_info_2'] = $this->language->get('text_info_2');
		$data['text_info_3'] = $this->language->get('text_info_3');
		$data['text_info_4'] = $this->language->get('text_info_4');
		$data['entry_shop_id'] = $this->language->get('entry_shop_id');
		$data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_log'] = $this->language->get('entry_log');
		$data['entry_export_orders'] = $this->language->get('entry_export_orders');
		$data['entry_status_created'] = $this->language->get('entry_status_created');
		$data['entry_status_completed'] = $this->language->get('entry_status_completed');
		$data['entry_status_cancelled'] = $this->language->get('entry_status_cancelled');
		$data['entry_export_subscribers'] = $this->language->get('entry_export_subscribers');
		$data['entry_export_type'] = $this->language->get('entry_export_type');
		$data['entry_webpush_files'] = $this->language->get('entry_webpush_files');
		$data['entry_xml_status'] = $this->language->get('entry_xml_status');
		$data['entry_xml_currency'] = $this->language->get('entry_xml_currency');
		$data['entry_xml_generate'] = $this->language->get('entry_xml_generate');
		$data['entry_xml_url'] = $this->language->get('entry_xml_url');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_css'] = $this->language->get('entry_css');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_brands'] = $this->language->get('entry_brands');
		$data['entry_exclude_brands'] = $this->language->get('entry_exclude_brands');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], $ssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], $ssl)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => strip_tags($this->language->get('heading_title')),
				'href' => $this->url->link('module/rees46', 'token=' . $this->session->data['token'], $ssl)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => strip_tags($this->language->get('heading_title')),
				'href' => $this->url->link('module/rees46', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], $ssl)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/rees46', 'token=' . $this->session->data['token'], $ssl);
		} else {
			$data['action'] = $this->url->link('module/rees46', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], $ssl);
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], $ssl);

		if (isset($this->request->post['rees46_shop_id'])) {
			$data['rees46_shop_id'] = $this->request->post['rees46_shop_id'];
		} else {
			$data['rees46_shop_id'] = $this->config->get('rees46_shop_id');
		}

		if (isset($this->request->post['rees46_secret_key'])) {
			$data['rees46_secret_key'] = $this->request->post['rees46_secret_key'];
		} else {
			$data['rees46_secret_key'] = $this->config->get('rees46_secret_key');
		}

		if (isset($this->request->post['rees46_tracking_status'])) {
			$data['rees46_tracking_status'] = $this->request->post['rees46_tracking_status'];
		} else {
			$data['rees46_tracking_status'] = $this->config->get('rees46_tracking_status');
		}

		if (isset($this->request->post['rees46_log'])) {
			$data['rees46_log'] = $this->request->post['rees46_log'];
		} else {
			$data['rees46_log'] = $this->config->get('rees46_log');
		}

		if (isset($this->request->post['rees46_status_created'])) {
			$data['rees46_status_created'] = $this->request->post['rees46_status_created'];
		} elseif ($this->config->get('rees46_status_created')) {
			$data['rees46_status_created'] = $this->config->get('rees46_status_created');
		} else {
			$data['rees46_status_created'] = array();
		}

		if (isset($this->request->post['rees46_status_completed'])) {
			$data['rees46_status_completed'] = $this->request->post['rees46_status_completed'];
		} elseif ($this->config->get('rees46_status_completed')) {
			$data['rees46_status_completed'] = $this->config->get('rees46_status_completed');
		} else {
			$data['rees46_status_completed'] = array();
		}

		if (isset($this->request->post['rees46_status_cancelled'])) {
			$data['rees46_status_cancelled'] = $this->request->post['rees46_status_cancelled'];
		} elseif ($this->config->get('rees46_status_cancelled')) {
			$data['rees46_status_cancelled'] = $this->config->get('rees46_status_cancelled');
		} else {
			$data['rees46_status_cancelled'] = array();
		}

		if (isset($this->request->post['rees46_subscribers'])) {
			$data['rees46_subscribers'] = $this->request->post['rees46_subscribers'];
		} else {
			$data['rees46_subscribers'] = $this->config->get('rees46_subscribers');
		}

		if (isset($this->request->post['rees46_xml_status'])) {
			$data['rees46_xml_status'] = $this->request->post['rees46_xml_status'];
		} else {
			$data['rees46_xml_status'] = $this->config->get('rees46_xml_status');
		}

		if (isset($this->request->post['rees46_xml_currency'])) {
			$data['rees46_xml_currency'] = $this->request->post['rees46_xml_currency'];
		} elseif ($this->config->get('rees46_xml_currency')) {
			$data['rees46_xml_currency'] = $this->config->get('rees46_xml_currency');
		} else {
			$data['rees46_xml_currency'] = $this->config->get('config_currency');
		}

		if (isset($this->request->get['module_id'])) {
			$data['module_id'] = $this->request->get['module_id'];
		}

		$data['modules'] = array();

		$modules = $this->model_extension_module->getModulesByCode('rees46');

		if (!empty($modules)) {
			foreach ($modules as $module) {
				$setting = json_decode($module['setting'], true);

				$manufacturers = array();

				if (!empty($setting['manufacturers'])) {
					foreach ($setting['manufacturers'] as $manufacturer_id) {
						$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

						if ($manufacturer_info) {
							$manufacturers[] = array(
								'manufacturer_id' => $manufacturer_info['manufacturer_id'],
								'name'            => $manufacturer_info['name']
							);
						}
					}
				}

				$manufacturers_exclude = array();

				if (!empty($setting['manufacturers_exclude'])) {
					foreach ($setting['manufacturers_exclude'] as $manufacturer_id) {
						$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

						if ($manufacturer_info) {
							$manufacturers_exclude[] = array(
								'manufacturer_id' => $manufacturer_info['manufacturer_id'],
								'name'            => $manufacturer_info['name']
							);
						}
					}
				}

				$data['modules'][] = array(
					'module_id'             => $module['module_id'],
					'name'                  => $module['name'],
					'setting'               => $setting,
					'manufacturers'         => $manufacturers,
					'manufacturers_exclude' => $manufacturers_exclude
				);

				$setting = '';
			}
		}

		sort($data['modules']);

		$data['module_row'] = 1;

		if (count($data['modules']) + 1 > $data['module_row']) {
			$data['module_row'] = count($data['modules']) + 1;
		}

		if ($this->request->server['HTTPS']) {
			$data['xml_url'] = HTTPS_CATALOG . 'index.php?route=tool/rees46';
		} else {
			$data['xml_url'] = HTTP_CATALOG . 'index.php?route=tool/rees46';
		}

		$data['token'] = $this->session->data['token'];
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (version_compare(VERSION, '2.2', '<')) {
			foreach ($data['languages'] as $key => $language) {
				$data['languages'][$key]['image'] = 'view/image/flags/' . $language['image'];
			}
		} else {
			foreach ($data['languages'] as $key => $language) {
				$data['languages'][$key]['image'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if (version_compare(VERSION, '2.2', '<')) {
			$this->response->setOutput($this->load->view('module/rees46.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('module/rees46', $data));
		}
	}

	public function export() {
		$this->load->language('module/rees46');

		$this->load->model('module/rees46');
		$this->load->model('catalog/product');
		$this->load->model('customer/customer');

		$json = array();

		if ($this->validate()) {
			$next = $this->request->post['next'];
			$limit = 100;

			$filter_data = array(
				'start' => ($next - 1) * $limit,
				'limit' => $limit
			);

			if ($filter_data['start'] < 0) {
				$filter_data['start'] = 0;
			}

			if ($this->request->post['type'] == 'orders') {
				$results_total = $this->model_module_rees46->getTotalOrders();

				$results = $this->model_module_rees46->getOrders($filter_data);

				$data = array();

				if ($results) {
					foreach ($results as $result) {
						if (($this->config->get('rees46_status_created') && in_array($result['order_status_id'], $this->config->get('rees46_status_created'))) || ($this->config->get('rees46_status_completed') && in_array($result['order_status_id'], $this->config->get('rees46_status_completed'))) || ($this->config->get('rees46_status_cancelled') && in_array($result['order_status_id'], $this->config->get('rees46_status_cancelled')))) {
							$order_products = array();

							$products = $this->model_module_rees46->getOrderProducts($result['order_id']);

							foreach ($products as $product) {
								$categories = array();

								$categories = $this->model_catalog_product->getProductCategories($product['product_id']);

								$order_products[] = array(
									'id'           => $product['product_id'],
									'price'        => $product['price'],
									'categories'   => $categories,
									'is_available' => $product['stock'],
									'amount'       => $product['quantity']
								);
							}

							$data[] = array(
								'id'         => $result['order_id'],
								'user_id'    => $result['customer_id'],
								'user_email' => $result['email'],
								'date'       => strtotime($result['date_added']),
								'items'      => $order_products
							);
						}
					}
				}
			} elseif ($this->request->post['type'] == 'subscribers') {
				if (!$this->config->get('rees46_subscribers')) {
					$filter_data['filter_newsletter'] = 1;
				}

				$results_total = $this->model_customer_customer->getTotalCustomers($filter_data);

				$results = $this->model_customer_customer->getCustomers($filter_data);

				$data = array();

				if ($results) {
					foreach ($results as $result) {
						$data[] = array(
							'id'    => $result['customer_id'],
							'email' => $result['email']
						);
					}
				}
			}

			if (!empty($data)) {
				$params['shop_id'] = $this->config->get('rees46_shop_id');
				$params['shop_secret'] = $this->config->get('rees46_secret_key');

				if ($this->request->post['type'] == 'orders') {
					$params['orders'] = $data;

					$url = 'http://api.rees46.com/import/orders';
				} elseif ($this->request->post['type'] == 'subscribers') {
					$params['audience'] = $data;

					$url = 'http://api.rees46.com/import/audience';
				}

				$return = $this->curl($url, json_encode($params, true));

				if ($return['info']['http_code'] < 200 || $return['info']['http_code'] >= 300) {
					$json['error'] = $this->language->get('text_error_export');

					if ($this->config->get('rees46_log')) {
						$this->log->write('REES46 [error]: Export ' . $this->request->post['type'] . ' [' . $return['info']['http_code'] . ']');
					}
				} else {
					if ($results_total > $next * $limit) {
						$json['next'] = $next + 1;

						$json['success'] = sprintf($this->language->get('text_processing_' . $this->request->post['type']), $next * $limit ? $results_total : 0, $results_total);
					} else {
						$json['success'] = sprintf($this->language->get('text_success_' . $this->request->post['type']), $results_total);

						if ($this->config->get('rees46_log')) {
							$this->log->write('REES46 [success]: Export ' . $this->request->post['type'] . ' [' . $results_total . ']');
						}
					}
				}
			} else {
				$json['error'] = $this->language->get('text_error_export');
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function startCheck() {
		$this->load->language('module/rees46');

		$json = array();

		if ($this->validate()) {
			$dir = str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/';

			$files = array(
				'manifest.json',
				'push_sw.js'
			);

			foreach ($files as $key => $file) {
				if (!is_file($dir . $file)) {
					$ch = curl_init();

					curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/rees46/web-push-files/master/' . $file);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$result = curl_exec($ch);
					$info = curl_getinfo($ch);

					curl_close($ch);

					if ($info['http_code'] < 200 || $info['http_code'] >= 300) {
						if ($this->config->get('rees46_log')) {
							$this->log->write('REES46 [error]: Not loading file ' . $file . ' [' . $info['http_code'] . ']');
						}
					} else {
						file_put_contents($dir . $file, $result);

						if ($this->config->get('rees46_log')) {
							$this->log->write('REES46 [success]: Loading file ' . $file);
						}
					}
				}

				if (is_file($dir . $file)) {
					$json['success_loaded'][$key] = sprintf($this->language->get('text_success_check'), $file);
				} else {
					$json['error_loaded'][$key] = sprintf($this->language->get('text_error_check'), $file);
				}
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function generateShop() {
		$this->load->language('module/rees46');

		$json = array();

		if ($this->validate() && isset($this->request->post['type']) && isset($this->request->post['next']) && $this->config->get('rees46_xml_status')) {
			if ($this->request->server['HTTPS']) {
				$url = HTTPS_CATALOG;
			} else {
				$url = HTTP_CATALOG;
			}

			$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
			$xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n";
			$xml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . "\n";
			$xml .= '  <shop>' . "\n";
			$xml .= '    <name>' . $this->config->get('config_name') . '</name>' . "\n";
			$xml .= '    <company>' . $this->config->get('config_owner') . '</company>' . "\n";
			$xml .= '    <url>' . $url . '</url>' . "\n";
			$xml .= '    <platform>OpenCart</platform>' . "\n";
			$xml .= '    <version>' . VERSION . '</version>' . "\n";

			$this->recorder($xml, 'w');

			if (is_file(DIR_DOWNLOAD . $this->xml_name)) {
				$json['success'] = $this->language->get('text_success_' . $this->request->post['type']);
				$json['type'] = 'currencies';
				$json['next'] = '0';
			} else {
				$json['error'] = $this->language->get('text_error_' . $this->request->post['type']);
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function generateCurrencies() {
		$this->load->language('module/rees46');

		$this->load->model('localisation/currency');

		$json = array();

		if ($this->validate() && isset($this->request->post['type']) && isset($this->request->post['next'])) {
			$allowed_currencies = array('RUR', 'RUB', 'UAH', 'BYN', 'KZT', 'USD', 'EUR');
			$main_currencies = array('RUR', 'RUB', 'UAH', 'BYN', 'KZT');

			//if (in_array($this->config->get('config_currency'), $main_currencies)) {
				$xml = '    <currencies>';
				$xml .= "\n" . '      <currency id="' . $this->config->get('config_currency') . '" rate="1"/>';

				foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
					if ($currency['code'] != $this->config->get('config_currency') && $currency['status'] == 1 && in_array($currency['code'], $allowed_currencies)) {
						$xml .= "\n" . '      <currency id="' . $currency['code'] . '" rate="' . number_format(1 / $currency['value'], 4, '.', '') . '"/>';
					}
				}

				$xml .= "\n" . '    </currencies>' . "\n";

				$this->recorder($xml, 'a');

				if (is_file(DIR_DOWNLOAD . $this->xml_name)) {
					$json['success'] = $this->language->get('text_success_' . $this->request->post['type']);
					$json['type'] = 'categories';
					$json['next'] = '0';
				} else {
					$json['error'] = $this->language->get('text_error_' . $this->request->post['type']);
				}
			//} else {
			//	$json['error'] = $this->language->get('text_error_currency_code');
			//}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function generateCategories() {
		$this->load->language('module/rees46');

		$this->load->model('module/rees46');

		$json = array();

		if ($this->validate() && isset($this->request->post['type']) && isset($this->request->post['next'])) {
			$categories = $this->model_module_rees46->getAllCategories();

			if (!empty($categories)) {
				$xml = '    <categories>';

				foreach ($categories as $category) {
					if ($category['parent_id']) {
						$parent = ' parentId="' . $category['parent_id'] . '"';
					} else {
						$parent = '';
					}

					$xml .= "\n" . '      <category id="' . $category['category_id'] . '"' . $parent . '>' . $this->replacer($category['name']) . '</category>';
				}

				$xml .= "\n" . '    </categories>' . "\n";

				$this->recorder($xml, 'a');

				if (is_file(DIR_DOWNLOAD . $this->xml_name)) {
					$json['success'] = $this->language->get('text_success_' . $this->request->post['type']);
					$json['type'] = 'offers';
					$json['next'] = '0';
				} else {
					$json['error'] = $this->language->get('text_error_' . $this->request->post['type']);
				}
			} else {
				$json['success'] = $this->language->get('text_success_' . $this->request->post['type']);
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function generateOffers() {
		$this->load->language('module/rees46');

		$this->load->model('module/rees46');
		$this->load->model('tool/image');
		$this->load->model('catalog/product');

		$json = array();

		if ($this->validate() && isset($this->request->post['type']) && isset($this->request->post['next'])) {
			if ($this->request->post['next'] == 0) {
				$xml = '    <offers>' . "\n";
			} else {
				$xml = '';
			}

			$product = $this->model_module_rees46->getProduct($this->request->post['next']);

			if (!empty($product)) {
				$json['type'] = 'offers';
				$json['next'] = $product['product_id'];

				$xml .= '      <offer id="' . $product['product_id'] . '" available="' . ($product['quantity'] > 0 ? 'true' : 'false') . '">' . "\n";

				if ($this->request->server['HTTPS']) {
					$xml .= '        <url>' . $this->replacer(HTTPS_CATALOG . 'index.php?route=product/product&product_id=' . $product['product_id']) . '</url>' . "\n";
				} else {
					$xml .= '        <url>' . $this->replacer(HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $product['product_id']) . '</url>' . "\n";
				}

				if ($product['special'] && $product['price'] > $product['special']) {
					$xml .= '        <price>' . number_format($this->currency->convert($product['special'], $this->config->get('config_currency'), $this->config->get('rees46_xml_currency')), 2, '.', '') . '</price>' . "\n";
					$xml .= '        <oldprice>' . number_format($this->currency->convert($product['price'], $this->config->get('config_currency'), $this->config->get('rees46_xml_currency')), 2, '.', '') . '</oldprice>' . "\n";
				} else {
					$xml .= '        <price>' . number_format($this->currency->convert($product['price'], $this->config->get('config_currency'), $this->config->get('rees46_xml_currency')), 2, '.', '') . '</price>' . "\n";
				}

				$xml .= '        <currencyId>' . $this->config->get('rees46_xml_currency') . '</currencyId>' . "\n";

				$categories = $this->model_catalog_product->getProductCategories($product['product_id']);

				if (!empty($categories)) {
					foreach ($categories as $category) {
						$xml .= '        <categoryId>' . $category . '</categoryId>' . "\n";
					}
				}

				if ($product['image']) {
					$xml .= '        <picture>' . $this->model_tool_image->resize($product['image'], 600, 600) . '</picture>' . "\n";
				}

				$xml .= '        <name>' . $this->replacer($product['name']) . '</name>' . "\n";

				if ($product['manufacturer']) {
					$xml .= '        <vendor>' . $this->replacer($product['manufacturer']) . '</vendor>' . "\n";
				}

				$xml .= '        <model>' . $this->replacer($product['model']) . '</model>' . "\n";
				$xml .= '        <description><![CDATA[' . strip_tags(htmlspecialchars_decode($product['description']), '<h3>, <ul>, <li>, <p>, <br>') . ']]></description>' . "\n";
				$xml .= '      </offer>' . "\n";
			} else {
				$xml .= '    </offers>' . "\n";
				$xml .= '  </shop>' . "\n";
				$xml .= '</yml_catalog>';
			}

			$this->recorder($xml, 'a');

			if (is_file(DIR_DOWNLOAD . $this->xml_name)) {
				if (!empty($product)) {
					$json['success'] = sprintf($this->language->get('text_success_' . $this->request->post['type']), $product['product_id']);
				} else {
					$json['success'] = $this->language->get('text_success_xml');
				}
			} else {
				$json['error'] = sprintf($this->language->get('text_error_' . $this->request->post['type']), $product['product_id']);
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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

	protected function replacer($str) {
		return trim(str_replace('&#039;', '&apos;', htmlspecialchars(htmlspecialchars_decode($str, ENT_QUOTES), ENT_QUOTES)));
	}

	protected function recorder($xml, $mode) {
		if (!$fp = fopen(DIR_DOWNLOAD . $this->xml_name, $mode)) {
			if ($this->config->get('rees46_log')) {
				$this->log->write('REES46 [error]: Could not open XML file');
			}
		} elseif (fwrite($fp, $xml) === false) {
			if ($this->config->get('rees46_log')) {
				$this->log->write('REES46 [error]: XML file not writable');
			}
		}

		fclose($fp);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/rees46')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/event');

		if (version_compare(VERSION, '2.2', '<')) {
			$this->model_extension_event->addEvent('rees46', 'post.order.add', 'module/rees46/exportOrder');
			$this->model_extension_event->addEvent('rees46', 'pre.order.history.add', 'module/rees46/exportStatus');
		} else {
			$this->model_extension_event->addEvent('rees46', 'catalog/model/checkout/order/addOrder/after', 'module/rees46/exportOrder');
			$this->model_extension_event->addEvent('rees46', 'catalog/model/checkout/order/addOrderHistory/before', 'module/rees46/exportStatus');
		}
	}

	public function uninstall() {
		$this->load->model('extension/event');

		$this->model_extension_event->deleteEvent('rees46');
	}
}