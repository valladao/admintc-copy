<div class="row">

	<div class="col-sm-6 tc-top-20">
		
		<table class="table table-bordered">
			<thead>
				<th>#</th>
				<th>Data</th>
				<th>SKU</th>
				<th>Qtd. Sistema</th>
				<th>Qtd. Shopify</th>
				<th></th>
			</thead>
			<tbody>
				<tr ng-repeat="data in errors">
					<td>{{data.idError}}</td>
					<td>{{data.datetime}}</td>
					<td>{{data.sku}}</td>
					<td>{{data.dbLevel}}</td>
					<td>{{data.shopifyLevel}}</td>
					<td>
						<button class="btn btn-info btn-xs" ng-click="checkedIssue(data.idError)"><i class="fa fa-fw fa-times"></i></button>
					</td>
				</tr>
			</tbody>
		</table>

	</div>
	
</div>