<div class="row">

	<form>

		<div class="col-sm-6">
		
			<div class="form-group">
				<label>Buscar por SKU ou Código de Barras:</label>
				<input class="form-control input-sm" type="text" placeholder="SKU ou Código de Barras" ng-model="search.sku" ng-change="search2.barcode=search.sku">
			</div>

		</div>
		
	</form>

</div>

<div class="row">
	
	<uib-tabset>

		<uib-tab index="0" heading="Entre Loja">

			<div class="row tc-pad-15">

				<div class="col-sm-3">

					<input class="form-control" type="text" name="store" value="<?php echo ucfirst($this->session->userdata('store')); ?>" disabled>

				</div>

				<div class="col-sm-1 text-center">
	
					<h3 class="tc-top-0"> >>> </h3>

				</div>

				<div class="col-sm-3">
		
					<select class="form-control"  name="destStore" ng-model="destStore">
						<option ng-if="store == 'Moema'">Jardins</option>
						<option ng-if="store == 'Moema'">Museu</option>
						<option ng-if="store == 'Jardins'">Moema</option>
						<option ng-if="store == 'Jardins'">Museu</option>
						<option ng-if="store == 'Museu'">Moema</option>
						<option ng-if="store == 'Museu'">Jardins</option>
						<option ng-if="store == 'Morumbi'">Moema</option>
						<option ng-if="store == 'Morumbi'">Jardins</option>
						<option ng-if="store == 'Morumbi'">Museu</option>
					</select>

				</div>

			</div>

			<div class="row tc-pad-15"><div class="col-sm-12">
			
				<table class="table table-bordered">
				
					<thead>
						<tr>
							<th>SKU</th>
							<th>Nome do Produto</th>
							<th>Modelo - Variação</th>
							<th>Fornecedor</th>
							<th>Preço (R$)</th>
							<th>Qtd.</th>
							<th>Transferência</th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in (filteredItems = (
						productTable | 
						filter: search | 
						limitTo:3
						))">
							<td>{{data.sku}}</td>
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.supplier}}</td>
							<td>R$ {{data.salesPrice}}</td>
							<td ng-if="store == 'Moema'">{{data.store1}}</td>
							<td ng-if="store == 'Jardins'">{{data.store2}}</td>
							<td ng-if="store == 'Museu'">{{data.store3}}</td>
							<td ng-if="store == 'Morumbi'">{{data.store4}}</td>
							<td class="text-center">
								<button class="btn btn-success btn-xs" ng-click="select(data)">Selecionar</button>
							</td>
						</tr>
						<tr ng-repeat="data in productTable | filter: search2 | limitTo:3" ng-if="filteredItems.length == 0">
							<td>{{data.sku}}</td>
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.supplier}}</td>
							<td>R$ {{data.salesPrice}}</td>
							<td ng-if="store == 'Moema'">{{data.store1}}</td>
							<td ng-if="store == 'Jardins'">{{data.store2}}</td>
							<td ng-if="store == 'Museu'">{{data.store3}}</td>
							<td ng-if="store == 'Morumbi'">{{data.store4}}</td>
							<td class="text-center">
									<button class="btn btn-success btn-xs" ng-click="select(data)">Selecionar</button>
							</td>
						</tr>
					</tbody>
				
				</table>
			
			</div></div>

			<h4 class="tc-sidepad-15">Lista de Transferência</h4>

			<div class="row tc-pad-15"><div class="col-sm-12">
			
				<table class="table table-bordered">
				
					<thead>
						<tr>
							<th>SKU</th>
							<th>Nome do Produto</th>
							<th>Modelo - Variação</th>
							<th>Fornecedor</th>
							<th>Preço (R$)</th>
							<th colspan="3">Transferência</th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in newTxTable">
							<td>{{data.sku}}</td>
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.supplier}}</td>
							<td>R$ {{data.salesPrice}}</td>
							<td ng-if="store == 'Moema'">{{data.store1}}</td>
							<td ng-if="store == 'Jardins'">{{data.store2}}</td>
							<td ng-if="store == 'Museu'">{{data.store3}}</td>
							<td ng-if="store == 'Morumbi'">{{data.store4}}</td>
							<td class="text-center">
								
								<div class="btn-group">
									<button class="btn btn-default btn-xs" ng-click="lessQty(data)"><i class="fa fa-fw fa-angle-double-left"></i></button>
									<button class="btn btn-success btn-xs" ng-click="plusQty(data)"><i class="fa fa-fw fa-angle-double-right"></i></button>
								</div>

							</td>
							<td>{{data.txQty}}</td>
						</tr>
					</tbody>
				
				</table>
			
			</div></div>

			<div class="row tc-marginside-15">

				<div class="alert alert-danger col-sm-7" role="alert" ng-if="alertNow">{{alertMsg}}</div>

				<button class="btn btn-success btn-lg tc-right col-sm-3" ng-click="!isButtonClicked && (isButtonClicked=true) && sendTx()" ng-dblclick="false" ng-disabled="isButtonClicked">Enviar Transferência</button>
				
			</div>

		</uib-tab>

		<uib-tab index="1" heading="Cadastro Inicial" disable="true">

			<div class="row tc-pad-15">

				<form class="form-inline">
					<div class="form-group tc-sidepad-15">
						<label>Filtrar Fornecedor</label>

						<select class="form-control input-sm  tc-left-25" ng-model="search.supplier">
							<option value=""></option>
							<option ng-repeat="option in suppliers" value="{{option.supplier}}">{{option.supplier}}</option>
						</select>

					</div>
				</form>

			</div>

			<div class="row tc-pad-15"><div class="col-sm-12">
			
				<table class="table table-bordered">
				
					<thead>
						<tr>
							<th>SKU</th>
							<th>Nome do Produto</th>
							<th>Modelo - Variação</th>
							<th>Fornecedor</th>
							<th>Preço (R$)</th>
							<th colspan="4">Transferência</th>
						</tr>
					</thead>
				
					<tbody>

						<tr ng-repeat="data2 in (filteredItems2 = (
						tempStock | 
						filter: search
						)).slice(
							((currentPage-1)*itemsPerPage), ((currentPage)*itemsPerPage)
						)">
							<td>{{data2.sku}}</td>
							<td>{{data2.title}}</td>
							<td>{{data2.model}} - {{data2.variants}}</td>
							<td>{{data2.supplier}}</td>
							<td>R$ {{data2.salesPrice}}</td>
							<td>{{data2.stockQty - skuMove1[data2.sku] - skuMove2[data2.sku]}}</td>
							<td class="text-center">
									<button class="btn btn-info btn-xs" ng-click="toMoema(data2.sku,data2.stockQty)">>></button><br>
									<button class="btn btn-primary btn-xs" ng-click="toJardins(data2.sku,data2.stockQty)">>></button>
							</td>
							<td>
								Moema: {{skuMove1[data2.sku]}}<br>
								Jardins: {{skuMove2[data2.sku]}}
							</td>
							<td class="text-center">
								<button class="btn btn-success btn-xs" ng-click="sendSku(data2.sku,data2.stockQty)">
									<i class="fa fa-fw fa-check"></i>
								</button><br>
								<button class="btn btn-link btn-xs" ng-click="clearSku(data2.sku)">
									<i class="fa fa-fw fa-times tc-button-green"></i>
								</button>
							</td>
						</tr>
					</tbody>
				
				</table>

				<ul uib-pagination total-items="filteredItems2.length" ng-model="currentPage" ng-change="pageChanged()" items-per-page="itemsPerPage"></ul>

			
			</div></div>

		</uib-tab>

	</uib-tabset>

</div>