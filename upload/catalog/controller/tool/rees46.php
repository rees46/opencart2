<?php
class ControllerToolRees46 extends Controller {
	private $xml = '';
	private $prev = 0;

	public function index() {
		if ($this->config->get('rees46_xml_status')) {
			$this->generateShop();
			$this->generateCurrencies();
			$this->generateCategories();

			while (isset($this->prev)) {
				$this->generateOffers();
			}

			$this->xml .= '    </offers>' . "\n";
			$this->xml .= '  </shop>' . "\n";
			$this->xml .= '</yml_catalog>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($this->xml);
		} else {
			$this->load->language('error/not_found');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			if (isset($this->request->get['route'])) {
				$url_data = $this->request->get;

				unset($url_data['_route_']);

				$route = $url_data['route'];

				unset($url_data['route']);

				$url = '';

				if ($url_data) {
					$url = '&' . urldecode(http_build_query($url_data, '', '&'));
				}

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link($route, $url, $this->request->server['HTTPS'])
				);
			}

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_error'] = $this->language->get('text_error');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('default/template/error/not_found', $data));
		}
	}

	protected function generateShop() {
		if ($this->request->server['HTTPS']) {
			$url = HTTPS_SERVER;
		} else {
			$url = HTTP_SERVER;
		}

		$this->xml .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$this->xml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n";
		$this->xml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . "\n";
		$this->xml .= '  <shop>' . "\n";
		$this->xml .= '    <name>' . $this->config->get('config_name') . '</name>' . "\n";
		$this->xml .= '    <company>' . $this->config->get('config_owner') . '</company>' . "\n";
		$this->xml .= '    <url>' . $url . '</url>' . "\n";
		$this->xml .= '    <platform>OpenCart</platform>' . "\n";
		$this->xml .= '    <version>' . VERSION . '</version>' . "\n";
	}

	protected function generateCurrencies() {
		$this->load->model('localisation/currency');

		$allowed_currencies = array('RUR', 'RUB', 'UAH', 'BYN', 'KZT', 'USD', 'EUR');
		$main_currencies = array('RUR', 'RUB', 'UAH', 'BYN', 'KZT');

		$this->xml .= '    <currencies>';
		$this->xml .= "\n" . '      <currency id="' . $this->config->get('config_currency') . '" rate="1"/>';

		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			if ($currency['code'] != $this->config->get('config_currency') && $currency['status'] == 1 && in_array($currency['code'], $allowed_currencies)) {
				$this->xml .= "\n" . '      <currency id="' . $currency['code'] . '" rate="' . number_format(1 / $currency['value'], 4, '.', '') . '"/>';
			}
		}

		$this->xml .= "\n" . '    </currencies>' . "\n";
	}

	protected function generateCategories() {
		$this->load->model('extension/module/rees46');

		$categories = $this->model_extension_module_rees46->getAllCategories();

		if (!empty($categories)) {
			$this->xml .= '    <categories>';

			foreach ($categories as $category) {
				if ($category['parent_id']) {
					$parent = ' parentId="' . $category['parent_id'] . '"';
				} else {
					$parent = '';
				}

				$this->xml .= "\n" . '      <category id="' . $category['category_id'] . '"' . $parent . '>' . $this->replacer($category['name']) . '</category>';
			}

			$this->xml .= "\n" . '    </categories>' . "\n";
		}
	}

	protected function generateOffers() {
		$this->load->model('extension/module/rees46');
		$this->load->model('tool/image');

		if ($this->prev == 0) {
			$this->xml .= '    <offers>' . "\n";
		}

		$product = $this->model_extension_module_rees46->getProduct($this->prev);

		if (!empty($product)) {
			$this->prev = $product['product_id'];

			$this->xml .= '      <offer id="' . $product['product_id'] . '" available="' . ($product['quantity'] > 0 ? 'true' : 'false') . '">' . "\n";

			if ($this->request->server['HTTPS']) {
				$this->xml .= '        <url>' . $this->replacer($this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</url>' . "\n";
			} else {
				$this->xml .= '        <url>' . $this->replacer($this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</url>' . "\n";
			}

			if ($product['special'] && $product['price'] > $product['special']) {
				$this->xml .= '        <price>' . number_format($this->currency->convert($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'), $this->config->get('rees46_xml_currency')), 2, '.', '') . '</price>' . "\n";
				$this->xml .= '        <oldprice>' . number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'), $this->config->get('rees46_xml_currency')), 2, '.', '') . '</oldprice>' . "\n";
			} else {
				$this->xml .= '        <price>' . number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'), $this->config->get('rees46_xml_currency')), 2, '.', '') . '</price>' . "\n";
			}

			$this->xml .= '        <currencyId>' . $this->config->get('rees46_xml_currency') . '</currencyId>' . "\n";

			$categories = $this->model_extension_module_rees46->getProductCategories($product['product_id']);

			if (!empty($categories)) {
				foreach ($categories as $category) {
					$this->xml .= '        <categoryId>' . $category . '</categoryId>' . "\n";
				}
			}

			if ($product['image']) {
				$this->xml .= '        <picture>' . $this->model_tool_image->resize($product['image'], 600, 600) . '</picture>' . "\n";
			}

			$this->xml .= '        <name>' . $this->replacer($product['name']) . '</name>' . "\n";

			if ($product['manufacturer']) {
				$this->xml .= '        <vendor>' . $this->replacer($product['manufacturer']) . '</vendor>' . "\n";
			}

			$this->xml .= '        <model>' . $this->replacer($product['model']) . '</model>' . "\n";
			$this->xml .= '        <description><![CDATA[' . strip_tags(htmlspecialchars_decode($product['description']), '<h3>, <ul>, <li>, <p>, <br>') . ']]></description>' . "\n";
			$this->xml .= '      </offer>' . "\n";
		} else {
			unset($this->prev);
		}
	}

	protected function replacer($str) {
		return trim(str_replace('&#039;', '&apos;', htmlspecialchars(htmlspecialchars_decode($str, ENT_QUOTES), ENT_QUOTES)));
	}
}