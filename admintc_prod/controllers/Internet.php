<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Internet extends CI_Controller {
	
	/**
	 * Internet Controller
	 * 
	 * To control interactions relater do E-commerce and Marketplace
	 * 
	 */

	public function __construct()
	{

		parent::__construct();

		$this->is_logged_in();
		$this->load->model('Other_db');

	}

	public function get_newProdList()
	{
		// To get new product list 
		// Products with no e-commerce information pending create as draft
		// Come from prodpageController, getNewProdList function

		$query = $this->Other_db->get_newProdList();

		$this->output->set_content_type('application/json')->set_output(json_encode($query));

	}

	public function get_shopifyInfo($idProduct)
	{
		// Go to shopify to get the Shopify already saved descriotion and tags
		// Come from prodpageController, getNewProdList function
		$this->load->model('Product_db');
		$this->load->model('Shopify_rest');

		// Get idShopify
		$result = $this->Product_db->get_idshopify($idProduct);
		$temp = $result->row();
		$idShopify = intval($temp->idShopify);

		$query = $this->Shopify_rest->get_product($idShopify);

		$this->output->set_content_type('application/json')->set_output($query);

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