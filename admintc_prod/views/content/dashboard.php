<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h3>
			Visão Geral <small>Informações do Dia</small>
		</h3>
	</div>
	<input type="hidden" name="store" value="<?php echo $this->session->userdata('store'); ?>">
</div>
<!-- /.row -->

<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-shopping-cart fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{counters.todaySales}}</div>
						<div>Vendas {{store | properCase}}</div>
					</div>
				</div>
			</div>
			<a href="/pages/sales_rprt">
				<div class="panel-footer">
					<span class="pull-left">Ver Detalhes</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-boxes fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{counters.pendingProds}}</div>
						<div>Produtos Pendentes</div>
					</div>
				</div>
			</div>
			<a href="/pages/fiscal">
				<div class="panel-footer">
					<span class="pull-left tc-font-blue">Ver Detalhes</span>
					<span class="pull-right tc-font-blue"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-exchange-alt fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{counters.pendingTxs}}</div>
						<div>Transferências</div>
					</div>
				</div>
			</div>
			<a href="/pages/fiscal">
				<div class="panel-footer">
					<span class="pull-left">Ver Detalhes</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-receipt fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{counters.pendingRecips}}</div>
						<div>Vendas Pendentes</div>
					</div>
				</div>
			</div>
			<a href="/pages/fiscal">
				<div class="panel-footer">
					<span class="pull-left tc-font-black">Ver Detalhes</span>
					<span class="pull-right tc-font-black"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
</div>
<!-- /.row -->

<div class="row">
	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-coffee fa-fw"></i>
					<span class="tc-left-15">Rotinas da Manhã</span>
				</h3>
			</div>
			<div class="panel-body">

				<div class="list-group">
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-clipboard-list"></i>
						 Verificar novas vendas online
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-box-open"></i>
						 Preparar vendas para envio
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-broom"></i>
						 Limpar/Arrumar o Salão
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-retweet"></i>
						 Repor produtos vendidos
					</a>
				</div>

			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-clock fa-fw"></i>
					<span class="tc-left-15">Rotinas Diárias</span>
				</h3>
			</div>
			<div class="panel-body">

				<div class="list-group">
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-truck"></i>
						 Enviar vendas online
					</a>
					<a href="#" class="list-group-item">
						<i class="fab fa-fw fa-whatsapp"></i>
						 Responder dúvidas clientes
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-edit"></i>
						 Fechar o caixa
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-utensils"></i>
						 Limpar a cozinha
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-trash-alt"></i>
						 Tirar o lixo
					</a>
				</div>

			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-headset fa-fw"></i>
					<span class="tc-left-15">Superbase</span>
				</h3>
			</div>
			<div class="panel-body">

				<div class="list-group">
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-book-open"></i>
						 Verificar/Ligar Clientes caderno
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-grin"></i>
						 Verificar/Ligar Vendas Abandonadas
					</a>
					<a href="#" class="list-group-item">
						<i class="fa fa-fw fa-file-invoice"></i>
						 Verificar/Ligar Boletos pendentes
					</a>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- /.row -->

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-money-bill-alt fa-fw"></i>
					<span class="tc-left-15">Vendas Registradas Hoje</span>
				</h3>
			</div>
			<div class="panel-body">

				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Número</th>
							<th>Registrado em</th>
							<th>Total</th>
							<th>Vendedor</th>
							<th>Finalizada</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="data in todaySales | filter: storeFilter">
							<td>{{data.idSales}}</td>
							<td>{{data.registerTime | onlyTime}}</td>
							<td>R$ {{data.totalPrice | dot2comma}}</td>
							<td>{{data.firstName}}</td>
							<td>
								<div class="tc-left-25" ng-if="isFiscal(data) == 1"><i class="fa fa-check"></i></div>
								<div class="tc-left-25" ng-if="isFiscal(data) == 0"><i class="fa fa-times"></i></div>
							</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>
<!-- /.row -->
