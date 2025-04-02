<?php defined('BASEPATH') OR exit('No direct script access allowed');

class External extends CI_Controller {

	/**
	 * Controller External
	 *
	 * Here we have all "not logged" public interactions
	 *
	 */

	public function __construct()
	{

		parent::__construct();

		$this->load->model('External_db');

		//define('SHOPIFY_APP_SECRET', '9a47a30f7dca563ccd001cdd1db242eda27c593f6163f452abad447d58aebf9f');
		define('SHOPIFY_APP_SECRET', '44611f8216480866190a44d5f6c7bc66');

	}

	public function index($error = NULL)
	{

		$view_data['error'] = $error;
		$this->load->view('external_html', $view_data);

	}

	public function validate()
	{

		# User select store radio (mandatory)
		if ($this->input->post('store')) {

			$query = $this->External_db->check();

			if ($query)
			{

				foreach ($query->result() as $row)
				{
	    			$data = array(
   						'username' => $row->username,
   						'firstName' => $row->firstName,
   						'lastName' => $row->lastName,
   						'store' => $this->input->post('store'),
   						'email' => $row->email,
   						'privLevel' => $row->privLevel,
   						'is_logged_in' => true
   					);
				}
				$this->session->set_userdata($data);

				$message = 'Usuário '.$this->session->userdata('username').' acessou o sistema para a loja '.$this->session->userdata('store');

				$this->External_db->externalLog('0',$message);

				redirect('pages');
			}
			else
			{
				echo "<script>console.log('Senha incorreta...');</script>";

				$message = 'Tentativa frustada de acesso. Usuário: '.$this->input->post('username');

				$this->External_db->externalLog('0',$message);

				$this->index('Usuário ou senha inválida. Acesso negado!');
			}

		}

		else
		{
			echo "<script>console.log('Loja não selecionada...');</script>";

			$message = 'Tentativa de acesso sem a loja. Usuário: '.$this->input->post('username');

			$this->External_db->externalLog('0',$message);

			$this->index('Por favor, selecionar a loja antes de proseguir!');
		}

	}

