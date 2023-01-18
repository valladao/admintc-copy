<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_db extends CI_Model {
	
	/**
	 * 
	 * Stock_db Model
	 * 
	 * Model responsible by all stock interactions with database
	 * 
	 */

	public function __construct()
	{

		parent::__construct();

	}

	public function get()
	{
		// Come from Stock Controller, get

		return $this->db->get('productTable')->result_array();

	}

	public function get_all()
	{
		// Come from Stock Controller, get_all

		return $this->db->get('allProducts')->result_array();

	}

	public function update($value)
	{
		// This update creates a new line in database to store stock quantity
		// Come from Stock Controller, stock_in

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
			'idStock' => $idStock ,
			'sku' => $value['sku'] ,
			'stockQty' => $value['quantity']
		);

		$this->db->insert('stockList', $data);

	}

	public function update2($value)
	{
		// This updates a line to store new stock quantity
		// Come from Stock Controller, stock_in

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

	public function get_qty($sku,$stock)
	{
		// Come from Stock Controller, check_qty

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
			
			case 'Shopify':
			case 'shopify':
				$this->db->where('sku', $sku);
				$this->db->where('idStock', 1);
				return $this->db->get('stockList');
				break;

			default:
				//If no stock, sum all stock
				$this->db->where('sku', $sku);
				return $this->db->get('stockTable');
				break;
		}

	}

	public function sku_stock($sku)
	{
		// Come from Stock Controller, stock_in

		$this->db->where('sku', $sku);
		return $this->db->get('skuStock')->result_array();

	}

	public function get_issues()
	{

		$this->db->where('ok?', false);
		return $this->db->get('stockErrors')->result_array();

	}

	public function clean_issue($idError)
	{
		// Come from Helper Controller receipt_done

		$data['ok?'] = true;

		$this->db->where('idError', $idError);
		$this->db->update('stockErrors', $data);

	}

}