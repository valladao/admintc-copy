<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!isset($content)) {
	$content = "";
}

switch ($content) {
	case 'dashboard':
		echo '<div ng-controller="dashboardController">';
		$this->load->view('content/dashboard');
		echo "</div>";
		break;

	case 'stock':
		echo '<div ng-controller="stockController">';
		$this->load->view('content/stock');
		echo "</div>";
		break;

	case 'products':
		echo '<div ng-controller="productsController">';
		$this->load->view('content/products');
		echo "</div>";
		break;

	case 'moves':
		echo '<div ng-controller="movesController">';
		$this->load->view('content/moves');
		echo "</div>";
		break;

	case 'sales':
		echo '<div ng-controller="salesController">';
		$this->load->view('content/sales');
		echo "</div>";
		break;

	case 'transfer':
		echo '<div ng-controller="transferController">';
		$this->load->view('content/transfer');
		echo "</div>";
		break;

	case 'input':
		echo '<div ng-controller="inputController">';
		$this->load->view('content/input');
		echo "</div>";
		break;

	case 'sales_rprt':
		echo '<div ng-controller="sales_rprtController">';
		$this->load->view('content/sales_rprt');
		echo "</div>";
		break;

	case 'fiscal':
		echo '<div ng-controller="fiscalController">';
		$this->load->view('content/fiscal');
		echo "</div>";
		break;

	case 'financial':
		echo '<div ng-controller="financialController">';
		$this->load->view('content/financial');
		echo "</div>";
		break;

	case 'fiscal_rprt':
		$this->load->view('content/fiscal_rprt');
		break;

	case 'supplier_rprt':
		echo '<div ng-controller="supplier_rprtController">';
		$this->load->view('content/supplier_rprt');
		echo "</div>";
		break;

	case 'prod_page':
		echo '<div ng-controller="prodpageController">';
		$this->load->view('content/prod_page');
		echo "</div>";
		break;

	case 'check_prod':
		echo '<div ng-controller="checkprodController">';
		$this->load->view('content/check_prod');
		echo "</div>";
		break;

	case 'stock_issues':
		echo '<div ng-controller="stockIssuesController">';
		$this->load->view('content/stock_issues');
		echo "</div>";
		break;

	case 'seller_rprt':
		echo '<div ng-controller="sellerRprtController">';
		$this->load->view('content/seller_rprt');
		echo "</div>";
		break;

	case 'commision':
		echo '<div ng-controller="commisionController">';
		$this->load->view('content/commision');
		echo "</div>";
		break;

	case 'password_change':
		echo '<div ng-controller="passwordChangeController">';
		$this->load->view('content/password_change');
		echo "</div>";
		break;

	default:
		redirect('pages');
		break;
}
