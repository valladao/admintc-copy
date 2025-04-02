<?php defined('BASEPATH') OR exit('No direct script access allowed');

class External_db extends CI_Model {

	/**
	 * External_db Model
	 *
	 * Here we have all DB access to External pages
	 *
	 */

	public function check()
	{

		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		# Query that does not send the password back
		$query = $this->db->query('SELECT `username`,`firstName`,`lastName`,`email`,`privLevel` FROM `users` WHERE `username`="'.$username.'" AND `password`="'.$password.'";');

		if($query->num_rows() == 1)
		{
			return $query;
		}
		else
		{
			return NULL;
		}

	}

	public function externalLog($level,$message)
	{

		// Level 0 = Infomation

		$data['level'] = $level;
		$data['message'] = $message;
		$data['datetime'] = date("Y-m-d H:i:s");

		$this->db->insert('logs',$data);

	}

	public function shopify_new_sale($discount,$totalPrice,$totalCost,$freight)
	{

		$data = array(
			'date' => date("Y-m-d") ,
			'store' => "shopify" ,
			'discount' => $discount ,
			'totalPrice' => $totalPrice ,
			'totalCost' => $totalCost ,
			'username' => "shopify" ,
			'fiscal?' => true
		);

		if ($freight != 0) {
			$data['freight'] = $freight;
		}

		$data['registerTime'] = date("Y-m-d H:i:s");

		$this->db->insert('sales', $data);

		return $this->db->insert_id();

	}

	public function shopify_add_list($idSales,$discount,$sku,$salesPrice,$salesQty,$unitCost,$supplier,$type)
	{

		$subtotal = ($salesPrice * $salesQty) - $discount;

		$data = array(
			'idSales' => $idSales ,
			'idVariant' => $sku ,
			'price' => $salesPrice ,
			'cost' => $unitCost ,
			'qty' => $salesQty ,
			'discount' => $discount ,
			'supplier' => $supplier ,
			'type' => $type ,
			'subtotal' => $subtotal
		);

		$this->db->insert('saleList', $data);

		return $this->db->insert_id();

	}

	public function add_pay($idSales,$gateway,$value,$order)
	{

		$data = array(
			'idSales' => $idSales,
			'value' => $value,
			'channel' => "Shopify",
			'note' => "Venda Shopify. Gateway: ".$gateway." - Ordem: ".$order
		);

		switch ($gateway) {
			case 'pag_seguro':
				$data['payType'] = "Pagseguro";
				break;

			case 'checkout_moip':
				$data['payType'] = "MOIP";
				break;

			default:
				$data['payType'] = "Desconhecido";
				break;
		}

		$this->db->insert('payList', $data);

		return $this->db->insert_id();

	}

	public function sku_cost($sku)
	{

		$this->db->where('sku', $sku);
		return $this->db->get('variants')->row()->unitCost;

	}

	public function sku_supplier($sku)
	{

		$this->db->where('sku', $sku);
		return $this->db->get('productTable')->row()->supplier;

	}

	public function sku_type($sku)
	{

		$this->db->where('sku', $sku);
		return $this->db->get('productTable')->row()->type;

	}

	public function get_qty($sku,$stock)
	{

		switch ($stock) {
			case 'Moema':
			case 'moema':
				$this->db->where('sku', $sku);
				$this->db->where('idStock', 1);
				return $this->db->get('stockList');
				break;

			case 'Jardins':
			case 'jardins':
				$this->db->where('sku', $sku);
				$this->db->where('idStock', 2);
				return $this->db->get('stockList');
				break;

			case 'Morumbi':
			case 'morumbi':
				$this->db->where('sku', $sku);
				$this->db->where('idStock', 4);
				return $this->db->get('stockList');
				break;

			case 'Museu':
			case 'museu':
				$this->db->where('sku', $sku);
				$this->db->where('idStock', 3);
				return $this->db->get('stockList');
				break;

			default:
				//If no stock, sum all stock
				$this->db->where('sku', $sku);
				return $this->db->get('stockTable');
				break;
		}

	}

	public function update($value)
	{
		// This updates a line to store new stock quantity

		switch ($value['origin']) {
			case 'Moema':
			case 'moema':
				$idStock = 1;
				break;

			case 'Jardins':
			case 'jardins':
				$idStock = 2;
				break;

			case 'Morumbi':
			case 'morumbi':
				$idStock = 4;
				break;

			case 'Museu':
			case 'museu':
				$idStock = 3;
				break;

			default:
				$idStock = 1;
				break;
		}

		$data = array(
			'stockQty' => $value['quantity']
		);

		$this->db->where('idStock', $idStock);
		$this->db->where('sku', $value['sku']);
		$this->db->update('stockList', $data);

	}

	public function tx_list_add($sku,$origin,$destination,$txQty)
	{

		$data['sku'] = $sku;
		$data['origin'] = $origin;
		$data['destination'] = $destination;
		$data['txQty'] = $txQty;
		$data['fiscal?'] = false;

		$this->db->insert('transferList',$data);

	}

	public function add_picture($picture,$idShopify)
	{
		// Come from Stock Controller stock_in

		// Log the picture URL before updating the database
		$this->externalLog('0', 'Picture URL before DB update: ' . $picture);

		$data['picture'] = $picture;

		$this->db->where('idShopify', $idShopify);
		$this->db->update('products', $data);

		// Query the database to get the stored picture URL
		$this->db->select('picture');
		$this->db->where('idShopify', $idShopify);
		$query = $this->db->get('products');

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$stored_picture = $row->picture;
			$this->externalLog('0', 'Picture URL after DB update: ' . $stored_picture);
		} else {
			$this->externalLog('3', 'Failed to retrieve picture URL after DB update for idShopify: ' . $idShopify);
		}
	}

	public function get_idInventory($sku)
	{

		$this->db->where('sku', $sku);
		return $this->db->get('variants')->row()->idInventory;

	}

	public function checkError($sku)
	{

		$this->db->where('sku', $sku);

		$query = $this->db->get('stockErrors');

		if($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function stock_error_add($sku,$dbLevel,$shopifyLevel)
	{

		$data['datetime'] = date("Y-m-d H:i:s");
		$data['sku'] = $sku;
		$data['dbLevel'] = $dbLevel;
		$data['ok?'] = false;

		if (is_null($shopifyLevel)) {
			$data['shopifyLevel'] = 0;
		}
		else {
			$data['shopifyLevel'] = $shopifyLevel;
		}

		$this->db->insert('stockErrors',$data);

	}

	public function stock_error_update($sku,$dbLevel,$shopifyLevel)
	{

		$data['datetime'] = date("Y-m-d H:i:s");
		$data['dbLevel'] = $dbLevel;
		$data['ok?'] = false;

		if (is_null($shopifyLevel)) {
			$data['shopifyLevel'] = 0;
		}
		else {
			$data['shopifyLevel'] = $shopifyLevel;
		}

		$this->db->where('sku', $sku);
		$this->db->update('stockErrors',$data);

	}

	public function get_skus()
	{

		$statement = "SELECT `sku` FROM `variants` ORDER BY `idVariant` DESC";

		return $this->db->query($statement)->result_array();

	}

}
