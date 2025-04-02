<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_db extends CI_Model {

	/**
	 * Product_db Model
	 *
	 * Model responsible by all products interactions with database
	 *
	 */

	public function __construct()
	{

		parent::__construct();

	}

	public function read($id)
	{
		// Come from Product Controller prod_add

		$statement = 'SELECT * FROM products NATURAL JOIN variants WHERE `idProduct` ='.$id;
		return $this->db->query($statement)->result_array();

	}

	public function create($products)
	{
		// Come from Product Controller prod_add

		$data = array(
			'title' => $products['title'] ,
			'type' => $products['type'] ,
			'supplier' => $products['supplier'] ,
			'model' => $products['model']
		);

		$this->db->insert('products', $data);

		return $this->db->insert_id();

	}

	public function createVar($ids,$variants)
	{
		// Come from Product Controller prod_add

		if($variants['barcode'] == NULL) {
			$barcode = "";
		}
		else {
			$barcode = $variants['barcode'];
		}

		$data = array(
			'idProduct' => $ids['idProduct'] ,
			'variant' => $variants['variant'] ,
			'salesPrice' => $variants['salesPrice'] ,
			'stockQty' => 0 ,
			'barcode' => $barcode ,
			'ncm' => $variants['ncm'] ,
			'draft?' => true
		);

		$this->db->insert('variants', $data);
		$idVariant = $sku = $this->db->insert_id();

		$data2 = array(
			'sku' => $sku
		);

		$this->db->where('idVariant', $idVariant);
		$this->db->update('variants', $data2);

		return $sku;

	}

	public function updateVar($variants)
	{
		// Come from Stock Controller stock_in

		$data = array(
			'salesPrice' => $variants['newPrice'] ,
			'unitCost' => $variants['unitCost'] ,
			'draft?' => false
		);

		if ($variants['idVar']) {
			$data['idVar'] = $variants['idVar'];
		}

		if ($variants['idInventory']) {
			$data['idInventory'] = $variants['idInventory'];
		}

		$this->db->where('sku', $variants['sku']);
		$this->db->update('variants', $data);

	}

	public function updateProd($products)
	{
		// Come from Stock Controller stock_in

		if ($products['idShopify']) {
			$data['idShopify'] = $products['idShopify'];
		}

		if ($products['idOptions']) {
			$data['idOptions'] = $products['idOptions'];
		}

		$this->db->where('idProduct', $products['idProduct']);
		$this->db->update('products', $data);

	}

	public function editProduct($editInfo)
	{
		// Come from Product Controller prod_updt

		$data1['title'] = $editInfo['title'];
		$data1['type'] = $editInfo['type'];
		$data1['model'] = $editInfo['model'];

		$data2['variant'] = $editInfo['variant'];
		$data2['salesPrice'] = $editInfo['salesPrice'];
		$data2['barcode'] = $editInfo['barcode'];

		$this->db->where('idProduct', $editInfo['idProduct']);
		$this->db->update('products', $data1);

		$this->db->where('sku', $editInfo['sku']);
		$this->db->update('variants', $data2);

	}

	public function get_idshopify($idProduct)
	{
		// get idShopify from idProduct

		$statement = 'SELECT * FROM `products` WHERE `idProduct`='.$idProduct;
		return $this->db->query($statement);

	}

	public function del_var($sku)
	{
		// Come from Stock Controller list_del

		$data['deleted?'] = true;

		$this->db->where('sku', $sku);
		$this->db->update('variants', $data);

	}

}