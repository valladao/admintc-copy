<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fiscal_db extends CI_Model {
	
	/**
	 * Fiscal_db Model
	 * 
	 * All interactions related to fiscal issues
	 * 
	 */

	public function get_fiscal()
	{
		// Come from Helper Controller, get_fiscal

		return $this->db->get('fiscalRegister')->result_array();

	}

	public function fiscal_add($sku)
	{
		// Come from Stock Controller, stock_in

		for ($x = 1; $x <= 2; $x++) {

			$data['idStock'] = $x;
			$data['sku'] = $sku;

			$this->db->insert('fiscalList',$data);

		}

	}

	public function fiscalRelease($sku,$idStock)
	{
		// Come from Helper Controller clean_fiscal

		$data['fiscal?'] = true;

		$this->db->where('sku', $sku);
		$this->db->where('idStock', $idStock);
		$this->db->update('fiscalList', $data);

	}

	public function get_transfer()
	{
		// Come from Helper Controller, get_transfer

		return $this->db->get('transferTable')->result_array();

	}

	public function clean_transfer($origin,$dest)
	{
		// Come from Helper Controller, clean_transfer

		$data['fiscal?'] = true;

		$this->db->where('origin', $origin);
		$this->db->where('destination', $dest);
		$this->db->update('transferList', $data);

	}

	public function receipt_done($idSales)
	{
		// Come from Helper Controller receipt_done

		$data['fiscal?'] = true;

		$this->db->where('idSales', $idSales);
		$this->db->update('sales', $data);

	}

}