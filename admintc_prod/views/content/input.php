<div class="row">

	<uib-tabset active="activeTab">

		<uib-tab index="0" heading="NFE">

			<table class="table table-bordered tc-top-20">

					<thead>
						<tr>
							<th>Número</th>
							<th>Fornecedor</th>
							<th>Romaneio</th>
							<th>Chave</th>
							<th>Data</th>
							<th>Valor Nota</th>
							<th></th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in tableData">
							<td>{{data.idNfes}}</td>
							<td>{{data.supplier}}</td>
							<td>{{data.romaneio}}</td>
							<td>{{data.nfeKey}}</td>
							<td>{{data.date}}</td>
							<td>R$ {{data.total}}</td>
							<td><button class="btn btn-success btn-xs" ng-click="selectNfe(data.idNfes,data.supplier,data.total)"><i class="fa fa-fw fa-forward"></i></button></td>
						</tr>
					</tbody>

			</table>

		</uib-tab>

		<uib-tab index="1" heading="Reposição" disable="disableTabs">

			<form class="tc-top-10">
		
				<div class="col-sm-4">
				
					<div class="form-group">
						<label>Buscar:</label>
						<input class="form-control input-sm" type="text" placeholder="Produto, SKU ou Cód. de Barras" ng-model="searchField" ng-change="forceFilter(searchField,search.supplier)">
					</div>
					
				</div>
				
				<div class="col-sm-3">
				
					<div class="form-group">
						<label>Tipo:</label>
						<select class="form-control input-sm"></select>
					</div>
				
				</div>
				
				<div class="col-sm-5">
			
					<div class="form-group">
						<p class="tc-bot-20"></p>
						<div class="col-sm-4">
							<button class="btn btn-primary form-control">Limpar</button>
						</div>
					</div>
				
				</div>
		
			</form>

			<div class="row tc-marginside-15">

				<table class="table table-bordered table-condensed">
				
					<thead>
						<tr>
							<th>Foto</th>
							<th>Nome do Produto</th>
							<th>Modelo - Variação</th>
							<th>Categoria</th>
							<th>SKU</th>
							<th>Preço (R$)</th>
							<th>Qtd.</th>
							<th></th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in (filteredItems = (productTable | 
						filter: search | 
						filter: search2
						)).slice((currentPage-1)*itemsPerPage,currentPage*itemsPerPage)">
							<td class="tc-center"><img ng-src="{{data.picture}}" width="75" height="100" ng-show="data.picture"></td>
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.type}}</td>
							<td>{{data.sku}}</td>
							<td>{{data.salesPrice}}</td>
							<td>{{data.total}}</td>
							<td class="text-center">
								<button class="btn btn-success btn-xs" ng-click="addProd(data.sku,data.salesPrice)">
									<i class="fa fa-fw fa-angle-double-right"></i>
								</button>
							</td>
						</tr>
					</tbody>
				
				</table>

			</div>

			<div class="row tc-sidepad-15">
				<div class="col-sm-6">
					<ul uib-pagination total-items="filteredItems.length" ng-model="currentPage" ng-change="pageChanged()" 	items-per-page="itemsPerPage" max-size="maxSize" class="tc-top-0"></ul>
				</div>
				<div class="col-sm-6">
					<button class="btn btn-success tc-right" ng-click="next()">Próximo <i class="fa fa-fw fa-angle-double-right"></i></button>
				</div>
			</div>

		</uib-tab>

		<uib-tab index="2" heading="Novos Produtos" disable="disableTabs">

			<div class="row">

				<div class="col-sm-6 tc-top-20">
					<form class="form-horizontal" name="newForm">
		
						<div class="form-group">
							<label class="control-label col-sm-4">Nome do Produto:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-model="products.title" ng-minlength="3" ng-maxlength="255" required>
							</div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-sm-4">Tipo:</label>
							<div class="col-sm-7">
								<select class="form-control" ng-model="products.type" required>
									<option ng-repeat="option in types" value="{{option.type}}">{{option.type}}</option>
								</select>
							</div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-sm-4">Modelo:</label>
							<div class="col-sm-7">
								<select class="form-control" ng-model="products.model" required>
									<option ng-repeat="option in models" value="{{option.model}}">{{option.model}}</option>
								</select>
							</div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-sm-4">Variação:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-model="products.variant" ng-minlength="3" ng-maxlength="55" required>
							</div>
						</div>
	
						<div class="form-group">
							<label class="control-label col-sm-4">Preço Final:</label>
							<div class="col-sm-5">
								<div class="input-group">
									<div class="input-group-addon">R$ </div>
									<input type="number" class="form-control input-sm" ng-model="products.salesPrice" required>
								</div>
							</div>
						</div>
	
						<div class="form-group">
							<label class="control-label col-sm-4">NCM:</label>
							<div class="col-sm-5" ng-class="{'has-error': newForm.ncm.$invalid && newForm.ncm.$touched}">
								<input type="text" name="ncm" class="form-control input-sm" ng-model="products.ncm" ng-minlength="8" ng-maxlength="8" required>
							</div>
						</div>
	
						<div class="form-group">
							<label class="control-label col-sm-4">Código de Barras:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-model="products.barcode">
							</div>
						</div>
	
					</form>

				</div>

				<div class="col-sm-6 tc-top-20">

					<div class="row">
						<button class="btn btn-success tc-left btn-lg" ng-click="newProd(newForm)">Criar</button>
					</div>			

					<div class="alert alert-warning row tc-top-20" role="alert" ng-if="alertNow2">Falta de informações para cadastrar produto!</div>

				</div>

			</div>

			<div class="row tc-marginside-15">

				<table class="table table-bordered table-condensed tc-top-20">
	
					<thead>
						<tr>
							<th>Nome do Produto</th>
							<th>Tipo</th>
							<th>Modelo</th>
							<th>Variação</th>
							<th>Preço Final</th>
							<th>NCM</th>
							<th>Código de Barras</th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in newProdTable">
							<td>{{data.title}}</td>
							<td>{{data.type}}</td>
							<td>{{data.model}}</td>
							<td>{{data.variant}}</td>
							<td>{{data.salesPrice}}</td>
							<td>{{data.ncm}}</td>
							<td>{{data.barcode}}</td>
						</tr>
					</tbody>
					
				</table>

			</div>

			<button class="btn btn-success tc-right"  ng-click="next()">Próximo <i class="fa fa-fw fa-angle-double-right"></i></button>

		</uib-tab>

		<uib-tab index="3" heading="Nova Variação" disable="disableTabs">

			<form class="tc-top-10">
		
				<div class="col-sm-4">
				
					<div class="form-group">
						<label>Buscar:</label>
						<input class="form-control input-sm" type="text" placeholder="Produto, SKU ou Cód. de Barras">
					</div>
					
				</div>
				
				<div class="col-sm-3">
				
					<div class="form-group">
						<label>Tipo:</label>
						<select class="form-control input-sm"></select>
					</div>
				
				</div>
				
				<div class="col-sm-5">
			
					<div class="form-group">
						<p class="tc-bot-20"></p>
						<div class="col-sm-4">
							<button class="btn btn-primary form-control">Limpar</button>
						</div>
					</div>
				
				</div>
		
			</form>

			<div class="row tc-marginside-15">

				<table class="table table-bordered table-condensed">
				
					<thead>
						<tr>
							<th>Foto</th>
							<th>Nome do Produto</th>
							<th>Modelo - Variação</th>
							<th>Categoria</th>
							<th>SKU</th>
							<th>Preço (R$)</th>
							<th></th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data2 in (filteredItems2 = (allProducts | filter: search)).slice((currentPage2-1)*itemsPerPage, currentPage2*itemsPerPage)">
							<td class="tc-center"><img ng-src="{{data.picture}}" width="75" height="100" ng-show="data.picture"></td>
							<td>{{data2.title}}</td>
							<td>{{data2.model}} - {{data2.variant}}</td>
							<td>{{data2.type}}</td>
							<td>{{data2.sku}}</td>
							<td>{{data2.salesPrice}}</td>
							<td class="text-center">
								<button class="btn btn-success btn-xs" ng-click="selectVar(data2.title,data2.model,data2.idProduct)">
									<i class="fa fa-fw fa-angle-down"></i>
								</button>
							</td>
						</tr>
					</tbody>
				
				</table>

			</div>

			<div class="row tc-sidepad-15">
				<div class="col-sm-6">
					<ul uib-pagination total-items="filteredItems2.length" ng-model="currentPage2" ng-change="pageChanged()" items-per-page="itemsPerPage" class="tc-top-0"></ul>
				</div>
				<div class="col-sm-6">
					<button class="btn btn-success tc-right" ng-click="next()">Próximo <i class="fa fa-fw fa-angle-double-right"></i></button>
				</div>
			</div>

			<div class="row" ng-hide="disableVars">

				<div class="col-sm-6 tc-top-20">
					<form class="form-horizontal" name="varForm">
		
						<div class="form-group">
							<label class="control-label col-sm-4">Nome do Produto:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-model="variable.title" disabled>
							</div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-sm-4">Modelo:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-model="variable.model" disabled>
							</div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-sm-4">Variação:</label>
							<div class="col-sm-7">
								<input type="text" name="variant" class="form-control input-sm" ng-model="variable.variant" required>
							</div>
						</div>
	
						<div class="form-group">
							<label class="control-label col-sm-4">Preço Final:</label>
							<div class="col-sm-5">
								<div class="input-group">
									<div class="input-group-addon">R$ </div>
									<input type="number" name="salesPrice" class="form-control infilter-sm" ng-model="variable.salesPrice" required>
								</div>
							</div>
						</div>
	
						<div class="form-group">
							<label class="control-label col-sm-4">NCM:</label>
							<div class="col-sm-5" ng-class="{'has-error': varForm.ncm.$invalid && varForm.ncm.$touched}">
								<input type="number" name="ncm" class="form-control input-sm" ng-model="variable.ncm" ng-minlength="8" ng-maxlength="8" required>
							</div>
						</div>
	
						<div class="form-group">
							<label class="control-label col-sm-4">Código de Barras:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-model="variable.barcode">
							</div>
						</div>
	
					</form>

				</div>

				<div class="col-sm-6 tc-top-20">

					<div class="row">
						<button class="btn btn-success tc-left btn-lg" ng-click="newVar(varForm)">Criar</button>
					</div>

					<div class="alert alert-warning row tc-top-20" role="alert" ng-if="alertNow3">Falta de informações para cadastrar variação!</div>

				</div>

			</div>

		</uib-tab>

		<uib-tab index="4" heading="Lista" disable="disableList">

			<div class="row tc-sidepad-15">

				<table class="table table-bordered table-condensed tc-top-20">
	
					<thead>
						<tr>
							<th>SKU</th>
							<th>Produto</th>
							<th>NCM</th>
							<th>Preço Final</th>
							<th>Custo</th>
							<th>Quantidade</th>
							<th>Subtotal</th>
							<th></th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in purchaseList">
							<td>{{data.sku}}</td>
							<td>{{data.title}} - {{data.model}}: {{data.variant}}</td>
							<td>{{data.ncm}}</td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">R$ </div>
									<input class="form-control input-sm" type="text" ng-model="data.newPrice">
								</div>
							</td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">R$ </div>
									<input class="form-control input-sm" type="text" ng-model="data.unitCost">
								</div>
							</td>
							<td>
								<input class="form-control input-sm" type="text" ng-model="data.quantity">
							</td>
							<td>{{data.unitCost * data.quantity | number:2}}</td>
							<td>
								<button class="btn btn-info btn-sm" ng-click="delList(data.idPurchaseList,data.sku)"><i class="fa fa-fw fa-times"></i></button>
							</td>
						</tr>
						<tr>
							<td colspan="4"></td>
							<td class="tc-bold" colspan="2">TOTAL</td>
							<td class="tc-bold">{{getTotal() | number:2}}</td>
							<td></td>
						</tr>
					</tbody>

					
				</table>

			</div>

			<div class="row tc-sidepad-15">

				<div class="col-sm-6">
					<h3>Total da Nota: R$ {{nfeTotal | number:2}}</h3>
					<div class="alert alert-success" role="alert" ng-if="alertNow">Lista de compra salva!</div>
				</div>

				<div class="col-sm-6">
					<button class="btn btn-success btn-lg tc-right col-sm-4 tc-left-25" ng-click="!isButtonClicked && (isButtonClicked=true) && closeNfe()" ng-dblclick="false" ng-disabled="isButtonClicked">Finalizar Compra</button>
					<button class="btn btn-primary tc-right col-sm-2" ng-click="saveNfe()">Salvar</button>
				</div>
				
			</div>

		</uib-tab>

	</uib-tabset>

</div>