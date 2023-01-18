<div class="row">

	<form>

		<div class="col-sm-6">
		
			<div class="form-group">
				<label>Buscar:</label>
				<input class="form-control input-sm" type="text" placeholder="Produto, SKU ou Cód. de Barras" ng-model="searchFilter" ng-change="clearPriority()">
			</div>
		
			<div class="row">
				<div class="col-sm-3">
					<button class="btn btn-primary" ng-click="cleanSearch()">Limpar</button>
				</div>
			
				<div class="col-sm-3 col-sm-offset-6">
					<select class="form-control input-sm" ng-model="itemsPerPage">
						<option>5</option>
						<option>10</option>
						<option>25</option>
						<option>50</option>
					</select>
				</div>
			</div>
			
		</div>
		
		<div class="col-sm-3">
		
			<div class="form-group">
				<label>Fornecedor:</label>
				<select class="form-control input-sm" ng-model="search.supplier">
					<option value=""></option>
					<option ng-repeat="option in suppliers" value="{{option.supplier}}">{{option.supplier}}</option>
				</select>
			</div>
		
			<label class="checkbox-inline">
				<input type="checkbox" ng-model="onlyZero">Só Zerados
			</label>
		
			<label class="checkbox-inline">
				<input type="checkbox" ng-model="nonZero">Não Zerados
			</label>
		
		</div>
		
		<div class="col-sm-3">
		
			<div class="form-group">
				<label>Tipo:</label>
				<select class="form-control input-sm" ng-model="search.type">
					<option value=""></option>
					<option ng-repeat="option in types" value="{{option.type}}">{{option.type}}</option>
				</select>
			</div>
		
			<label class="checkbox-inline">
				<input type="checkbox" ng-model="hasPicture">Com Fotos
			</label>
			<label class="checkbox-inline">
				<input type="checkbox" ng-model="noPicture">Sem Fotos
			</label>
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
			<tr ng-repeat="data in (filteredItems = (
			productTable | 
			filter: ignoreAccents | 
			filter: search | 
			filter: (nonZero || '') && greaterThan('total', 0) | 
			filter: (onlyZero || '') && equalZero('total') | 
			filter: (hasPicture || '') && emptyOrNull | 
			filter: (noPicture || '') && notEmpty
			)).slice(
				((currentPage-1)*itemsPerPage), ((currentPage)*itemsPerPage)
			)">
				<td class="tc-center"><img ng-src="{{data.picture}}" width="75" height="100" ng-show="data.picture"></td>
				<td>{{data.title}}</td>
				<td>{{data.model}} - {{data.variant}}</td>
				<td>{{data.type}}</td>
				<td>{{data.supplier}}</td>
				<td>{{data.sku}}</td>
				<td>{{data.salesPrice}}</td>
				<td>Moema: {{data.store1}}<br>Jardins: {{data.store2}}<br>Museu: {{data.store3}}<br>Morumbi: {{data.store4}}</td>
				<td><?php 
				if ($this->session->userdata('privLevel') > 2 ) {
					?> 
					<button class="btn btn-info btn-xs" ng-click="delProd(data.total,data.sku)"><i class="fa fa-fw fa-times"></i></button>
					<?php
				}
				?></td>
			</tr>
		</tbody>
	
	</table>

	<ul uib-pagination total-items="filteredItems.length" ng-model="currentPage" ng-change="pageChanged()" items-per-page="itemsPerPage" max-size="maxSize" boundary-links="true" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></ul>

	<h4>Produtos encontrados: {{filteredItems.length}}</h4>

</div>