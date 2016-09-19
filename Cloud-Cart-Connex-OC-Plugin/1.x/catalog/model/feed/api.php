<?php
#####################################################################################
#  API for Opencart 1.5.x From Cloud Cart Connector     			#
#####################################################################################


class ModelFeedApi extends Model {

	//You can put any DB calls you like in here to post/delete/put/get

	public function updateOrderStatus($order_id, $status, $comment)
	{
		$query = $this->db->query("Update `" . DB_PREFIX . "order` SET comment = $comment, order_status_id = (select order_status_id from `" . DB_PREFIX . "order_status` where Name = '". $this->db->escape($status) . "') WHERE order_id=" . $this->db->escape($order_id) . "");
		return $this->db->countAffected();
	}

	public function getCustomers() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer");
		
		return $query->row;
	}
	
  public function getOrderTotals($orderid){
  		$query = $this->db->query("Select * from `" . DB_PREFIX . "order_total` where order_id = $orderid");
		return $query->rows;

  }
  
   public function getProducts($beginDate, $endDate, $skip, $limit){   
		$query = $this->db->query("select p.*, pd.name, pd.description from `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd on p.product_id = pd.product_id WHERE p.Date_Added >= $beginDate AND p.Date_Added <= $endDate limit $skip, $limit");	
		return $query->rows;
	}
	
	
public function getOrders($beginDate, $endDate) {
		
		$query = $this->db->query("select o.*, os.name as order_status_name from `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) where o.Date_Added >= $beginDate AND o.Date_Added <= $endDate");
		return $query->rows;
	}
	
  
  public function getOrderProducts($orderid){
  		$query = $this->db->query("select op.*, p.*, oo.name as option_name, op.Price as item_price, op.Quantity as item_quantity, oo.value as option_value from `" . DB_PREFIX . "order_product` op
LEFT JOIN `" . DB_PREFIX . "order_option` oo on (oo.order_product_id = op.order_product_id) 
LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = op.product_id) where op.order_id = $orderid");
		return $query->rows;

  }
	
	
	public function getOrdersByNumberRange($lowest, $highest)
	{
		$query = $this->db->query("select o.*, os.name as order_status_name from `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) where o.order_id >= $lowest AND o.order_id <= $highest");
		return $query->rows;
	}
	
	public function updateInventory($product_id, $quantity)
	{
		$query = $this->db->query("Update " . DB_PREFIX . "product SET quantity ='" . $this->db->escape($quantity) . "' WHERE sku='" . $this->db->escape($product_id) . "' OR model = '" . $this->db->escape($product_id) . "'");
		return $this->db->countAffected();
	}
	
	public function authenticate()
	{
		$query = $this->db->query("select * from `product` limit 1");
		return "OK";
	}
  
  public function createProduct($product_name, $product_sku, $product_model, $product_quantity, $product_description){
      $query = $this->db->query("select * from `product` where sku = '" . $this->db->escape($product_sku) . "'");
      if($this->db->countAffected() == 0){
      		$query = $this->db->query("Insert INTO " . DB_PREFIX . "product (model,sku,upc,ean,jan,isbn,mpn,location,stock_status_id,manufacturer_id,tax_class_id,date_available) VALUES ('" . $this->db->escape($product_sku) . "','" . $this->db->escape($product_sku) . "','" . $this->db->escape($product_sku) . "', '', '', '','','',7,0,0,'2014-1-1')");
		      return $this->db->countAffected();
      }
      else{
        return "0";
      }
  }
	
	public function deleteProduct($product_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id='" . $this->db->escape($product_id) . "'");
	}

}
?>