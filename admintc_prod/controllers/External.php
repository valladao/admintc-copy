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
	 * Get product images from database and Shopify
	 *
	 * @param string $sku The SKU to look up
	 * @return array|null Array with db_image_url, shopify_image_url, and idShopify, or null if not found
	 */
	private function _get_product_images($sku)
	{
		// Load Shopify_rest model
		$this->load->model('Shopify_rest');

		// Get product information from database
		$db_product = $this->External_db->get_product_by_sku($sku);

		if (!$db_product) {
			return null;
		}

		$db_image_url = $db_product->picture;
		$idShopify = $db_product->idShopify;

		// Get product information from Shopify using output buffering to capture the output
		ob_start();
		$this->Shopify_rest->get_product($idShopify);
		$shopify_product = ob_get_clean();

		$shopify_data = json_decode($shopify_product, true);

		if (!isset($shopify_data['product']) || !isset($shopify_data['product']['image']['src'])) {
			return null;
		}

		$shopify_image_url = $shopify_data['product']['image']['src'];

		return [
			'db_image_url' => $db_image_url,
			'shopify_image_url' => $shopify_image_url,
			'idShopify' => $idShopify
		];
	}

	/**
	 * Check if Shopify image is the same as database image for a given SKU
	 *
	 * @param string $sku The SKU to check
	 * @return void
	 */
	public function check_image($sku)
	{
		// Get product images
		$images = $this->_get_product_images($sku);

		if (!$images) {
			echo "Product with SKU {$sku} not found in database or Shopify, or has no image.<br>";
			return;
		}

		$db_image_url = $images['db_image_url'];
		$shopify_image_url = $images['shopify_image_url'];

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

			// Add button to update database with Shopify image
			echo "<div style='margin-top: 20px;'>";
			echo "<a href='/external/update_image/{$sku}' style='padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; display: inline-block;'>Update Database with Shopify Image</a>";
			echo "</div>";
		}
	}

	/**
	 * Update database image with Shopify image for a given SKU
	 *
	 * @param string $sku The SKU to update
	 * @return void
	 */
	public function update_image($sku)
	{
		// Get product images
		$images = $this->_get_product_images($sku);

		if (!$images) {
			echo "Product with SKU {$sku} not found in database or Shopify, or has no image.<br>";
			return;
		}

		$db_image_url = $images['db_image_url'];
		$shopify_image_url = $images['shopify_image_url'];
		$idShopify = $images['idShopify'];

		// Update database with Shopify image
		$this->External_db->add_picture($shopify_image_url, $idShopify);

		// Log the update
		$message = "Updated image for SKU {$sku} from '{$db_image_url}' to '{$shopify_image_url}'";
		$this->External_db->externalLog('0', $message);

		// Display success message
		echo "<h2>Image Updated for SKU: {$sku}</h2>";
		echo "<p style='color: green;'>✓ The database has been updated with the Shopify image.</p>";
		echo "<p><strong>Old Image URL:</strong> {$db_image_url}</p>";
		echo "<p><strong>New Image URL:</strong> {$shopify_image_url}</p>";

		// Show images side by side
		echo "<div style='display: flex; margin-top: 20px;'>";
		echo "<div style='margin-right: 20px;'>";
		echo "<h3>Old Image:</h3>";
		echo "<img src='{$db_image_url}' style='max-width: 300px; max-height: 300px;' />";
		echo "</div>";
		echo "<div>";
		echo "<h3>New Image:</h3>";
		echo "<img src='{$shopify_image_url}' style='max-width: 300px; max-height: 300px;' />";
		echo "</div>";
		echo "</div>";

		// Add link to go back to check_image
		echo "<div style='margin-top: 20px;'>";
		echo "<a href='/external/check_image/{$sku}' style='padding: 10px; background-color: #2196F3; color: white; text-decoration: none; display: inline-block;'>Go Back to Check Image</a>";
		echo "</div>";
	}

	/**
	 * Update all product images in the database with Shopify images
	 *
	 * @return void
	 */
	public function update_all_images()
	{
		// Set longer execution time for processing many products
		ini_set('max_execution_time', '600');
		set_time_limit(600);

		// Get all SKUs
		$skus = $this->External_db->get_skus();

		// Initialize counters
		$total = count($skus);
		$updated = 0;
		$skipped = 0;
		$errors = 0;
		$updated_skus = [];

		// Start HTML output
		echo "<h2>Updating All Product Images</h2>";
		echo "<p>Total products to process: {$total}</p>";
		echo "<div id='progress' style='margin: 20px 0;'></div>";

		// Flush output to show progress
		flush();

		// Process each SKU
		foreach ($skus as $index => $row) {
			$sku = $row['sku'];
			$current = $index + 1;

			// Update progress
			$percent = round(($current / $total) * 100);
			echo "<script>document.getElementById('progress').innerHTML = 'Processing {$current} of {$total} ({$percent}%): SKU {$sku}';</script>";
			flush();

			// Get product images
			$images = $this->_get_product_images($sku);

			if (!$images) {
				// Skip products with no images or not found in Shopify
				$skipped++;
				continue;
			}

			$db_image_url = $images['db_image_url'];
			$shopify_image_url = $images['shopify_image_url'];
			$idShopify = $images['idShopify'];

			// Check if images match
			if ($db_image_url !== $shopify_image_url) {
				// Update database with Shopify image
				$this->External_db->add_picture($shopify_image_url, $idShopify);

				// Log the update
				$message = "Batch update: Updated image for SKU {$sku} from '{$db_image_url}' to '{$shopify_image_url}'";
				$this->External_db->externalLog('0', $message);

				$updated++;
				$updated_skus[] = $sku;
			} else {
				// Images already match
				$skipped++;
			}

			// Delay to prevent Shopify API rate limiting (0.5 seconds as recommended by Shopify)
			usleep(500000);
		}

		// Display summary
		echo "<h3>Update Complete</h3>";
		echo "<p><strong>Total processed:</strong> {$total}</p>";
		echo "<p><strong>Updated:</strong> {$updated}</p>";
		echo "<p><strong>Skipped (already matching or no image):</strong> {$skipped}</p>";
		echo "<p><strong>Errors:</strong> {$errors}</p>";

		// Log summary
		$summary = "Batch image update completed. Total: {$total}, Updated: {$updated}, Skipped: {$skipped}, Errors: {$errors}";
		$this->External_db->externalLog('0', $summary);

		// Display list of updated SKUs
		if ($updated > 0) {
			echo "<h3>Updated SKUs:</h3>";
			echo "<ul>";
			foreach ($updated_skus as $sku) {
				echo "<li>{$sku}</li>";
			}
			echo "</ul>";

			// Log list of updated SKUs
			$sku_list = implode(", ", $updated_skus);
			$this->External_db->externalLog('0', "Batch image update - Updated SKUs: {$sku_list}");
		}

		// Add link to go back
		echo "<div style='margin-top: 20px;'>";
		echo "<a href='/external' style='padding: 10px; background-color: #2196F3; color: white; text-decoration: none; display: inline-block;'>Go Back</a>";
		echo "</div>";
	}
}
