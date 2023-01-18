<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Helper extends CI_Controller {

	/**
	 *
	 * Helper Pages
	 *
	 * Internal admin funtions to support
	 *
	 */

	public function __construct()
	{

		parent::__construct();

		$this->is_logged_in();
		$this->load->model('Other_db');

	}

	public function logout()
	{

		$message = 'Usuário '.$this->session->userdata('username').' deixou o sistema para a loja '.$this->session->userdata('store');

		$this->Other_db->internalLog('0',$message);

		$this->session->sess_destroy();
		redirect('external');

	}

	public function get_types()
	{

		//Come from getTerraCotta.js types

		$query = $this->Other_db->get_types();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_models()
	{

		//Come from getTerraCotta.js types

		$query = $this->Other_db->get_models();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_suppliers()
	{

		//Come from getTerraCotta.js types

		$query = $this->Other_db->get_suppliers();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_fiscal()
	{
		//Come from fiscalController, getFiscal

		$this->load->model('Fiscal_db');
		$query = $this->Fiscal_db->get_fiscal();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function clean_fiscal()
	{
		//Come from fiscalController, cleanList

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);
		$sku_list = ""; $count = 0; $idStock = 0;

		$this->load->model('Product_db');
		$this->load->model('Fiscal_db');

		switch ($this->session->userdata('store')) {
			case 'moema':
				$idStock = 1;
				break;
			case 'jardins':
				$idStock = 2;
				break;
			case 'museu':
				$idStock = 3;
				break;
		}

		foreach ($arrayPostdata as $row) {

			$this->Fiscal_db->fiscalRelease($row['sku'],$idStock);

			$sku_list .= $row['sku'];
			$sku_list .= ", ";
			$count++;

		}

		$sku_list = substr($sku_list,0,-2);

		$message = 'Lista fiscal com '.$count.' produtos apagada por '.$this->session->userdata('username').', loja '.$this->session->userdata('store');

		$this->Other_db->internalLog('1',$message);

		// To give time to save first log
		sleep(1);

		$message = 'SKUs: '.$sku_list;

		$this->Other_db->internalLog('1',$message);

	}

	public function get_transfer()
	{
		//Come from fiscalController, getFiscal

		$this->load->model('Fiscal_db');
		$query = $this->Fiscal_db->get_transfer();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function clean_transfer()
	{
		//Come from fiscalController, cleanTransfer

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$origin = $arrayPostdata['origin'];
		$dest = $arrayPostdata['dest'];

		$this->load->model('Fiscal_db');
		$this->Fiscal_db->clean_transfer($origin,$dest);

		$message = 'Lista de transferência apagada por '.$this->session->userdata('username').'. Produtos de Origem: '.$origin.', Destino: '.$dest;

		$this->Other_db->internalLog('1',$message);

	}

	public function clean_stock()
	{
		//Come from financialController, cleanStock

		$this->load->model('Financial_db');
		$this->Financial_db->stockRelease();
		$this->Financial_db->saleslistRelease();
		$this->Financial_db->movelistRelease();

		$message = 'Lista financeira de estoque e custo de venda apagada por '.$this->session->userdata('username');

		$this->Other_db->internalLog('1',$message);

	}

	public function clean_payments()
	{
		//Come from financialController, cleanPay

		$this->load->model('Financial_db');
		$this->Financial_db->paylistRelease();

		$message = 'Lista financeira de pagamentos apagada por '.$this->session->userdata('username');

		$this->Other_db->internalLog('1',$message);

	}

	public function clean_sales()
	{
		//Come from financialController, cleanSales

		$this->load->model('Financial_db');
		$this->Financial_db->salesRelease();
		$this->Financial_db->movesRelease();

		$message = 'Lista financeira de vendas apagada por '.$this->session->userdata('username');

		$this->Other_db->internalLog('1',$message);

	}

	public function get_input()
	{
		//Come from financialController, getInput

		$query = $this->Other_db->get_input();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_cost()
	{
		//Come from financialController, getCost

		$query = $this->Other_db->get_cost();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_payment()
	{
		//Come from financialController, getPayment

		$query = $this->Other_db->get_payment();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_sales()
	{
		//Come from financialController, getSales

		$query = $this->Other_db->get_sales();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_sales2()
	{
		//Come from fiscalController, getSales

		$query = $this->Other_db->get_sales2();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_move()
	{
		//Come from financialController, getMove

		$query = $this->Other_db->get_move();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_moveList()
	{
		//Come from financialController, getMove

		$query = $this->Other_db->get_moveList();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_origin()
	{
		//Come from productsController, getOrigin

		$supplier = file_get_contents("php://input");

		$query = $this->Other_db->get_origin($supplier);
		$row = $query->row();
		$origin = $row->cstDigit;

		$this->output->set_content_type('text/plain')->set_output($origin);

	}

	public function get_variants($idProduct)
	{
		//Come from productsController, getVariants

		$query = $this->Other_db->get_variants($idProduct);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_purchase($sku)
	{
		//Come from productsController, get_purchase

		$query = $this->Other_db->get_purchase($sku);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_sales3($sku)
	{
		//Come from productsController, get_sales

		$query = $this->Other_db->get_sales3($sku);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_moves($sku)
	{
		//Come from productsController, get_sales

		$query = $this->Other_db->get_moves($sku);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_transfer2($sku)
	{
		//Come from productsController, get_sales

		$query = $this->Other_db->get_transfer($sku);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function today_sales()
	{
		//Come from dashboardController.js, getSales

		$today = date("Y-m-d");

		$query = $this->Other_db->today_sales($today);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_counters()
	{
		//Come from dashboardController.js, getSales
		$today = date("Y-m-d");
		$store = $this->session->userdata('store');

		$query = $this->Other_db->get_counters($today,$store);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function receipt_done($idSales)
	{
		//Come from fiscalController.js, receiptDone

		$this->load->model('Fiscal_db');
		$this->Fiscal_db->receipt_done($idSales);

	}

	public function get_users()
	{

		//Come from salesController.js getUser

		$query = $this->Other_db->get_users();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function clean_issue($idError)
	{

		$this->load->model('Stock_db');
		$this->Stock_db->clean_issue($idError);

		$message = 'Entrada de erro núm. ' . $idError . ' limpa por '.$this->session->userdata('username');

		$this->Other_db->internalLog('1',$message);

	}

	public function change_password()
	{
		// Come from passwordChangeController changePassword

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		// Confirm that form fields has been filled
		if (isset($arrayPostdata['password']) && isset($arrayPostdata['new']) && isset($arrayPostdata['repeat'])) {
			$username = $arrayPostdata['username'];
			$current = $arrayPostdata['password'];
			$new = $arrayPostdata['new'];
			$repeat = $arrayPostdata['repeat'];

			// Check if new password matches
			if ($new != $repeat) {
				$this->output
	        ->set_status_header(406)
	        ->set_content_type('text/plain', 'UTF-8')
	        ->set_output("As senhas não batem!");

					$message = 'Tentativa de troca de senha usuário: ' . $username . '. As senhas não batem.';
					$this->Other_db->internalLog('1',$message);

				} else {
				// All initial checks passed, now checking current password
				// If it is OK, update directly
				$query = $this->Other_db->check_update($username,$current,$new);

				if ($query) {
					// Password OK
					$this->output
		        ->set_status_header(200)
	  	      ->set_content_type('text/plain', 'UTF-8')
	    	    ->set_output("Senha atualizada!");

						$message = 'Senha atualizada! Usuário: ' . $username;
						$this->Other_db->internalLog('1',$message);

				} else {
					// Current password wrong
					$this->output
		        ->set_status_header(403)
	  	      ->set_content_type('text/plain', 'UTF-8')
	    	    ->set_output("Senha fornecida incorreta!");

						$message = 'Tentativa de troca de senha usuário: ' . $username . '. Senha fornecida incorreta.';
						$this->Other_db->internalLog('1',$message);

					}
			}

		} else {
			$this->output
        ->set_status_header(406)
        ->set_content_type('text/plain', 'UTF-8')
        ->set_output("Preencha todos os campos!");
		}

	}

	private function is_logged_in()
	{

		$is_logged_in = $this->session->userdata('is_logged_in');

		if (!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect('external');
		}

	}

}