<uib-tabset active="activeTab">

	<uib-tab index="0" heading="Para Revisar">



		<div class="row">
		
			<form>
		
				<div class="col-sm-6 tc-top-10">
				
					<div class="form-group">
						<label>Buscar:</label>
						<input class="form-control input-sm" type="text" placeholder="Produto, SKU ou Cód. de Barras" ng-model="filter.search" ng-change="clearPriority()">
						<p>{{searchFilter}}</p>
					</div>
				
				</div>
				
				<div class="col-sm-3 tc-top-10">
				
					<div class="form-group">
						<label>Fornecedor:</label>
						<select class="form-control input-sm" ng-model="search.supplier">
							<option value=""></option>
							<option ng-repeat="option in suppliers" value="{{option.supplier}}">{{option.supplier}}</option>
						</select>
					</div>
				
				</div>
				
				<div class="col-sm-3 tc-top-10">
				
					<div class="form-group">
						<label>Tipo:</label>
						<select class="form-control input-sm" ng-model="search.type">
							<option value=""></option>
							<option ng-repeat="option in types" value="{{option.type}}">{{option.type}}</option>
						</select>
					</div>
				
				</div>
		
			</form>
		
		</div>
		
		<div class="row tc-pad-15">
		
			<table class="table table-bordered">
			
				<thead>
					<tr>
						<th>Foto</th>
						<th>Nome do Produto</th>
						<th>Modelo - Variação</th>
						<th>Categoria</th>
						<th>Fornecedor</th>
						<th>SKU</th>
						<th>Preço (R$)</th>
						<th>Qtd.</th>
						<th></th>
					</tr>
				</thead>
			
				<tbody>





				</tbody>
			
			</table>
		
			<ul uib-pagination total-items="filteredItems.length" ng-model="currentPage" ng-change="pageChanged()" items-per-page="itemsPerPage" max-size="maxSize" boundary-links="true" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></ul>
		
			<h4>Produtos encontrados: {{filteredItems.length}}</h4>
		
		</div>




	</uib-tab>

	<uib-tab index="1" heading="Editar">

	</uib-tab>

	<uib-tab index="2" heading="Feito">

	</uib-tab>

</uib-tabset>