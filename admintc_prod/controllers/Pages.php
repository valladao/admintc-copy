<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	/**
	 * Controller Pages
	 *
	 * To build each internal page HTML base code
	 * Check user login
	 * Based on url/routes set $view_data with proper content
	 * Load the base_html view
	 *
	 */

	public function __construct()
	{

		parent::__construct();

		$this->is_logged_in();

	}

	public function index()
	{

		$this->dashboard();

	}

	public function dashboard()
	{

		$view_data['content'] = 'dashboard';
		$view_data['title'] = 'Dashboard';

		$this->load->view('base_html', $view_data);

	}

	public function stock()
	{

		$view_data['content'] = 'stock';
		$view_data['title'] = 'Consultar Estoque';

		$this->load->view('base_html', $view_data);

	}

	public function products()
	{

		$view_data['content'] = 'products';
		$view_data['title'] = 'Editar Produtos';

		$this->load->view('base_html', $view_data);

	}

	public function moves()
	{

		$view_data['content'] = 'moves';
		$view_data['title'] = 'Outras Movimentações';

		$this->load->view('base_html', $view_data);

	}

	public function sales()
	{

		$view_data['content'] = 'sales';
		$view_data['title'] = 'Registrar Vendas';

		$this->load->view('base_html', $view_data);

	}

	public function transfer()
	{

		$view_data['content'] = 'transfer';
		$view_data['title'] = 'Transferência de Estoque';

		$this->load->view('base_html', $view_data);

	}

	public function input()
	{

		$view_data['content'] = 'input';
		$view_data['title'] = 'Cadastrar Compra';

		$this->load->view('base_html', $view_data);

	}

	public function sales_rprt()
	{

		$view_data['content'] = 'sales_rprt';
		$view_data['title'] = 'Relatório de Vendas';

		$this->load->view('base_html', $view_data);

	}

	public function fiscal()
	{

		$view_data['content'] = 'fiscal';
		$view_data['title'] = 'Módulo Fiscal';

		$this->load->view('base_html', $view_data);

	}

	public function financial()
	{

		$view_data['content'] = 'financial';
		$view_data['title'] = 'Módulo Financeiro';

		$this->load->view('base_html', $view_data);

	}

	public function fiscal_rprt()
	{

		$view_data['content'] = 'fiscal_rprt';
		$view_data['title'] = 'Visão Fiscal';

		$this->load->view('base_html', $view_data);

	}

	public function supplier_rprt()
	{

		$view_data['content'] = 'supplier_rprt';
		$view_data['title'] = 'Vendas por Fornecedor';

		$this->load->view('base_html', $view_data);

	}

	public function prod_page()
	{

		$view_data['content'] = 'prod_page';
		$view_data['title'] = 'Cadastrar E-commerce';

		$this->load->view('base_html', $view_data);

	}

	public function check_prod()
	{

		$view_data['content'] = 'check_prod';
		$view_data['title'] = 'Revisar Produtos do E-commerce';

		$this->load->view('base_html', $view_data);

	}

	public function stock_issues()
	{

		$view_data['content'] = 'stock_issues';
		$view_data['title'] = 'Problemas de Estoque';

		$this->load->view('base_html', $view_data);

	}

	public function seller_rprt()
	{

		$view_data['content'] = 'seller_rprt';
		$view_data['title'] = 'Minha Meta e Vendas';

		$this->load->view('base_html', $view_data);

	}

	public function commision()
	{

		$view_data['content'] = 'commision';
		$view_data['title'] = 'Comissão Vendedores';

		$this->load->view('base_html', $view_data);

	}

	public function password_change()
	{

		$view_data['content'] = 'password_change';
		$view_data['title'] = 'Troque sua Senha';

		$this->load->view('base_html', $view_data);

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