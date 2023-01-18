<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {
	
	/**
	 * Stock Controller
	 * 
	 * To control all interactions with stock
	 * 
	 */

	public function __construct()
	{

		parent::__construct();

		$this->is_logged_in();
		$this->load->model('Other_db');
		$this->load->model('Stock_db');

	}

	public function get()
	{
		// Used in page "Consultar Estoque"
		# Utilizado na página "Registrar Vendas"
		# Utilizado na página "Transferência de Estoque"
		# Utilizado na página "Cadastrar Compra"

		$query = $this->Stock_db->get();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_all()
	{
		// Used in inputController.js, function getAllProds
		// Get products including drafts

		$query = $this->Stock_db->get_all();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_vars()
	{
		# Utilizado na página "Editar Produtos"
	}

	public function get_nfe()
	{
		// Used in page "Cadastrar Compra"
		# Used in page "Editar Produtos"

		$query = $this->Other_db->get_nfes();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function nfe_list($idNfes)
	{
		// Used in page "Cadastrar Compra"

		$query = $this->Other_db->nfe_list($idNfes);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function list_add()
	{
		// Come from inputController, addProd function
		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		// Attach the created product to input purchase list
		$ids['idNfes'] = $arrayPostdata['idNfes'];
		$ids['sku'] = $arrayPostdata['sku'];
		$this->Other_db->list_add($ids,$arrayPostdata['price'],0);

	}

	public function list_save()
	{
		// Used in page "Cadastrar Compra"
		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$query = $this->Other_db->list_save($arrayPostdata);

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function list_del($idPurchaseList,$sku)
	{
		// Used in page "Cadastrar Compra"

		$this->load->model('Product_db');
		$this->Other_db->list_del($idPurchaseList);

		if (false) {
			$this->Product_db->del_var($sku);
		}

	}

	public function stock_in($idNfes)
	{
		// Used in page "Cadastrar Compra"

		// Give time to save the list
		sleep(1);

		$query = $this->Other_db->nfe_list($idNfes);

		$this->load->model('Shopify_rest');
		$this->load->model('Product_db');
		$this->load->model('Fiscal_db');

		foreach ($query as $row) {

			if ($row['draft?']) {

				if ($row['newVar']) {

					$result = $this->Product_db->get_idshopify($row['idProduct']);
					$temp = $result->row();
					$row['idShopify'] = intval($temp->idShopify);

					// Add product variant in Shopify
					$json_result = $this->Shopify_rest->vpost($row);

					$array_result = json_decode($json_result, true);
					$variant = $array_result['variant'];
					$row['idVar'] = $variant['id'];
					$row['idInventory'] = $variant['inventory_item_id'];

				}

				else {

					//If this product is draft, create on shopify and sync ids with system
	
					$json_result = $this->Shopify_rest->post($row);
	
					$array_result = json_decode($json_result, true);
					$product = $array_result['product'];
	
					$row['idShopify'] = $product['id'];
					foreach ($product['variants'] as $rows2) {
						$row['idVar'] = $rows2['id'];
						$row['idInventory'] = $rows2['inventory_item_id'];
					}
					foreach ($product['options'] as $rows3) {
						$row['idOptions'] = $rows3['id'];
					}

					$this->Product_db->updateProd($row);

				}

				$this->Stock_db->update($row);

				$this->Shopify_rest->updt_qty_new($row['idInventory'],$row['quantity']);

			}

			else {

				//To recalculate cost for old products
				$query2 = $this->Stock_db->sku_stock($row['sku']);
				$row['unitCost'] = ($query2[0]['stockPrice'] + ($row['unitCost'] * $row['quantity']))/($row['quantity'] + $query2[0]['total']);
				$stock1 = $this->check_qty($row['sku'],'moema');
				$row['quantity'] = ($row['quantity'] + $stock1);

				$this->Stock_db->update2($row);

				//Update stock and quantity in shopify
				$row['salesPrice'] = $row['newPrice'];
				$row['stockQty'] = $this->check_qty($row['sku'],false);
				$json_result = $this->Shopify_rest->vput($row);

			}

			$this->Product_db->updateVar($row);

			$this->Fiscal_db->fiscal_add($row['sku']);

		}

		$this->Other_db->close_nfes($idNfes);

		$message = 'Nota fiscal Núm. '.$idNfes.' foi fechado pelo usuário '.$this->session->userdata('username').'.';

		$this->Other_db->internalLog('1',$message);

	}

	public function updt_stock()
	{
		// Used in page "Cadastrar Compra"

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$origin = $arrayPostdata['store'];
		$dest = $arrayPostdata['destStore'];
		$data = $arrayPostdata['data'];
		$sku_list = "";

		foreach ($data as $row) {
			$orig_qty = $this->check_qty($row['sku'],$origin);

			//Check if we have enought itens to transfer
			if ($orig_qty >= $row['txQty'] && $row['txQty'] > 0) {
				$new_qty = $orig_qty - $row['txQty'];

				$value['origin'] = $origin;
				$value['sku'] = $row['sku'];
				$value2['sku'] = $row['sku'];
				$value['quantity'] = $new_qty;

				$this->Stock_db->update2($value);

				$value2['origin'] = $dest;

				//Check if product destination already exists
				$dest_exist = $this->Stock_db->get_qty($row['sku'],$dest);
		
				if ($dest_exist->num_rows()==0) {
					// Add a new line
					$value2['quantity'] = $row['txQty'];
					$this->Stock_db->update($value2);
				}
				else {
					// Update current line
					$row2 = $dest_exist->row();
					$qty = intval($row2->stockQty);
					$value2['quantity'] = $qty + $row['txQty'];
					$this->Stock_db->update2($value2);
				}

				// To add transfer to the list
				$this->Other_db->tx_list_add($row['sku'],$origin,$dest,$row['txQty']);

				// To add SKU to list and after log it
				$sku_list .= $row['sku'];
				$sku_list .= ", ";

			}

		}

		$message1 = 'Transferência feita por '.$this->session->userdata('username').'. De: '.$origin.' - Para: '.$dest;

		$this->Other_db->internalLog('1',$message1);

		// To give time to save first log
		sleep(1);

		$sku_list = substr($sku_list,0,-2);
		$message2 = 'SKUs:  '.$sku_list;

		$this->Other_db->internalLog('1',$message2);

		$message = $message1."\n".$message2;
		$this->output->set_content_type('text/html')->set_output($message);

	}

	public function move_stock()
	{
		// Come from movesController sendMove

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$date = $arrayPostdata['date'];
		$store = $arrayPostdata['store'];
		$username = $arrayPostdata['username'];
		$reason = $arrayPostdata['reason'];
		$note = $arrayPostdata['note'];
		$moveTotal = $arrayPostdata['moveTotal'];
		$moveTable = $arrayPostdata['moveTable'];

		if (isset($arrayPostdata['changeSale'])) {
			$changeSale = $arrayPostdata['changeSale'];
		}
		else {
			$changeSale = "";
		}

		$sku_list = "";

		$idMoves = $this->Other_db->move_add($date,$username,$moveTotal,$reason,$note,$changeSale,$store);

		foreach ($moveTable as $row) {

			$this->Other_db->move_list_add($idMoves,$row['sku'],$row['supplier'],$row['changeQty'],$row['unitCost']);

			$data['origin'] = $store;
			$data['sku'] = $row['sku'];
			$orig_qty = $this->check_qty($row['sku'],$store);
			$data['quantity'] = $orig_qty + $row['changeQty'];

			//Check if product destination already exists
			$dest_exist = $this->Stock_db->get_qty($data['sku'],$data['origin']);
		
			if ($dest_exist->num_rows()==0) {
				$this->Stock_db->update($data);
			}
			else {
				$this->Stock_db->update2($data);
			}

			//Update qty for Shopify
			//Updated to adapt to Shopify new stock inventory changes
			$this->load->model('Shopify_rest');
			$idInventory = $row['idInventory'];
			$qty = $this->check_qty($row['sku'],"");
			$result = $this->Shopify_rest->updt_qty_new($idInventory,$qty);

		}

		$output = "Movimentação de estoque ".$idMoves.", registrada por ".$this->session->userdata('username').".";
		$this->Other_db->internalLog('1',$output);
		$this->output->set_content_type('text/html')->set_output($output);

	}

	public function check_qty($sku,$stock)
	{

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

	///////////////////////////////
	// DELETE "Cadastro Inicial" //
	///////////////////////////////

	public function get_temp()
	{
		// Used in page "Transferência de Estoque"

		$query = $this->Stock_db->get_temp();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function set_stock()
	{
		# code...

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$data1['sku'] = $data2['sku'] = $arrayPostdata['sku'];
		$data1['origin'] = "Moema";
		$data2['origin'] = "Jardins";
		$data1['quantity'] = $arrayPostdata['moema'];
		$data2['quantity'] = $arrayPostdata['jardins'];

		// Gravar no estoque Moema
		$this->Stock_db->update($data1);

		if ($data2['quantity'] > 0) {
			// Gravar no estoque Jardins
			$this->Stock_db->update($data2);
			// Gravar na planilha de transferência para o Jardins
			$this->Other_db->tx_list_add($data2['sku'],"Moema","Jardins",$data2['quantity']);
		}

		// Zerar o estoque Antigo
		$this->Stock_db->clear_var($data1['sku']);

	}

	public function get_issues()
	{

		$query = $this->Stock_db->get_issues();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

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