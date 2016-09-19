<?php
#####################################################################################
#  API for Opencart 2.0 - 2.2 For Cloud Cart Connector / Connex for QuickBooks  			#
#####################################################################################
class ControllerFeedConnexQb extends Controller {

	public function index() {

		//verify Connex for Quickbooks is enabled
		if ($this->config->get('connex_qb_status')) {

			//authenticate request
			$authenticated = $this->Authenticate();

			// verify user is authenticated
			if ($authenticated == '2') {

				$output = '';

				if($this->request->server['REQUEST_METHOD'] == 'GET') {
					if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
						$output = call_user_func(array($this, $this->request->get['func']));
					}
				} elseif ($this->request->server['REQUEST_METHOD'] == 'POST')	{
					if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
						$output = call_user_func(array($this, $this->request->get['func']));
					}
				} elseif($this->request->server['REQUEST_METHOD'] == 'PUT') {
					if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
						$output = call_user_func(array($this, $this->request->get['func']));
					}
				} elseif($this->request->server['REQUEST_METHOD'] == 'DELETE') {
					if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
						if (!isset($this->request->get['param1'])) {
							$output = 'DELETE functions require (param1)';
						} else {
							$output = call_user_func(array($this, $this->request->get['func']));
						}
					}
				}

				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($output));

			//authentication error
			} else {
				$error = '';
				$error .= $authenticated;
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($error));
			}
		} else {
			$this->response->setOutput('Connex for Quickbooks Feed Disabled');
		}
	}

	/**
	 *  WRITE YOUR DESIRED API FUNCTIONS HERE:
	 *
	 *  example calling function getProducts() as restful webservice:
	 *  http://www.example.com/index.php?route=feed/api&func=getProducts
	 *
	 */

	//"GET" FUNCTIONS:

	// get Orders
	private function getOrders() {

		if($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->getOrders($this->request->get['beginDate'], $this->request->get['endDate']);
	}

  	// get Products
	private function getProducts() {

		if($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->getProducts($this->request->get['beginDate'], $this->request->get['endDate'], $this->request->get['skip'], $this->request->get['limit']);
	}


	// get Order Products
	private function getOrderProducts() {

		//validate that this is a GET REQUEST
		if($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->getOrderProducts($this->request->get['orderid']);
	}


	//get Order Totals
	private function getOrderTotals() {

		//validate that this is a GET REQUEST
		if($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->getOrderTotals($this->request->get['orderid']);
	}


	//create a new product
	private function createProduct() {

		//validate that this is a GET REQUEST
		if($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->createProduct($this->request->get['product_name'], $this->request->get['product_sku'], $this->request->get['product_model'], $this->request->get['product_quantity'],$this->request->get['product_description']);

	}


	//get order by orderid ranges
	private function getOrdersByNumberRange() {

		//validate that this is a GET REQUEST
		if($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->getOrdersByNumberRange($this->request->get['lowest'], $this->request->get['highest']);
	}



	//"PUT" FUNCTIONS:

	//update product stock qty
	private function updateInventory() {

		//validate that this is a PUT REQUEST
		if($this->request->server['REQUEST_METHOD'] != 'PUT') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->updateInventory($this->request->get['sku'], $this->request->get['quantity']);
	}


	//update order status and add order history by status name
	private function updateOrderStatus() {

		//validate that this is a PUT REQUEST
		if($this->request->server['REQUEST_METHOD'] != 'PUT') {
			return '';
		}

		$this->load->model('feed/connex_qb');
		return $this->model_feed_connex_qb->updateOrderStatus($this->request->get['order_id'], $this->request->get['status'], $this->request->get['comment'], $this->request->get['notify'] ? $this->request->get['notify'] : false);
	}


	//Authenticate Connex Username, Password & Valid Function
	private function Authenticate() {

		$error_status = false;
		$error = 'Authentication Error: ';

		if(!isset($this->request->get['email']) || (urldecode($this->request->get['email']) != $this->config->get('connex_qb_username'))) {
			$error .= 'Invalid Connection Username';
			$error_status = true;
		} elseif (!isset($this->request->get['password']) || ($this->request->get['password'] != $this->config->get('connex_qb_password'))) {
			$error .= 'Invalid Connection Password';
			$error_status = true;
		} elseif (!isset($this->request->get['func']) || !is_callable(array($this, $this->request->get['func']))) {
			$error .= 'Invalid or No Function Requested e.g.(func=someFunction)';
			$error_status = true;
		}

		if($error_status) {
			return $error;
		}

		return '2';
	}


}
