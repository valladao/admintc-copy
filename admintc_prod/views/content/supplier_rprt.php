<div class="row">

	<form class="form-inline tc-top-20 tc-sidepad-15">
	
		<div class="form-group">
	
			<label class="tc-right-25">Ano:</label>

			<select class="form-control input-sm" ng-model="selectedYear">
				<option ng-repeat="year in years" ng-value="{{year}}">{{year}}</option>
			</select>

		</div>

		<button class="btn btn-success btn-sm tc-left-35" ng-click="getData()">Buscar</button>

	</form>

</div>

<div class="row">

	<table class="table table-bordered tc-top-20">
	
		<thead>
			<tr>
				<th>Fornecedor</th>
				<th>Total</th>
				<th>Jan</th>
				<th>Fev</th>
				<th>Mar</th>
				<th>Abr</th>
				<th>Mai</th>
				<th>Jun</th>
				<th>Jul</th>
				<th>Ago</th>
				<th>Set</th>
				<th>Out</th>
				<th>Nov</th>
				<th>Dez</th>
			</tr>
		</thead>
	
		<tbody>
			<tr ng-repeat="data in reportTable">
				<td><strong>{{data.supplier}}</strong></td>
				<td><strong>{{data.total | dot2comma}}</strong></td>
				<td>{{data.Jan | dot2comma}}</td>
				<td>{{data.Fev | dot2comma}}</td>
				<td>{{data.Mar | dot2comma}}</td>
				<td>{{data.Abr | dot2comma}}</td>
				<td>{{data.Mai | dot2comma}}</td>
				<td>{{data.Jun | dot2comma}}</td>
				<td>{{data.Jul | dot2comma}}</td>
				<td>{{data.Ago | dot2comma}}</td>
				<td>{{data.Set | dot2comma}}</td>
				<td>{{data.Out | dot2comma}}</td>
				<td>{{data.Nov | dot2comma}}</td>
				<td>{{data.Dez | dot2comma}}</td>
			</tr>
		</tbody>
	
	</table>

</div>