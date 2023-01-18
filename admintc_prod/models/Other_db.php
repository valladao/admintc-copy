<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Other_db extends CI_Model {

	/**
	 * Other_db Model
	 *
	 * Here we have all access to minor/other tables
	 *
	 */

	public function internalLog($level,$message)
	{
		// Come from ...
		// Level 0 = Information
		// Level 1 = Event
		// Level 2 = Warning
		// Level 3 = Error

		$data['level'] = $level;
		$data['message'] = $message;
		$data['datetime'] = date("Y-m-d H:i:s");

		$this->db->insert('logs',$data);

	}

	public function get_types()
	{
		// Come from Helper Controller, get_types

		return $this->db->get('type')->result_array();

	}

	public function get_models()
	{
		// Come from Helper Controller, get_models

		return $this->db->get('model')->result_array();

	}

	public function get_suppliers()
	{
		// Come from Helper Controller, get_models

		$this->db->where('deleted?', false);
		return $this->db->get('supplier')->result_array();

	}

	public function get_nfes()
	{
		// Come from Stock Controller, get_nfe

		return $this->db->get('open_nfes')->result_array();

	}

	public function nfe_list($idNfes)
	{
		// Come from Stock Controller, nfe_list and stock_in

		$statement = 'SELECT * FROM `purchaseItems` WHERE `idNfes`='.$idNfes.' ORDER BY `idPurchaseList` ASC';
		return $this->db->query($statement)->result_array();

	}

	public function close_nfes($idNfes)
	{
		// Come from Stock Controller, stock_in
		$data['closed?'] = true;
		$data['financial?'] = false;

		$this->db->where('idNfes', $idNfes);
		$this->db->update('nfes', $data);

	}

	public function list_add($ids,$price,$newvar)
	{
		// Come from Product Controller, prod_add

		$data['idNfes'] = $ids['idNfes'];
		$data['sku'] = $ids['sku'];
		$data['newPrice'] = $price;
		$data['newVar?'] = $newvar;

		$this->db->insert('purchaseList',$data);

	}

	public function list_save($purchaseList)
	{
		// Come from Product Controller, prod_add

		foreach ($purchaseList as $row) {

			$data['newPrice'] = $row['newPrice'];
			$data['unitCost'] = $row['unitCost'];
			$data['quantity'] = $row['quantity'];
			$idNfes = $row['idNfes'];

			$this->db->where('idPurchaseList', $row['idPurchaseList']);
			$this->db->update('purchaseList', $data);

		}

		return $this->nfe_list($idNfes);

	}

	public function list_del($purchaseList)
	{
		// Come from Product Controller, prod_add

		$this->db->where('idPurchaseList', $purchaseList);
		$this->db->delete('purchaseList');

	}

	public function tx_list_add($sku,$origin,$destination,$txQty)
	{
		// Come from Stock Controller, updt_stock

		$data['sku'] = $sku;
		$data['origin'] = $origin;
		$data['destination'] = $destination;
		$data['txQty'] = $txQty;
		$data['	fiscal?'] = false;

		$this->db->insert('transferList',$data);

	}

	public function get_input()
	{
		// Come from Helper Controller, get_input

		return $this->db->get('closed_nfes')->result_array();

	}

	public function get_cost()
	{
		// Come from Helper Controller, get_cost

		return $this->db->get('cogsFinancial')->result_array();

	}

	public function get_payment()
	{
		// Come from Helper Controller, get_payment

		return $this->db->get('payListFinancial')->result_array();

	}

	public function get_sales()
	{
		// Come from Helper Controller, get_sales

		return $this->db->get('salesFinancial')->result_array();

	}

	public function get_sales2()
	{
		// Come from Helper Controller, get_sales

		return $this->db->get('salesFiscal')->result_array();

	}

	public function move_add($date,$username,$moveTotal,$reason,$note,$changeSale,$store)
	{
		// Come from Stock Controller, move_stock

		$store_lwr = strtolower($store);

		$data = array(
			'date' => $date ,
			'username' => $username ,
			'store' => $store_lwr ,
			'moveTotal' => $moveTotal ,
			'reason' => $reason ,
			'note' => $note ,
			'financial?' => false
		);

		if (isset($changeSale)) {
			$data['changeSale'] = $changeSale;
		}

		$this->db->insert('moves', $data);

		return $this->db->insert_id();

	}

	public function move_list_add($idMoves,$sku,$supplier,$changeQty,$unitCost)
	{
		// Come from Stock Controller, move_stock

		$data = array(
			'idMoves' => $idMoves ,
			'sku' => $sku ,
			'supplier' => $supplier ,
			'changeQty' => $changeQty ,
			'unitCost' => $unitCost ,
			'financial?' => false
		);

		$this->db->insert('moveList', $data);

		return $this->db->insert_id();

	}

	public function get_move()
	{
		// Come from Helper Controller, get_move

		$this->db->where('financial?', false);
		return $this->db->get('moves')->result_array();

	}

	public function get_moveList()
	{
		// Come from Helper Controller, get_moveList

		return $this->db->get('moveListFinancial')->result_array();

	}

	public function get_origin($supplier)
	{
		// Come from Helper Controller, get_origin
		$this->db->where('supplier', $supplier);

		return $this->db->get('supplier');

	}

	public function get_variants($idProduct)
	{
		// Come from Helper Controller, get_origin and get_variants

		$statement = 'SELECT model, variant, sku, store1, store2, store3, total, salesPrice FROM `productTable` WHERE `idProduct`='.$idProduct;

		return $this->db->query($statement)->result_array();

	}

	public function get_purchase($sku)
	{
		// Come from Helper Controller, get_purchase

		$statement = 'SELECT * FROM `purchaseBySKU` WHERE `sku` LIKE "'.$sku.'"';

		return $this->db->query($statement)->result_array();

	}

	public function get_sales3($sku)
	{
		// Come from Helper Controller, get_sales3

		$statement = 'SELECT * FROM `salesBySKU` WHERE `idVariant` ='.$sku;

		return $this->db->query($statement)->result_array();

	}

	public function get_moves($sku)
	{
		// Come from Helper Controller, get_moves

		$statement = 'SELECT * FROM `movesBySKU` WHERE `sku` ='.$sku;

		return $this->db->query($statement)->result_array();

	}

	public function get_transfer($sku)
	{
		// Come from Helper Controller, get_transfer2

		$statement = 'SELECT * FROM `transferList` WHERE `sku` ='.$sku;

		return $this->db->query($statement)->result_array();

	}

	public function today_sales($today)
	{
		// Come from Helper Controller, today_sales

		$this->db->where('date', $today);
		return $this->db->get('dashSales')->result_array();

	}

	public function get_counters($today,$store)
	{
		// Come from Helper Controller, get_counters

		$statement1 = 'SELECT COUNT(*) AS `total` FROM `sales` WHERE `date`="'.$today.'" AND `store`="'.$store.'"';
		$query['todaySales'] = $this->db->query($statement1)->row()->total;

		$statement2 = 'SELECT COUNT(*) AS `total` FROM `fiscalRegister` WHERE `stockName`="'.$store.'"';
		$query['pendingProds'] = $this->db->query($statement2)->row()->total;

		$statement3 = 'SELECT COUNT(*) AS `total` FROM `transferTable` WHERE `origin`="'.$store.'"';
		$query['pendingTxs'] = $this->db->query($statement3)->row()->total;

		$statement4 = 'SELECT COUNT(*) AS `total` FROM `salesFiscal` WHERE `store`="'.$store.'"';
		$query['pendingRecips'] = $this->db->query($statement4)->row()->total;

		return $query;

	}

	public function get_users()
	{
		// Come from Helper Controller, get_users

		return $this->db->get('userTable')->result_array();

	}

	public function get_newProdList()
	{
		// Come from Internet Controller, get_newProdList

//		$statement = "SELECT productTable.picture AS picture, productTable.title AS title, productTable.variant AS variant, productTable.model AS model, productTable.type AS type, productTable.supplier AS supplier, productTable.sku AS sku, productTable.salesPrice AS salesPrice, productTable.store1 AS store1, productTable.store2 AS store2, productTable.store3 AS store3 FROM productTable LEFT JOIN internetData ON productTable.sku=internetData.sku WHERE internetData.sku IS NULL AND productTable.total>0 AND productTable.picture<>''";

		return $this->db->get('newProdList')->result_array();

//		return $this->db->query($statement)->result_array();

	}

	public function check_update($username,$current,$new)
	{
		// Come from Helper Controller, change_password

		$password = md5($current);

		# Query that does not send the password back
		$query = $this->db->query('SELECT `username`,`firstName`,`lastName`,`email`,`privLevel` FROM `users` WHERE `username`="'.$username.'" AND `password`="'.$password.'";');

		if($query->num_rows() == 1)
		{

			$data['password'] = md5($new);

			$this->db->where('username', $username);
			$this->db->update('users', $data);
			return $query;

		}
		else
		{
			return NULL;
		}

	}

}