	public function verify_webhook($data,$hmac_header)
	{

		$calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));

		return hash_equals($hmac_header,$calculated_hmac);

	}

	public function shopify_new_sale()
	{

		$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
		$data = file_get_contents('php://input');
		$verified = $this->verify_webhook($data,$hmac_header);
		error_log('Shopify Webhook - New Sale. Verified: '.var_export($verified, true)); //check error.log to see the result

		$message = 'Shopify Webhook - New Sale. Verified: '.var_export($verified, true);
		$this->External_db->externalLog('0',$message);

		if ($verified) {
			// Validation OK

			$postdata = file_get_contents("php://input");
			$arrayPostdata = json_decode($postdata,true);

			$gateway = $arrayPostdata['gateway'];
			$value = $arrayPostdata['total_price'];
			$totalPrice = $arrayPostdata['subtotal_price'];
			$discount = $arrayPostdata['total_discounts'];
			$order = $arrayPostdata['name'];

			$shipping = $arrayPostdata['shipping_lines'];
			$freight = $shipping[0]['price'];

			$lineItems = $arrayPostdata['line_items'];

			$totalCost = 0;

			foreach ($lineItems as $rows1) {

				$quantity = $rows1['quantity'];
				$sku = $rows1['sku'];
				$itemCost = $this->External_db->sku_cost($sku);

				$totalCost += $quantity * $itemCost;

			}

			$message = 'Venda - Gateway: '.$gateway.'. Value: '.$value.'. TotalPrice: '.$totalPrice.'. Discount: '.$discount.'. Order: '.$order.'. Frete: '.$freight.'. Custo total: '.$totalCost;
			$this->External_db->externalLog('0',$message);

			$idSales = $this->External_db->shopify_new_sale($discount,$totalPrice,$totalCost,$freight);

			foreach ($lineItems as $rows2) {

				$quantity = $rows2['quantity'];
				$price = $rows2['price'];
				$sku = $rows2['sku'];
				$itemDiscount = $rows2['total_discount'];
				$itemCost = $this->External_db->sku_cost($sku);
				$supplier = $this->External_db->sku_supplier($sku);
				$type = $this->External_db->sku_type($sku);

				$message1 = 'Produto - Qtd: '.$quantity.'. Preço: '.$price.'. SKU: '.$sku.'. Discount: '.$itemDiscount.'. Custo: '.$itemCost;
				$this->External_db->externalLog('0',$message1);

				$this->External_db->shopify_add_list($idSales,$itemDiscount,$sku,$price,$quantity,$itemCost,$supplier,$type);

				$moema_qty = $this->check_qty($sku,"moema");

				if ($quantity <= $moema_qty) {

					$data1['origin'] = "moema";
					$data1['sku'] = $sku;
					$data1['quantity'] = $moema_qty - $quantity;

					$this->External_db->update($data1);

				}
				else {

					$jardins_qty = $this->check_qty($sku,"jardins");

					if ($quantity <= $moema_qty + $jardins_qty) {

						$data2['origin'] = "moema";
						$data2['sku'] = $sku;
						$data2['quantity'] = 0;
						$this->External_db->update($data2);

						$data3['origin'] = "jardins";
						$data3['sku'] = $sku;
						$data3['quantity'] = $jardins_qty - $quantity + $moema_qty;
						$txQty = $quantity - $moema_qty;
						$this->External_db->update($data3);

						$this->External_db->tx_list_add($sku,"jardins","moema",$txQty);

					}
					else {

						$museu_qty = $this->check_qty($sku,"museu");

						if ($quantity <= $moema_qty + $jardins_qty + $museu_qty) {

							$data4['origin'] = "moema";
							$data4['sku'] = $sku;
							$data4['quantity'] = 0;
							$this->External_db->update($data4);

							$data5['origin'] = "jardins";
							$data5['sku'] = $sku;
							$data5['quantity'] = 0;
							$this->External_db->update($data5);

							$data6['origin'] = "museu";
							$data6['sku'] = $sku;
							$data6['quantity'] = $museu_qty - $quantity + $moema_qty + $jardins_qty;
							$txQty = $quantity - $moema_qty - $jardins_qty;
							$this->External_db->update($data6);

							if ($jardins_qty > 0) {
								$this->External_db->tx_list_add($sku,"jardins","moema",$jardins_qty);
							}

							$this->External_db->tx_list_add($sku,"museu","moema",$txQty);

						}

						else {
							$message = 'Erro cadastro de venda do site. Quantidade não incompatível. Pedido: '.$order;
							$this->External_db->externalLog('3',$message);
						}

					}

				}

			}

			$this->External_db->add_pay($idSales,$gateway,$value,$order);

		}
		else {
			// Validation FAIL

			header('HTTP/1.0 403 Forbidden');
			exit();
		}

	}

	public function shopify_add_picture()
	{

		$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
		$data = file_get_contents('php://input');
		$verified = $this->verify_webhook($data,$hmac_header);
		error_log('Shopify Webhook - New Picture. Verified: '.var_export($verified, true)); //check error.log to see the result

		$message = 'Shopify Webhook - New Picture. Verified: '.var_export($verified, true);
		$this->External_db->externalLog('0',$message);

		if ($verified) {

			$arrayPostdata = json_decode($data,true);
			$image = $arrayPostdata['image'];
			$idShopify = $arrayPostdata['id'];
			$picture = $image['src'];

			// Log the picture URL for debugging
			$this->External_db->externalLog('0', 'Picture URL from webhook: ' . $picture);

			$this->External_db->add_picture($picture,$idShopify);

		}
		else {
			// Validation FAIL

			header('HTTP/1.0 403 Forbidden');
			exit();
		}

	}

	public function check_qty($sku,$stock)
	{

		$result = $this->External_db->get_qty($sku,$stock);

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

	//Retrieve the Shopify inventory levels
	public function shopify_level($sku)
	{

		$idInventory = $this->External_db->get_idInventory($sku);

		$this->load->model('Shopify_rest');

		$output = $this->Shopify_rest->get_inventoryLevel($idInventory);

		$array_result = json_decode($output, true);

		$inventory = $array_result['inventory_levels'];
		$qty = $inventory['0']['available'];

		return $qty;

	}

	//Check if DB Stock and Shopify are the same
	public function check_stock($sku)
	{

		$db_stock = $this->check_qty($sku,"");

		$shopify_stock = $this->shopify_level($sku);

		if ($shopify_stock <> $db_stock) {

			if ($this->External_db->checkError($sku)) {

				$this->External_db->stock_error_update($sku,$db_stock,$shopify_stock);

			}

			else{

				$this->External_db->stock_error_add($sku,$db_stock,$shopify_stock);

			}

		}

	}

	//
	public function daily_check($value='')
	{

		ini_set('max_execution_time', '300');
		set_time_limit(300);

		$counter = 0;

		//Get all SKUs
		$list = $this->External_db->get_skus();

		foreach ($list as $row) {

			$this->check_stock($row['sku']);

			$counter ++;

			usleep(500000);

		}

		//Log
		$message = 'Verificação de estoque finalizada. SKUs verificados: ' . $counter;
		$this->External_db->externalLog('0',$message);

	}

	/**
	 * Check if Shopify image is the same as database image for a given SKU
	 *
	 * @param string $sku The SKU to check
	 * @return void
	 */
	public function check_image($sku)
	{
		// Load required models
		$this->load->model('Product_db');
		$this->load->model('Shopify_rest');

		// Get product information from database
		$db_product = $this->Product_db->get_product_by_sku($sku);

		if (!$db_product) {
			echo "Product with SKU {$sku} not found in database.<br>";
			return;
		}

		$db_image_url = $db_product->picture;
		$idShopify = $db_product->idShopify;

		// Get product information from Shopify
		$shopify_product = $this->Shopify_rest->get_product($idShopify);
		$shopify_data = json_decode($shopify_product, true);

		if (!isset($shopify_data['product']) || !isset($shopify_data['product']['image']['src'])) {
			echo "Product with SKU {$sku} not found in Shopify or has no image.<br>";
			return;
		}

		$shopify_image_url = $shopify_data['product']['image']['src'];

		// Display both URLs
		echo "<h2>Image URLs for SKU: {$sku}</h2>";
		echo "<p><strong>Database Image URL:</strong> {$db_image_url}</p>";
		echo "<p><strong>Shopify Image URL:</strong> {$shopify_image_url}</p>";

		// Compare URLs
		if ($db_image_url === $shopify_image_url) {
			echo "<p style='color: green;'>✓ The image URLs match.</p>";
		} else {
			echo "<p style='color: red;'>✗ The image URLs do not match.</p>";

			// Show images side by side
			echo "<div style='display: flex; margin-top: 20px;'>";
			echo "<div style='margin-right: 20px;'>";
			echo "<h3>Database Image:</h3>";
			echo "<img src='{$db_image_url}' style='max-width: 300px; max-height: 300px;' />";
			echo "</div>";
			echo "<div>";
			echo "<h3>Shopify Image:</h3>";
			echo "<img src='{$shopify_image_url}' style='max-width: 300px; max-height: 300px;' />";
			echo "</div>";
			echo "</div>";
		}
	}

}
