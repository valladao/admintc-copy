<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_db extends CI_Model {

	/**
	 *
	 * Sales_db Model
	 *
	 * Model responsible by all sales interactions with database
	 *
	 */

	public function __construct()
	{

		parent::__construct();

	}

	public function add($date,$store,$discount,$totalPrice,$username,$totalCost,$freight)
	{
		//Come from Sales Controller, add_sale function

		$data = array(
			'date' => $date ,
			'store' => $store ,
			'discount' => $discount ,
			'totalPrice' => $totalPrice ,
			'totalCost' => $totalCost ,
			'username' => $username
		);

		if ($freight != 0) {
			$data['freight'] = $freight;
		}

		$data['registerTime'] = date("Y-m-d H:i:s");

		$this->db->insert('sales', $data);

		return $this->db->insert_id();

	}

	public function add_list($idSales,$discount,$sku,$salesPrice,$salesQty,$unitCost,$supplier,$type)
	{
		//Come from Sales Controller, add_sale function

		$subtotal = ($salesPrice * $salesQty) - $discount;

		$data = array(
			'idSales' => $idSales ,
			'idVariant' => $sku ,
			'price' => $salesPrice ,
			'cost' => $unitCost ,
			'qty' => $salesQty ,
			'discount' => $discount ,
			'supplier' => $supplier ,
			'type' => $type ,
			'subtotal' => $subtotal
		);

		$this->db->insert('saleList', $data);

		return $this->db->insert_id();

	}

	public function add_pay($payData,$idSales)
	{
		//Come from Sales Controller, add_sale function

		$data = array(
			'payType' => $payData['payType'],
			'idSales' => $idSales,
			'value' => $payData['value']
		);

		if (isset($payData['banner'])) {
			$data['banner'] = $payData['banner'];
		}

		if (isset($payData['mode'])) {
			$data['mode'] = $payData['mode'];
		}

		if (isset($payData['installment'])) {
			$data['installment'] = $payData['installment'];
		}

		if (isset($payData['date'])) {
			$data['date'] = $payData['date'];
		}

		if (isset($payData['bank'])) {
			$data['bank'] = $payData['bank'];
		}

		if (isset($payData['channel'])) {
			$data['channel'] = $payData['channel'];
		}

		if (isset($payData['note'])) {
			$data['note'] = $payData['note'];
		}

		$this->db->insert('payList', $data);

		return $this->db->insert_id();

	}

	public function get_saleList($date)
	{
		//Come from Report Controller, daily function

		$this->db->where('date', $date);
		return $this->db->get('rpt_saleList')->result_array();

	}

	public function get_payList($date)
	{
		//Come from Report Controller, daily function

		$this->db->where('date', $date);
		return $this->db->get('rpt_payList')->result_array();

	}

	public function get_sale($idSales)
	{
		//Come from Report Controller, daily function

		$this->db->where('idSales', $idSales);
		return $this->db->get('dashSales')->result_array();

	}

	public function get_saleList2($idSales)
	{
		//Come from Report Controller, daily function

		$this->db->where('idSales', $idSales);
		return $this->db->get('rpt_saleList')->result_array();

	}

	public function get_payList2($idSales)
	{
		//Come from Report Controller, daily function

		$this->db->where('idSales', $idSales);
		return $this->db->get('rpt_payList')->result_array();

	}

	public function get_supplierReport($year)
	{
		//Build supplier report and get data

		$part1 = "SELECT `supplier`,
			SUM(`subtotal`) AS `total`,
			SUM(IF(EXTRACT(month FROM `date`) = 1,`subtotal`,0)) AS `Jan`,
			SUM(IF(EXTRACT(month FROM `date`) = 2,`subtotal`,0)) AS `Fev`,
			SUM(IF(EXTRACT(month FROM `date`) = 3,`subtotal`,0)) AS `Mar`,
			SUM(IF(EXTRACT(month FROM `date`) = 4,`subtotal`,0)) AS `Abr`,
			SUM(IF(EXTRACT(month FROM `date`) = 5,`subtotal`,0)) AS `Mai`,
			SUM(IF(EXTRACT(month FROM `date`) = 6,`subtotal`,0)) AS `Jun`,
			SUM(IF(EXTRACT(month FROM `date`) = 7,`subtotal`,0)) AS `Jul`,
			SUM(IF(EXTRACT(month FROM `date`) = 8,`subtotal`,0)) AS `Ago`,
			SUM(IF(EXTRACT(month FROM `date`) = 9,`subtotal`,0)) AS `Set`,
			SUM(IF(EXTRACT(month FROM `date`) = 10,`subtotal`,0)) AS `Out`,
			SUM(IF(EXTRACT(month FROM `date`) = 11,`subtotal`,0)) AS `Nov`,
			SUM(IF(EXTRACT(month FROM `date`) = 12,`subtotal`,0)) AS `Dez`
			FROM `sales`
			INNER JOIN `saleList` USING (idSales)
			WHERE EXTRACT(year FROM `date`) = ";
		$part2 = " GROUP BY `supplier`
			ORDER BY `total` DESC";

		$statement = $part1.$year.$part2;

		return $this->db->query($statement)->result_array();

	}

	public function sales_seller($username,$year,$month)
	{
		//Get sales per seller
		$part1 = "SELECT SUM(totalPrice) AS sales FROM sales WHERE EXTRACT(year FROM date) = ";
		$part2 = " AND EXTRACT(month FROM date) = ";
		$part3 = " AND username='";

		$statement = $part1.$year.$part2.$month.$part3.$username."'";

		return $this->db->query($statement)->result_array();

	}

	public function sales_store($store,$year,$month)
	{
		//Get sales per seller
		$part1 = "SELECT SUM(totalPrice) AS sales FROM sales WHERE EXTRACT(year FROM date) = ";
		$part2 = " AND EXTRACT(month FROM date) = ";
		$part3 = " AND store='";

		$statement = $part1.$year.$part2.$month.$part3.$store."'";

		return $this->db->query($statement)->result_array();

	}

	public function sales_terracotta($year,$month)
	{
		//Get sales per seller
		$part1 = "SELECT SUM(totalPrice) AS sales FROM sales WHERE EXTRACT(year FROM date) = ";
		$part2 = " AND EXTRACT(month FROM date) = ";

		$statement = $part1.$year.$part2.$month;

		return $this->db->query($statement)->result_array();

	}

	public function commission_seller($username,$year,$month)
	{
		//Get sales per seller
		$part1 = "SELECT `personal`,`store`,`business`,`mystore` FROM `commission` WHERE `seller`='";
		$part2 = "' AND `month`=";
		$part3 = " AND `year`=";

		$statement = $part1.$username.$part2.$month.$part3.$year;

		return $this->db->query($statement)->result_array();

	}

}