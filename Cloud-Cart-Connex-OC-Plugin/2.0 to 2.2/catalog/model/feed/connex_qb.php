<?php
#####################################################################################
#  API for Opencart 2.0 - 2.2 for Cloud Cart Connector and Connex for QuickBooks     	    #
#####################################################################################


class ModelFeedConnexQb extends Model {

	/* You can put any DB calls you like in here to post/delete/put/get */


	//update the order status and add history to order
	public function updateOrderStatus($order_id, $status, $comment='', $notify = false, $override = false) {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$status_id = $this->getStatusIdByName($status);

		if ($order_info && $status_id) {
			$this->model_checkout_order->addOrderHistory($order_id, $status_id['order_status_id'], $comment, $notify, $override);
			return '1';
		}
		return '0';
	}


	//get order status by name
	public function getStatusIdByName($status_name) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE name = " . $this->db->escape($status_name) . "");
		return $query->row;
	}


	//get order totals, discounts, coupons and vouchers
	public function getOrderTotals($orderid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = " . $this->db->escape($orderid)."");
		return $query->rows;

	}


	//get products by date added
	public function getProducts($beginDate, $endDate, $skip, $limit) {
		$query = $this->db->query("SELECT p.*, pd.name, pd.description FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd on p.product_id = pd.product_id WHERE DATE(p.date_added) >= DATE('" . $this->db->escape($beginDate) . "') AND DATE(p.date_added) <= DATE('" . $this->db->escape($endDate) . "') LIMIT " . (int)$this->db->escape($skip) .", " . (int)$this->db->escape($limit)."");
		return $query->rows;
	}


	//get orders by date
	public function getOrders($beginDate, $endDate) {
			$query = $this->db->query("SELECT o.*, os.name as order_status_name, cg.name as order_customer_group, (SELECT ot.comment FROM " . DB_PREFIX . "order_history ot WHERE ot.order_status_id = 3 AND ot.order_id = o.order_id AND ot.comment !='' LIMIT 1) AS tracking_number FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cg ON (o.customer_group_id = cg.customer_group_id) WHERE (o.date_added >= '" . $this->db->escape($beginDate) . "' OR o.date_modified >= '" . $this->db->escape($beginDate) . "') AND (o.date_added <= '" . $this->db->escape($endDate) . "' OR o.date_modified <= '" . $this->db->escape($endDate) . "') AND o.order_status_id != 0");

			$results = [];

			//loop through orders to extract tracking data fields
			foreach($query->rows as $row){

				if($row['tracking_number']){
					$trackstring = $row['tracking_number'];

					$tnum = explode("Tracking No ", $trackstring)[1];
					$shipdate = explode(" via ", explode("shipped on ", $trackstring)[1])[0];
					$shipmethod = explode(". Tracking", explode(" via ", $trackstring)[1])[0];

					$row['tracking_number'] = $tnum;
					$row['ship_date'] = date("m/d/Y", strtotime(str_replace("/", "-", $shipdate)));
					$row['ship_method'] = $shipmethod;
				} else {
					$row['tracking_number'] = '';
					$row['ship_date'] = '';
					$row['ship_method'] = '';
				}

				array_push($results, $row);
			}

		return $results;
	}


	//get order products and gift certificate vouchers
	public function getOrderProducts($orderid) {
		//get order products
		$query = $this->db->query("SELECT op.*, p.*, oo.name as option_name, op.price as item_price, op.quantity as item_quantity, oo.value as option_value FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "order_option oo on (oo.order_product_id = op.order_product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = op.product_id) WHERE op.order_id = " . $this->db->escape($orderid) ."");
		$qr = $query->rows;
		//get order gift certificate vouchers
		$queryv = $this->db->query("SELECT ov.order_voucher_id as order_product_id, ov.order_id, ov.voucher_id as product_id, ov.description as name, ov.amount as item_price FROM " . DB_PREFIX . "order_voucher ov WHERE ov.order_id = " . $this->db->escape($orderid) ."");
		$qvr = $queryv->rows;

		//combine order products and order gift certificate vouchers
		foreach($qvr as $row) {
			if($row != ''){
				$row['sku'] = 'Gift Certificate';
				$row['model'] = 'Gift Certificate';
				$row['item_quantity'] = 1;
				$row['total'] = $row['item_price'];

				array_push($qr, $row);
			}
		} //end combine order products and order gift certificate vouchers

		return $qr;
	}


	//get specific orders by orderid range
	public function getOrdersByNumberRange($lowest, $highest) {
		$query = $this->db->query("SELECT o.*, os.name as order_status_name, cg.name as order_customer_group, (SELECT ot.comment FROM " . DB_PREFIX . "order_history ot WHERE ot.order_status_id = 3 AND ot.order_id = o.order_id AND ot.comment !='' LIMIT 1) AS tracking_number FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cg ON (o.customer_group_id = cg.customer_group_id) WHERE o.order_id >= " . $this->db->escape($lowest) ." AND o.order_id <= " . $this->db->escape($highest) ." AND o.order_status_id != 0");

		$results = [];

		//loop through orders to extract tracking data fields
		foreach($query->rows as $row){

			if($row['tracking_number']) {
				$trackstring = $row['tracking_number'];

				$tnum = explode("Tracking No ", $trackstring)[1];
				$shipdate = explode(" via ", explode("shipped on ", $trackstring)[1])[0];
				$shipmethod = explode(". Tracking", explode(" via ", $trackstring)[1])[0];

				$row['tracking_number'] = $tnum;
				$row['ship_date'] = date("m/d/Y", strtotime(str_replace("/", "-", $shipdate)));
				$row['ship_method'] = $shipmethod;
			} else {
				$row['tracking_number'] = '';
				$row['ship_date'] = '';
				$row['ship_method'] = '';
			}

			array_push($results, $row);
		}

		return $results;
	}


	//update product stock qty
	public function updateInventory($product_id, $quantity) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity ='" . $this->db->escape($quantity) . "' WHERE sku='" . $this->db->escape($product_id) . "' OR model = '" . $this->db->escape($product_id) . "'");
		return $this->db->countAffected();
	}


	//create a new manual product if sku does not exist
	public function createProduct($product_name, $product_sku, $product_model, $product_quantity, $product_description) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($product_sku) . "'");
	  	if($this->db->countAffected() == 0) {
	  		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "product (model,sku,upc,ean,jan,isbn,mpn,location,stock_status_id,manufacturer_id,tax_class_id,date_available) VALUES ('" . $this->db->escape($product_model) . "','" . $this->db->escape($product_sku) . "','" . $this->db->escape($product_sku) . "', '', '', '','','',7,0,0,'2014-01-01')");
			$product_id = $this->db->getLastId();
			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_description (product_id,language_id,name,description,meta_title) VALUES ('" . $this->db->escape($product_id) . "', '1', '" . $this->db->escape($product_name) . "','" . $this->db->escape($product_description) . "', '" . $this->db->escape($product_name) . "')");
			return $this->db->countAffected();
	  	} else {
	    	return "0";
	  }
	}

}
