<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Financial_db extends CI_Model {
	
	/**
	 * Financial_db Model
	 * 
	 * All interactions related to financial issues
	 */

	public function stockRelease()
	{
		// Come from Helper Controller clean_stock

		$data['financial?'] = true;

		$this->db->where('financial?', false);
		$this->db->update('nfes', $data);

	}

	public function saleslistRelease()
	{
		// Come from Helper Controller clean_stock

		$data['financial?'] = true;

		$this->db->where('financial?', false);
		$this->db->update('saleList', $data);

	}

	public function paylistRelease()
	{
		// Come from Helper Controller clean_payments

		$data['financial?'] = true;

		$this->db->where('financial?', false);
		$this->db->update('payList', $data);

	}

	public function salesRelease()
	{
		// Come from Helper Controller clean_sales

		$data['financial?'] = true;

		$this->db->where('financial?', false);
		$this->db->update('sales', $data);

	}

	public function movesRelease()
	{
		// Come from Helper Controller clean_sales

		$data['financial?'] = true;

		$this->db->where('financial?', false);
		$this->db->update('moves', $data);

	}

	public function movelistRelease()
	{
		// Come from Helper Controller clean_stock

		$data['financial?'] = true;

		$this->db->where('financial?', false);
		$this->db->update('moveList', $data);

	}

}