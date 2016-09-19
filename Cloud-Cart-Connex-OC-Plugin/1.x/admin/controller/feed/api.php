<?php 
#####################################################################################
#  API for Opencart 1.5.x From HostJars http://opencart.hostjars.com    			#
#####################################################################################

class ControllerFeedApi extends Controller {
	public function index() {
		$this->load->language('feed/api');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('api', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

			$language_info = array(
			'heading_title',
			'text_enabled',
			'text_disabled',
			'entry_status',
			'entry_data_api',
			'entry_headings',
			'entry_cdata',
			'button_save',
			'button_cancel',
			'tab_general',
		);
		
		foreach ($language_info as $language) {
			$this->data[$language] = $this->language->get($language);
		}
		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_feed'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('feed/api', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('feed/api', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['api_status'])) {
			$this->data['api_status'] = $this->request->post['api_status'];
		} else {
			$this->data['api_status'] = $this->config->get('api_status');
		}
		
		$this->data['data_api'] = HTTP_CATALOG . 'index.php?route=feed/api';
		
		$this->template = 'feed/api.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render());
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'feed/api')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}	
}
?>