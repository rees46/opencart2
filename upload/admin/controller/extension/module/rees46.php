<?php
class ControllerExtensionModuleRees46 extends Controller {
	private $version = '2.5.3';
	private $error = array();

	public function index() {
		$this->load->language('extension/module/rees46');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/module');
		$this->load->model('catalog/manufacturer');
		$this->load->model('localisation/language');
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/currency');

		if ($this->request->server['HTTPS']) {
			$site_url = HTTPS_CATALOG;
		} else {
			$site_url = HTTP_CATALOG;
		}

        if (!$this->config->get('rees46_action_lead') || $this->config->get('rees46_action_lead') == null) {
            $this->load->model('user/user');

            $user_info = $this->model_user_user->getUser($this->user->getId());

			$this->load->model('localisation/country');

			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

            $params = array(
                'website' => $site_url,
                'cms_version' => VERSION,
                'module_version' => $this->version,
                'email' => $this->config->get('config_email'),
                'first_name' => $user_info['firstname'],
                'last_name' => $user_info['lastname'],
                'phone' => $this->config->get('config_telephone'),
                'city' => $this->config->get('config_address'),
                'country' => $country['name'],
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_URL, 'https://rees46.com/trackcms/opencart?' . http_build_query($params));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            curl_close($ch);

            $rees46_action_lead = 1;
        }

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

			if ((!$this->config->get('rees46_xml_exported') || $this->config->get('rees46_xml_exported') == null) && $this->request->post['setting']['rees46_store_key'] != '' && $this->request->post['setting']['rees46_secret_key'] != '') {
				$params['store_key'] = $this->request->post['setting']['rees46_store_key'];
				$params['store_secret'] = $this->request->post['setting']['rees46_secret_key'];
				$params['yml_file_url'] = $site_url . 'index.php?route=tool/rees46';

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_URL, 'https://rees46.com/api/shop/set_yml');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params, true));
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$return['result'] = curl_exec($ch);
				$return['info'] = curl_getinfo($ch);

				curl_close($ch);

				if ($return['info']['http_code'] >= 200 && $return['info']['http_code'] < 300) {
					$this->request->post['setting']['rees46_xml_exported'] = 1;
				}
			}

			if (isset($rees46_action_lead)) {
				$this->request->post['setting']['rees46_action_lead'] = $rees46_action_lead;
			}

			$this->model_setting_setting->editSetting('rees46', $this->request->post['setting']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		} elseif (isset($rees46_action_lead)) {
			$this->model_setting_setting->editSetting('rees46', array('rees46_action_lead' => 1));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_products'] = $this->language->get('tab_products');
		$data['tab_orders'] = $this->language->get('tab_orders');
		$data['tab_customers'] = $this->language->get('tab_customers');
		$data['tab_webpush'] = $this->language->get('tab_webpush');
		$data['tab_modules'] = $this->language->get('tab_modules');
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
		$data['text_help'] = $this->language->get('text_help');
		$data['text_type_interesting'] = $this->language->get('text_type_interesting');
		$data['text_type_also_bought'] = $this->language->get('text_type_also_bought');
		$data['text_type_similar'] = $this->language->get('text_type_similar');
		$data['text_type_popular'] = $this->language->get('text_type_popular');
		$data['text_type_see_also'] = $this->language->get('text_type_see_also');
		$data['text_type_recently_viewed'] = $this->language->get('text_type_recently_viewed');
		$data['text_type_buying_now'] = $this->language->get('text_type_buying_now');
		$data['text_type_search'] = $this->language->get('text_type_search');
		$data['text_type_supply'] = $this->language->get('text_type_supply');
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
		$data['entry_store_key'] = $this->language->get('entry_store_key');
		$data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_log'] = $this->language->get('entry_log');
		$data['entry_export_orders'] = $this->language->get('entry_export_orders');
		$data['entry_status_created'] = $this->language->get('entry_status_created');
		$data['entry_status_completed'] = $this->language->get('entry_status_completed');
		$data['entry_status_cancelled'] = $this->language->get('entry_status_cancelled');
		$data['entry_export_customers'] = $this->language->get('entry_export_customers');
		$data['entry_export_type'] = $this->language->get('entry_export_type');
		$data['entry_webpush_files'] = $this->language->get('entry_webpush_files');
		$data['entry_xml_currency'] = $this->language->get('entry_xml_currency');
		$data['entry_xml_cron'] = $this->language->get('entry_xml_cron');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_brands'] = $this->language->get('entry_brands');
		$data['entry_exclude_brands'] = $this->language->get('entry_exclude_brands');
		$data['entry_block_status'] = $this->language->get('entry_block_status');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/rees46', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/rees46', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/rees46', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/rees46', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['setting']['rees46_action_lead'])) {
			$data['rees46_action_lead'] = $this->request->post['setting']['rees46_action_lead'];
		} elseif ($this->config->get('rees46_action_lead')) {
			$data['rees46_action_lead'] = $this->config->get('rees46_action_lead');
		} else {
			$data['rees46_action_lead'] = null;
		}

		if (isset($this->request->post['setting']['rees46_xml_exported'])) {
			$data['rees46_xml_exported'] = $this->request->post['setting']['rees46_xml_exported'];
		} elseif ($this->config->get('rees46_xml_exported')) {
			$data['rees46_xml_exported'] = $this->config->get('rees46_xml_exported');
		} else {
			$data['rees46_xml_exported'] = null;
		}

		if (isset($this->request->post['setting']['rees46_store_key'])) {
			$data['rees46_store_key'] = $this->request->post['setting']['rees46_store_key'];
		} else {
			$data['rees46_store_key'] = $this->config->get('rees46_store_key');
		}

		if (isset($this->request->post['setting']['rees46_secret_key'])) {
			$data['rees46_secret_key'] = $this->request->post['setting']['rees46_secret_key'];
		} else {
			$data['rees46_secret_key'] = $this->config->get('rees46_secret_key');
		}

		if (isset($this->request->post['setting']['rees46_tracking_status'])) {
			$data['rees46_tracking_status'] = $this->request->post['setting']['rees46_tracking_status'];
		} else {
			$data['rees46_tracking_status'] = $this->config->get('rees46_tracking_status');
		}

		if (isset($this->request->post['setting']['rees46_log'])) {
			$data['rees46_log'] = $this->request->post['setting']['rees46_log'];
		} else {
			$data['rees46_log'] = $this->config->get('rees46_log');
		}

		if (isset($this->request->post['setting']['rees46_status_created'])) {
			$data['rees46_status_created'] = $this->request->post['setting']['rees46_status_created'];
		} elseif ($this->config->get('rees46_status_created')) {
			$data['rees46_status_created'] = $this->config->get('rees46_status_created');
		} else {
			$data['rees46_status_created'] = array();
		}

		if (isset($this->request->post['setting']['rees46_status_completed'])) {
			$data['rees46_status_completed'] = $this->request->post['setting']['rees46_status_completed'];
		} elseif ($this->config->get('rees46_status_completed')) {
			$data['rees46_status_completed'] = $this->config->get('rees46_status_completed');
		} else {
			$data['rees46_status_completed'] = array();
		}

		if (isset($this->request->post['setting']['rees46_status_cancelled'])) {
			$data['rees46_status_cancelled'] = $this->request->post['setting']['rees46_status_cancelled'];
		} elseif ($this->config->get('rees46_status_cancelled')) {
			$data['rees46_status_cancelled'] = $this->config->get('rees46_status_cancelled');
		} else {
			$data['rees46_status_cancelled'] = array();
		}

		if (isset($this->request->post['setting']['rees46_customers'])) {
			$data['rees46_customers'] = $this->request->post['setting']['rees46_customers'];
		} else {
			$data['rees46_customers'] = $this->config->get('rees46_customers');
		}

		if (isset($this->request->post['setting']['rees46_xml_status'])) {
			$data['rees46_xml_status'] = $this->request->post['setting']['rees46_xml_status'];
		} else {
			$data['rees46_xml_status'] = $this->config->get('rees46_xml_status');
		}

		if (isset($this->request->post['setting']['rees46_xml_currency'])) {
			$data['rees46_xml_currency'] = $this->request->post['setting']['rees46_xml_currency'];
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

		$data['cron'] = $site_url . 'index.php?route=tool/rees46_cron';
		$data['token'] = $this->session->data['token'];
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/rees46', $data));
	}

	public function export() {
		$this->load->language('extension/module/rees46');

		$this->load->model('extension/module/rees46');
		$this->load->model('catalog/product');
		$this->load->model('customer/customer');

		$json = array();

		if ($this->validate()) {
			$next = $this->request->post['next'];
			$limit = 1000;

			$filter_data = array(
				'start' => ($next - 1) * $limit,
				'limit' => $limit
			);

			if ($filter_data['start'] < 0) {
				$filter_data['start'] = 0;
			}

			if ($this->request->post['type'] == 'orders') {
				$results_total = $this->model_extension_module_rees46->getTotalOrders();

				$results = $this->model_extension_module_rees46->getOrders($filter_data);

				$data = array();

				if ($results) {
					foreach ($results as $result) {
						if (($this->config->get('rees46_status_created') && in_array($result['order_status_id'], $this->config->get('rees46_status_created'))) || ($this->config->get('rees46_status_completed') && in_array($result['order_status_id'], $this->config->get('rees46_status_completed'))) || ($this->config->get('rees46_status_cancelled') && in_array($result['order_status_id'], $this->config->get('rees46_status_cancelled')))) {
							$order_products = array();

							$products = $this->model_extension_module_rees46->getOrderProducts($result['order_id']);

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
			} elseif ($this->request->post['type'] == 'customers') {
				if (!$this->config->get('rees46_customers')) {
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
				$params['shop_id'] = $this->config->get('rees46_store_key');
				$params['shop_secret'] = $this->config->get('rees46_secret_key');

				if ($this->request->post['type'] == 'orders') {
					$params['orders'] = $data;

					$url = 'http://api.rees46.com/import/orders';
				} elseif ($this->request->post['type'] == 'customers') {
					$params['audience'] = $data;

					$url = 'http://api.rees46.com/import/audience';
				}

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params, true));

				$return['result'] = curl_exec($ch);
				$return['info'] = curl_getinfo($ch);

				curl_close($ch);

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
		$this->load->language('extension/module/rees46');

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

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/rees46')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/event');

		$this->model_extension_event->addEvent('rees46', 'catalog/model/checkout/order/addOrderHistory/before', 'extension/module/rees46/exportStatus');
	}

	public function uninstall() {
		$this->load->model('extension/event');

		$this->model_extension_event->deleteEvent('rees46');
	}
}
