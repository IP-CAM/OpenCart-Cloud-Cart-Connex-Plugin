<?php 
#####################################################################################
#  API for Opencart 2.x For Cloud Cart Connector / Connex for QuickBooks    			#
#####################################################################################
class ControllerFeedApi extends Controller {

	private $email ;

	public function index() {
		if ($this->config->get('api_status')) {
			
			$output = '';
			$email = 'REPLACE';
			if($this->request->server['REQUEST_METHOD'] == 'GET') {
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
				}
			}
			elseif ($this->request->server['REQUEST_METHOD'] == 'POST')	{
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
				}
			}
			elseif($this->request->server['REQUEST_METHOD'] == 'PUT') {
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
				}
			}
			elseif($this->request->server['REQUEST_METHOD'] == 'DELETE') {
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					if (!isset($this->request->get['param1'])) {
						$output = "DELETE functions require \"param1\"";
					}
					else {
						$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
					}
				}
			}
			
			$this->response->setOutput($output);
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

	// example function - getProducts loads the products model and calls its getProducts function.
	//private function getProducts() {
		//if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			//return '';
		//}
		//$this->load->model('catalog/product');
		//return $this->model_catalog_product->getProducts();
	//}
  
	// example function - getCustomers from the database
	//private function getCustomers() {
	//	if ($this->request->server['REQUEST_METHOD'] != 'GET') {
	//		return '';
	//	}
	//	$this->load->model('feed/api');
	//	return $this->model_feed_api->getCustomers();
	//}
  
	// example function - getOrders from the database
	private function getOrders() {
		if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}
		
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->getOrders($this->request->get['beginDate'], $this->request->get['endDate']);
	}
  
  	// example function - getOrders from the database
	private function getProducts() {
    if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->getProducts($this->request->get['beginDate'], $this->request->get['endDate'], $this->request->get['skip'], $this->request->get['limit']);

}
	
	// example function - getOrders from the database
	private function getOrderProducts() {
		
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->getOrderProducts($this->request->get['orderid']);
	}
	
	private function getOrderTotals() {
		
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->getOrderTotals($this->request->get['orderid']);
	}
  
  private function createProduct(){
    if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->createProduct($this->request->get['product_name'], $this->request->get['product_sku'],$this->request->get['product_model'], $this->request->get['product_quantity'],$this->request->get['product_description']);

  }
	
	private function getOrdersByNumberRange() {
		
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->getOrdersByNumberRange($this->request->get['lowest'], $this->request->get['highest']);
	}
	
	//"DELETE" FUNCTIONS:

	// example function - deleteProduct loads the model and calls its deleteProduct function.
	/*private function deleteProduct($prodid) {
		//		if ($this->request->server['REQUEST_METHOD'] != 'DELETE') {
		//			return '';
		//		}
		$this->load->model('feed/api');
		return $this->model_feed_api->deleteProduct($this->request->get['param1']);
	}*/
	
	//"PUT" FUNCTIONS:
	
	private function updateInventory() {
		
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		$this->load->model('feed/api');
		return $this->model_feed_api->updateInventory($this->request->get['sku'], $this->request->get['quantity']);
	}
	
	private function updateOrderStatus() {
		
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		$this->load->model('feed/api');
		return $this->model_feed_api->updateOrderStatus($this->request->get['order_id'], $this->request->get['status'], $this->request->get['comment']);
	}
	
	private function Authenticate(){
		if($this->request->get['email'] != "REPLACE")
		{
			return "INVALID EMAIL";
		}
		
		return "OK";
	}
	

}
?>