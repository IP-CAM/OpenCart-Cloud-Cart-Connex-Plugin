<?php
#####################################################################################
#  API for Opencart 2.0 -2.2 For Cloud Cart Connector and Connex for QuickBooks    		#
#####################################################################################
class ControllerFeedConnexQb extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('feed/connex_qb');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('connex_qb', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_connex_qb_status'] = $this->language->get('entry_connex_qb_status');
		$data['entry_connex_qb_username'] = $this->language->get('entry_connex_qb_username');
		$data['entry_connex_qb_password'] = $this->language->get('entry_connex_qb_password');
		$data['entry_connex_qb_url'] = $this->language->get('entry_connex_qb_url');

		$data['help_connex_qb_username'] = $this->language->get('help_connex_qb_username');
		$data['help_connex_qb_password'] = $this->language->get('help_connex_qb_password');
		$data['help_connex_qb_url'] = $this->language->get('help_connex_qb_url');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_success'] = $this->language->get('text_success');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_feed'),
			'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('feed/connex_qb', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('feed/connex_qb', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['connex_qb_status'])) {
			$data['connex_qb_status'] = $this->request->post['connex_qb_status'];
		} else {
			$data['connex_qb_status'] = $this->config->get('connex_qb_status');
		}

		if (isset($this->request->post['connex_qb_username'])) {
			$data['connex_qb_username'] = $this->request->post['connex_qb_username'];
		} else {
			$data['connex_qb_username'] = $this->config->get('connex_qb_username');
		}

		if (isset($this->request->post['connex_qb_password'])) {
			$data['connex_qb_password'] = $this->request->post['connex_qb_password'];
		} elseif ($this->config->get('connex_qb_password')) {
			$data['connex_qb_password'] = $this->config->get('connex_qb_password');
		} else {
			$data['connex_qb_password'] = token(25);
		}

		$data['connex_qb_url'] = HTTP_CATALOG . 'index.php?route=feed/connex_qb&func=Authenticate';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/connex_qb.tpl', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'feed/connex_qb')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


}
