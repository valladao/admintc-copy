<div class="row">

	<form name="searchForm">

		<div class="col-sm-6">
		
			<div class="form-group">
				<label>Buscar por SKU:</label>
				<input class="form-control input-sm" type="text" name="search" placeholder="SKU" ng-model="skuInput">
				<input class="form-control" type="hidden" name="username" value="<?php echo ucfirst($this->session->userdata('firstName')); ?>" disabled>
				<input class="form-control" type="hidden" name="store" value="<?php echo ucfirst($this->session->userdata('store')); ?>" disabled>
			</div>

		</div>
		
	</form>

</div>

<div class="row">

	<uib-tabset active="activeTab">

		<uib-tab index="0" heading="Lista" ng-click="back2list()">

			<div class="row tc-pad-15">

				<table class="table table-bordered">
					<thead>
						<th>Foto</th>
						<th>Nome do Produto</th>
						<th>Modelo - Variação</th>
						<th>Categoria</th>
						<th>Fornecedor</th>
						<th>SKU</th>
						<th>Preço (R$)</th>
						<th>Qtd.</th>
						<th></th>
					</thead>
					<tbody>
						<tr ng-if="searchForm.$dirty" ng-repeat="data in (filteredItems = (
						productTable | 
						filter: skuFilter
						))
						">
							<td class="tc-center">
								<img ng-src="{{data.picture}}" width="75" height="100" ng-show="data.picture">
							</td>
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.type}}</td>
							<td>{{data.supplier}}</td>
							<td>{{data.sku}}</td>
							<td>R$ {{data.salesPrice}}</td>
							<td ng-if="store == 'Moema'">{{data.store1}}</td>
							<td ng-if="store == 'Jardins'">{{data.store2}}</td>
							<td ng-if="store == 'Museu'">{{data.store3}}</td>
							<td ng-if="store == 'Morumbi'">{{data.store4}}</td>
							<td class="text-center">
								<button class="btn btn-success btn-xs" ng-click="select(data)"><i class="fa fa-fw fa-angle-double-down"></i></button>
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<h4>Lista de Movimentação:</h4>

			<div class="row tc-pad-15">

				<table class="table table-bordered">
					<thead>
						<th>Nome do Produto</th>
						<th>Modelo - Variação</th>
						<th>SKU</th>
						<th>Categoria</th>
						<th>Fornecedor</th>
						<th>Qtd. Inicio</th>
						<th>Alteração</th>
						<th>Qtd. Fim</th>
					</thead>
					<tbody>
						<tr ng-repeat="data in moveTable">
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.sku}}</td>
							<td>{{data.type}}</td>
							<td>{{data.supplier}}</td>
							<td>{{data.total}}</td>
							<td><div class="input-group">
								<div class="input-group-addon" ng-click="lessQty(data.changeQty,data.total,$index)"><i class="fa fa-fw fa-minus"></i></div>
								<input type="number" class="form-control input-sm" ng-model="data.changeQty" min="0">
								<div class="input-group-addon" ng-click="plusQty(data.changeQty,$index)"><i class="fa fa-fw fa-plus"></i></div>
							</div></td>
							<td>
								{{data.total + data.changeQty}}
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<div class="row tc-pad-15">

				<button class="btn btn-success tc-right" ng-click="closeList()">Proseguir >></button>

			</div>

		</uib-tab>

		<uib-tab index="1" heading="Fechar" disable="disableClose">
			
			<div class="row tc-pad-15">

				<p class="lead tc-pad-15">Produtos movimentados:</p>
				
				<table class="table table-bordered">
					<thead>
						<th>Nome do Produto</th>
						<th>Modelo - Variação</th>
						<th>SKU</th>
						<th>Categoria</th>
						<th>Fornecedor</th>
						<th>Qtd. Inicio</th>
						<th>Alteração</th>
						<th>Qtd. Fim</th>
					</thead>
					<tbody>
						<tr ng-repeat="data in moveTable">
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.sku}}</td>
							<td>{{data.type}}</td>
							<td>{{data.supplier}}</td>
							<td>{{data.total}}</td>
							<td>{{data.changeQty}}</td>
							<td>
								{{data.total + data.changeQty}}
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<div class="row tc-pad-15 col-sm-9">


				<form class="form-horizontal">
	
					<div class="form-group">
						<label class="control-label col-sm-4">Justificativa:</label>
						<div class="col-sm-5">
								<select class="form-control input-sm" ng-model="move.reason">
									<option>Produto quebrado na loja</option>
									<option>Produto desaparecido</option>
									<option>Produto chegou quebrado</option>
									<option>Produto na nota, mas não chegou</option>
									<option>Compra cancelada na loja por erro</option>
									<option>Compra cancelada na Internet</option>
									<option>Devolução de compra</option>
									<option>Produto reencontrado no estoque</option>
									<option>Presente ou brinde</option>
									<option>Ramificando em vários produtos</option>
								</select>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4">Nota de Explicação:</label>
						<div class="col-sm-7">
							<textarea class="form-control input-sm" rows="5" ng-model="move.note"></textarea>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4"># Venda:<br><small>(Devoluções)</small></label>
						<div class="col-sm-3">
							<input type="text" class="form-control input-sm" ng-model="move.changeSale">
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4"></label>
						<div class="col-sm-3">
							<div class="input-group">
								<button class="btn btn-info btn-sm" ng-click="!isButtonClicked && (isButtonClicked=true) && sendMove()" ng-dblclick="false" ng-disabled="isButtonClicked">Enviar</button>
							</div>
						</div>
					</div>
		
				</form>

			</div>

		</uib-tab>

	</uib-tabset>

</div>