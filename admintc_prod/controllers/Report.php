<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	
	/**
	 * 
	 * Report Controller
	 * 
	 * Controller responsible for all report interactions
	 * 
	 */

	public function __construct()
	{

		parent::__construct();

		$this->load->model('Sales_db');

	}

	public function daily($date)
	{
		// Used in page "Relatório de Vendas", sales_rprtController.js, getReport function

//		$query['sales'] = $this->Sales_db->sales_daily($date);
		$this->load->model('Other_db');

		$query['sales'] = $this->Other_db->today_sales($date);
		$query['saleList'] = $this->Sales_db->get_saleList($date);
		$query['payList'] = $this->Sales_db->get_payList($date);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

//		$this->output->set_content_type('text/plain')->set_output($date);
	}

	public function sales($idSales)
	{
		// Used in page "Relatório de Vendas", sales_rprtController.js, getReport2 function

		$query['sales'] = $this->Sales_db->get_sale($idSales);
		$query['saleList'] = $this->Sales_db->get_saleList2($idSales);
		$query['payList'] = $this->Sales_db->get_payList2($idSales);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function supplier($year)
	{
		// Used in page "Vendas por Fornecedor", supplier_rprtController.js, getReport function

		$query = $this->Sales_db->get_supplierReport($year);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function fiscal()
	{
		# Utilizado na página "Relatório de Vendas"
	}

}