<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

	/**
	 *
	 * Controller responsible for Sales interactions
	 *
	 */

	public function __construct()
	{

		parent::__construct();
		$this->load->model('Sales_db');
		$this->load->model('Stock_db');
		$this->load->model('Other_db');

		$this->is_logged_in();

	}

	public function add_sale()
	{
		// Used in page "Registrar Vendas", salesController.js, salesClose function

		$postdata = file_get_contents("php://input");
		$arrayPostdata = json_decode($postdata,true);

		$date = $arrayPostdata['date'];
		$store = $arrayPostdata['store'];
		$discount = $arrayPostdata['discount'];
		$username = $arrayPostdata['username'];
		$totalPrice = $arrayPostdata['totalPrice'];
		$totalCost = $arrayPostdata['totalCost'];
		$salesTable = $arrayPostdata['salesTable'];
		$payTable = $arrayPostdata['payTable'];
		$freight = $arrayPostdata['freight'];

		// Check if quantity is OK before go ahead
		$valid = true;

		// Stock check
		foreach ($salesTable as $row) {

			$salesQty = $row['salesQty'];
			$orig_qty = $this->check_qty($row['sku'],$store);

			if ($salesQty <= $orig_qty) {
				//$output = $orig_qty - $salesQty;
			}
			else {
				$valid = false;
			}

		}

		// Quantity is OK
		if ($valid) {

			$idSales = $this->Sales_db->add($date,$store,$discount,$totalPrice,$username,$totalCost,$freight);

			foreach ($salesTable as $row) {

				$this->Sales_db->add_list($idSales,$row['discount'],$row['sku'],$row['salesPrice'],$row['salesQty'],$row['unitCost'],$row['supplier'],$row['type']);

				$data['origin'] = $store;
				$data['sku'] = $row['sku'];
				$salesQty = $row['salesQty'];
				$orig_qty = $this->check_qty($row['sku'],$store);
				$data['quantity'] = $orig_qty - $salesQty;

				$this->Stock_db->update2($data);

				//Update qty for Shopify
				//Updated to adapt to Shopify new stock inventory changes
				$this->load->model('Shopify_rest');
				$idInventory = $row['idInventory'];
				$qty = $this->check_qty($row['sku'],"");
				$result = $this->Shopify_rest->updt_qty_new($idInventory,$qty);

			}

			foreach ($payTable as $row1) {

				$this->Sales_db->add_pay($row1,$idSales);

			}

			$output = "Venda ".$idSales.", registrada por ".$this->session->userdata('username').".";
			$this->Other_db->internalLog('1',$output);
			$this->output->set_content_type('text/html')->set_output($output);

		}

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

	public function commission_info($username,$month1 = 0,$year1 = 0)
	{
		// Used in page "seller_rprt"
		// Set base commission
		$commission_rate1 = 0.003;
		$commission_rate2 = 0.003;
		$commission_msg1 = "Sem comissão configurada.";
		$commission_msg2 = "Sem comissão configurada.";

		// Get current and old month and year
		if ($month1 == 0) {
			$month1 = (int)date('m');
		}
		if ($year1 == 0) {
			$year1 = (int)date('Y');
		}

		if ($month1 == 1) {
				$month2 = 12; // December of the previous year
				$year2 = $year1 - 1;
		} else {
				$month2 = $month1 - 1; // Previous month
				$year2 = $year1;
		}

		// Change username small caps
		$smallcaps_username = strtolower($username);

		// Current month
		$query1 = $this->Sales_db->sales_seller($smallcaps_username,$year1,$month1);
		$sales1 = (float)$query1[0]['sales'];

		$query2 = $this->Sales_db->commission_seller($smallcaps_username,$year1,$month1);

		$personal1 = (float)$query2[0]['personal'];
		$store1 = (float)$query2[0]['store'];
		$business1 = (float)$query2[0]['business'];

		if ($personal1 > 0) {
			$commission_msg1 = "Em busca da meta!";
		}

		$query5 = $this->Sales_db->sales_store($query2[0]['mystore'],$year1,$month1);
		$sales_store1 = (float)$query5[0]['sales'];

		$query7 = $this->Sales_db->sales_terracotta($year1,$month1);
		$sales_terracotta1 = (float)$query7[0]['sales'];

		if ($sales1 / $personal1 >= 1) {
			$query['personal_target1'] = 1;
		} else {
			$query['personal_target1'] = $sales1 / $personal1;
		}

		if ($sales_store1 / $store1 >= 1.5) {
			$query['store_target1'] = 1.5;
		} else {
			$query['store_target1'] = $sales_store1 / $store1;
		}

		if ($sales_terracotta1 / $business1 >= 1.5) {
			$query['terracotta_target1'] = 1.5;
		} else {
			$query['terracotta_target1'] = $sales_terracotta1 / $business1;
		}

		if ($sales1 / $personal1 >= 1) {
			if ($sales_store1 / $store1 >= 1.5 && $sales_terracotta1 / $business1 >= 1.5) {
				$commission_rate1 = 0.007;
				$commission_msg1 = "Hipermeta Atingida!";
			} elseif ($sales_store1 / $store1 >= 1.5 && $sales_terracotta1 / $business1 >= 1.2) {
				$commission_rate1 = 0.006;
				$commission_msg1 = "Supermeta Atingida!";
			} elseif ($sales_store1 / $store1 >= 1.2 && $sales_terracotta1 / $business1 >= 1.5) {
				$commission_rate1 = 0.006;
				$commission_msg1 = "Supermeta Atingida!";
			} elseif ($sales_store1 / $store1 >= 1.2 && $sales_terracotta1 / $business1 >= 1.2) {
				$commission_rate1 = 0.005;
				$commission_msg1 = "Supermeta Atingida!";
			} elseif ($sales_store1 / $store1 >= 1.2 && $sales_terracotta1 / $business1 >= 0.9) {
				$commission_rate1 = 0.004;
				$commission_msg1 = "Meta Atingida!";
			} elseif ($sales_store1 / $store1 >= 0.9 && $sales_terracotta1 / $business1 >= 1.2) {
				$commission_rate1 = 0.004;
				$commission_msg1 = "Meta Atingida!";
			} elseif ($sales_store1 / $store1 >= 0.9 && $sales_terracotta1 / $business1 >= 0.9) {
				$commission_msg1 = "Meta Atingida!";
			}
		}

		$query['sales1'] = $sales1;
		$query['commission_rate1'] = $commission_rate1;
		$query['commission1'] = $sales1 * $commission_rate1;
		$query['commissionMsg1'] = $commission_msg1;

		$query['sales_store1'] = $sales_store1;

		// Last month
		$query3 = $this->Sales_db->sales_seller($smallcaps_username,$year2,$month2);
		$sales2 = (float)$query3[0]['sales'];

		$query4 = $this->Sales_db->commission_seller($smallcaps_username,$year2,$month2);

		if (count($query4) > 0) {

			$personal2 = (float)$query4[0]['personal'];
			$store2 = (float)$query4[0]['store'];
			$business2 = (float)$query4[0]['business'];

			if ($personal2 > 0) {
				$commission_msg2 = "Em busca da meta!";
			}

			$query6 = $this->Sales_db->sales_store($query4[0]['mystore'],$year2,$month2);
			$sales_store2 = (float)$query6[0]['sales'];

			$query8 = $this->Sales_db->sales_terracotta($year2,$month2);
			$sales_terracotta2 = (float)$query8[0]['sales'];

			if ($sales2 / $personal2 >= 1) {
				$query['personal_target2'] = 1;
			} else {
				$query['personal_target2'] = $sales2 / $personal2;
			}

			if ($sales_store2 / $store2 >= 1.5) {
				$query['store_target2'] = 1.5;
			} else {
				$query['store_target2'] = $sales_store2 / $store2;
			}

			if ($sales_terracotta2 / $business2 >= 1.5) {
				$query['terracotta_target2'] = 1.5;
			} else {
				$query['terracotta_target2'] = $sales_terracotta2 / $business2;
			}

			if ($sales2 / $personal2 >= 1) {
				if ($sales_store2 / $store2 >= 1.5 && $sales_terracotta2 / $business2 >= 1.5) {
					$commission_rate2 = 0.007;
					$commission_msg2 = "Hipermeta Atingida!";
				} elseif ($sales_store2 / $store2 >= 1.5 && $sales_terracotta2 / $business2 >= 1.2) {
					$commission_rate2 = 0.006;
					$commission_msg2 = "Supermeta Atingida!";
				} elseif ($sales_store2 / $store2 >= 1.2 && $sales_terracotta2 / $business2 >= 1.5) {
					$commission_rate2 = 0.006;
					$commission_msg2 = "Supermeta Atingida!";
				} elseif ($sales_store2 / $store2 >= 1.2 && $sales_terracotta2 / $business2 >= 1.2) {
					$commission_rate2 = 0.005;
					$commission_msg2 = "Supermeta Atingida!";
				} elseif ($sales_store2 / $store2 >= 1.2 && $sales_terracotta2 / $business2 >= 0.9) {
					$commission_rate2 = 0.004;
					$commission_msg2 = "Meta Atingida!";
				} elseif ($sales_store2 / $store2 >= 0.9 && $sales_terracotta2 / $business2 >= 1.2) {
					$commission_rate2 = 0.004;
					$commission_msg2 = "Meta Atingida!";
				} elseif ($sales_store2 / $store2 >= 0.9 && $sales_terracotta2 / $business2 >= 0.9) {
					$commission_msg2 = "Meta Atingida!";
				}
			}

			$query['sales2'] = $sales2;
			$query['commission_rate2'] = $commission_rate2;
			$query['commission2'] = $sales2 * $commission_rate2;
			$query['commissionMsg2'] = $commission_msg2;

			$query['sales_store2'] = $sales_store2;

		}

		// Send results
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