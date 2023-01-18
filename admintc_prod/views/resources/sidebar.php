<?php defined('BASEPATH') OR exit('No direct script access allowed');

	/**
	 * Sidebar menu
	 *
	 * Build links for internal pages
	 * Define the active page
	 *
	 */

?>

<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
	<ul class="nav navbar-nav side-nav">

		<li <?php $test1 = current_url(); $test2 = site_url('pages/sales'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/sales"><i class="fa fa-fw fa-credit-card"></i> Registrar Vendas</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/stock'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/stock"><i class="fa fa-fw fa-cubes"></i> Consultar Estoque</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/products'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/products"><i class="fa fa-fw fa-clone"></i> Editar Produtos</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/seller_rprt'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/seller_rprt"><i class="fa fa-fw fa-hand-holding-usd"></i> Minha Meta e Vendas</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/fiscal'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/fiscal"><i class="fa fa-fw fa-book"></i> Módulo Fiscal</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/transfer'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/transfer"><i class="fa fa-fw fa-random"></i> Transferência de Estoque</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/moves'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/moves"><i class="fa fa-fw fa-edit"></i> Outras Movimentações</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/input'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/input"><i class="fa fa-fw fa-truck"></i> Cadastrar Compra</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/sales_rprt'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/sales_rprt"><i class="fa fa-fw fa-chart-line"></i> Relatório de Vendas</a>
		</li>

		<li ng-if="false" <?php $test1 = current_url(); $test2 = site_url('pages/fiscal_rprt'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/fiscal_rprt"><i class="fa fa-fw fa-university"></i> Visão Fiscal</a>
		</li>

	<?php
	if ($this->session->userdata('privLevel') > 1 ) {
	?>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/financial'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/financial"><i class="fa fa-fw fa-calculator"></i> Módulo Financeiro</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/stock_issues'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/stock_issues"><i class="fa fa-fw fa-exclamation"></i> Problemas de Estoque</a>
		</li>

	<?php
	}
	?>

	<?php
	if ($this->session->userdata('privLevel') > 2 ) {
	?>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/supplier_rprt'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/supplier_rprt"><i class="fa fa-fw fa-chart-pie"></i> Vendas por Fornecedor</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/commision'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/commision"><i class="fa fa-fw fa-money-bill-alt"></i> Comissão Vendedores</a>
		</li>

	<?php
	}
	?>

	<?php
	if ($this->session->userdata('privLevel') > 3 ) {
	?>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/prod_page'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/prod_page"><i class="fa fa-fw fa-dollar-sign"></i> Cadastrar E-commerce</a>
		</li>

		<li <?php $test1 = current_url(); $test2 = site_url('pages/check_prod'); if ($test1 == $test2) { echo ' class="active"'; } ?>>
			<a href="/pages/check_prod"><i class="fa fa-fw fa-check-square"></i> Revisão de Produto</a>
		</li>

	<?php
	}
	?>

	</ul>
</div>
