<div class="row">

	<form>

		<div class="col-sm-6">
		
			<div class="form-group">
				<label>Buscar por SKU ou Código de Barras:</label>
				<input class="form-control input-sm" type="text" placeholder="SKU ou Código de Barras" ng-model="skuInput" ng-change="checkSku()" ng-disabled="waitLoad">
			</div>

		</div>
		
	</form>

	<p class="tc-top-30">{{ loadMsg }}</p>

</div>

<div class="row">

	<uib-tabset>
		<uib-tab index="0" heading="SKU {{product.sku}}">
			
			<div class="col-sm-6 tc-top-20">
				<form class="form-horizontal">
	
					<div class="form-group">
						<label class="control-label col-sm-4">Nome do Produto:</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="text" class="form-control input-sm" ng-model="product.title" ng-disabled="locked">
								<div class="input-group-addon add-cursor" ng-click="editProduct()"><i class="fa fa-fw fa-edit"></i></div>
							</div>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4">Fornecedor:</label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" ng-model="product.supplier" disabled>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4">Tipo:</label>
						<div class="col-sm-7">
							<div class="input-group">
								<select class="form-control input-sm" ng-model="product.type" ng-disabled="locked">
									<option ng-repeat="option in types" value="{{option.type}}">{{option.type}}</option>
								</select>
								<div class="input-group-addon add-cursor" ng-click="editProduct()"><i class="fa fa-fw fa-edit"></i></div>
							</div>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4">Modelo:</label>
						<div class="col-sm-7">
							<div class="input-group">
								<select class="form-control input-sm" ng-model="product.model" ng-disabled="locked">
									<option ng-repeat="option in models" value="{{option.model}}">{{option.model}}</option>
								</select>
								<div class="input-group-addon add-cursor" ng-click="editProduct()"><i class="fa fa-fw fa-edit"></i></div>
							</div>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4">Variação:</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="text" class="form-control input-sm" ng-model="product.variant" ng-disabled="locked">
								<div class="input-group-addon add-cursor" ng-click="editProduct()"><i class="fa fa-fw fa-edit"></i></div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">SKU:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control input-sm" ng-model="product.sku" disabled>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">Preço:</label>
						<div class="col-sm-5">
							<div class="input-group">
								<div class="input-group-addon">R$ </div>
								<input type="text" class="form-control input-sm" ng-model="product.salesPrice" ng-disabled="locked">
								<div class="input-group-addon add-cursor" ng-click="editProduct()"><i class="fa fa-fw fa-edit"></i></div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">Estoque:</label>
						<div class="col-sm-3">
							<table class="table table-bordered">
								<thead>
									<th>Moema</th>
									<th>Jardins</th>
									<th>Museu</th>
									<th>Morumbi</th>
									<th>Total</th>
								</thead>
								<tbody>
									<tr>
										<td>{{product.store1}}</td>
										<td>{{product.store2}}</td>
										<td>{{product.store3}}</td>
										<td>{{product.store4}}</td>
										<td>{{product.total}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
		
					<div class="form-group">
						<label class="control-label col-sm-4">Código de Barras:</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="text" class="form-control input-sm" ng-model="product.barcode" ng-disabled="locked">
								<div class="input-group-addon add-cursor" ng-click="editProduct()"><i class="fa fa-fw fa-edit"></i></div>
							</div>
						</div>
					</div>

				</form>
			</div>

			<div class="col-sm-6 tc-top-20">

				<div class="row">
					<img ng-src="{{product.picture}}" alt="..." class="img-thumbnail" width="225" height="300" ng-show="product.picture">
				</div>

				<div class="row tc-top-20">
					<button class="btn btn-info btn-sm" ng-click="delProd(product.total,product.sku)">Apagar</button>
				</div>

				<div class="row tc-top-20">
					<div class="alert alert-danger col-sm-7" role="alert" ng-if="alertNow">{{alertMsg}}</div>
				</div>

			</div>
		
		</uib-tab>

		<uib-tab index="1" heading="Fiscal" ng-click="clickFiscal()">

			<div class="col-sm-6 tc-top-20">
				<form class="form-horizontal">
	
					<div class="form-group">
						<label class="control-label col-sm-4">NCM:</label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" ng-model="product.ncm" disabled>
						</div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-4">Custo:</label>
						<div class="col-sm-5">
							<div class="input-group">
								<div class="input-group-addon">R$ </div>
								<input type="text" class="form-control input-sm" ng-model="product.unitCost" disabled>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-4">Origem:</label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" ng-model="product.origin" disabled>
						</div>
					</div>
	
				</form>

			</div>

		</uib-tab>

		<uib-tab index="2" heading="NFEs">

			<div class="row tc-pad-15">

				<table class="table table-bordered">
					<thead>
						<th>idNFE</th>
						<th>Data</th>
						<th>Chave</th>
						<th>Qtd.</th>
						<th>Preço</th>
						<th>Custo</th>
					</thead>
					<tbody>
						<tr ng-repeat="data in purchase">
							<td>{{data.idNfes}}</td>
							<td>{{data.date}}</td>
							<td>{{data.nfeKey}}</td>
							<td>{{data.quantity}}</td>
							<td>{{data.newPrice}}</td>
							<td>{{data.unitCost}}</td>
						</tr>
					</tbody>
				</table>

			</div>

		</uib-tab>

		<uib-tab index="3" heading="Site">

			<h3>Painel Site... em construção</h3>

		</uib-tab>

		<uib-tab index="4" heading="Variações">

			<div class="row tc-pad-15">

				<table class="table table-bordered">
					<thead>
						<th>Modelo</th>
						<th>Variação</th>
						<th>SKU</th>
						<th>Qtd.</th>
						<th>Preço</th>
					</thead>
					<tbody>
						<tr ng-repeat="data in variants">
							<td>{{data.model}}</td>
							<td>{{data.variant}}</td>
							<td>{{data.sku}}</td>
							<td>Moema: {{data.store1}}<br>Jardins: {{data.store2}}</td>
							<td>{{data.salesPrice}}</td>
						</tr>
					</tbody>
				</table>

			</div>

		</uib-tab>

		<uib-tab index="5" heading="Vendas">

			<div class="row tc-pad-15">

				<table class="table table-bordered">
					<thead>
						<th>Venda</th>
						<th>Data</th>
						<th>Loja</th>
						<th>Vendedor</th>
						<th>Preço</th>
						<th>Qtd.</th>
						<th>Desconto</th>
						<th>Subtotal</th>
					</thead>
					<tbody>
						<tr ng-repeat="data in sales">
							<td>{{data.idSales}}</td>
							<td>{{data.date}}</td>
							<td>{{data.store | properCase}}</td>
							<td>{{data.username | properCase}}</td>
							<td>{{data.price}}</td>
							<td>{{data.qty}}</td>
							<td>{{data.discount}}</td>
							<td>{{data.subtotal}}</td>
						</tr>
					</tbody>
				</table>

			</div>

		</uib-tab>

		<uib-tab index="6" heading="Movimentações">

			<div class="row tc-pad-15">
			
				<table class="table table-bordered">
					<thead>
						<th>Data</th>
						<th>idMoves</th>
						<th>Alteração</th>
						<th>Funcionário</th>
						<th>Loja</th>
						<th>Troca</th>
						<th>Motivo</th>
						<th>Nota</th>
					</thead>
					<tbody ng-repeat="data in moves">
						<td>{{data.date}}</td>
						<td>{{data.idMoves}}</td>
						<td>{{data.changeQty}}</td>
						<td>{{data.username}}</td>
						<td>{{data.store}}</td>
						<td>{{data.changeSale}}</td>
						<td>{{data.reason}}</td>
						<td>{{data.note}}</td>
					</tbody>
				</table>

			</div>
			
		</uib-tab>

		<uib-tab index="7" heading="Transferências">

			<div class="row tc-pad-15">
			
				<table class="table table-bordered">
					<thead>
						<th>idTransfer</th>
						<th>Origem</th>
						<th>Destino</th>
						<th>Qtd.</th>
					</thead>
					<tbody ng-repeat="data in transfers">
						<td>{{data.idTransferlist}}</td>
						<td>{{data.origin}}</td>
						<td>{{data.destination}}</td>
						<td>{{data.txQty}}</td>
					</tbody>
				</table>

			</div>
			
		</uib-tab>

	</uib-tabset>

</div>