<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	
	/**
	 * 
	 * Product Controller
	 * 
	 * Controller responsible by all products interactions
	 * 
	 */

	public function __construct()
	{

		parent::__construct();

		// $this->is_logged_in();
		$this->load->model('Product_db');
		$this->load->model('Other_db');

	}

	public function get()
	{
		# Utilizado na página "Editar Produtos"
	}

	public function prod_add()
	{
		// Used in page "Cadastrar Compra" (call by inputController.js)

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		// Create product and variant in system
		$ids['idProduct'] = $this->Product_db->create($arrayPostdata);

		$ids['sku'] = $this->Product_db->createVar($ids,$arrayPostdata);

		// Attach the created product to input purchase list
		$ids['idNfes'] = $arrayPostdata['idNfes'];
		$this->Other_db->list_add($ids,$arrayPostdata['salesPrice'],0);

		$message = "Produto ".$arrayPostdata['title'].", SKU: ".$ids['sku']." criado pelo usuário ".$this->session->userdata('username').".";
		$this->Other_db->internalLog(1,$message);

	}

	public function var_add()
	{
		// Used in page "Cadastrar Compra" (call by inputController.js)

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$ids['idProduct'] = $arrayPostdata['idProduct'];

		$ids['sku'] = $this->Product_db->createVar($ids,$arrayPostdata);

		// Attach the created product to input purchase list
		$ids['idNfes'] = $arrayPostdata['idNfes'];
		$this->Other_db->list_add($ids,$arrayPostdata['salesPrice'],1);

		$message = "Produto ".$arrayPostdata['title'].", SKU: ".$ids['sku']." criado pelo usuário ".$this->session->userdata('username').".";
		$this->Other_db->internalLog(1,$message);

	}

	public function prod_updt()
	{
		// Used in page "Editar Produtos" (call by productsController.js, updateProduct function)

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$this->Product_db->editProduct($arrayPostdata);

		// Update Shopify
		$result = $this->Product_db->get_idshopify($arrayPostdata['idProduct']);
		$temp = $result->row();
		$arrayPostdata['idShopify'] = intval($temp->idShopify);

		$this->load->model('Shopify_rest');
		$this->Shopify_rest->put($arrayPostdata);
		$this->Shopify_rest->vput($arrayPostdata);

		// Add to fiscal list
		$this->load->model('Fiscal_db');
		$this->Fiscal_db->fiscal_add($arrayPostdata['sku']);

		// Log this edition
		$message = "Produto ".$arrayPostdata['title'].", SKU: ".$arrayPostdata['sku']." editado pelo usuário ".$this->session->userdata('username').".";
		$this->Other_db->internalLog(1,$message);
		$this->Other_db->internalLog(1,$postdata);

	}

	public function prod_del($sku)
	{
		// Used in page "Editar Produtos" (call by productsController.js, delProd function)

		$qty = $this->check_qty($sku,"");

		if ($qty == 0) {
			$this->Product_db->del_var($sku);
			$this->output->set_content_type('text/plain')->set_output($sku);
			$message = "Produto SKU: ".$sku." apagado pelo usuário ".$this->session->userdata('username').".";
			$this->Other_db->internalLog(1,$message);
		}

		else {
			$message = "Tentativa de apagar produto ".$sku." frustrada. Produto ainda tem ".$qty." itens.";
			$this->Other_db->internalLog(3,$message);
			$this->response->setStatusCode(403, "O produto não está zerado!");
		}

	}

	/**
	 * 
	 * Specific functions related to update product in Shopify
	 * 
	 */

	public function get_site_info()
	{
		# Utilizado na página "Editar Produtos"
	}

	public function updt_site_info()
	{
		# Utilizado na página "Editar Produtos"
	}

	public function check_qty($sku,$stock)
	{

		$this->load->model('Stock_db');
		$result = $this->Stock_db->get_qty($sku,$stock);

		if ($result->num_rows()==0) {
			$qty = 0;
		}
		else {
			if ($stock) {
				$row = $result->row();
				$qty = intval($row->stockQty);
			}
			else {
				$row = $result->row();
				$qty = intval($row->total);
			}
		}

		return $qty;

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