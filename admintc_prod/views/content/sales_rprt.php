<div class="row">

	<uib-tabset>

		<uib-tab index="0" heading="Vendas Diárias">

			<form class="form-inline tc-top-20 tc-sidepad-15">
			
				<div class="form-group">
			
					<label class="tc-right-25">Informar o Dia:</label>
					<div class="input-group">

						<input type="text" class="form-control input-sm" ng-model="search.date" is-open="popup1.opened" uib-datepicker-popup>
						<div class="input-group-addon add-cursor" ng-click="open1()"><i class="fa fa-fw fa-calendar"></i></div>
						<div class="input-group-addon add-cursor" ng-click="getSales()">Buscar</div>

					</div>
			
				</div>
			
			</form>

			<div class="tc-sidepad-15">

				<h3 ng-if="report.date">Vendas do dia: {{report.date  | date : 'dd/MM/yyyy'}}</h3>
	
				<p class="tc-top-15" ng-if="report.date">Total: R$ {{total.all | number : 2}}</p>
				<p ng-if="report.date">Total Moema: R$ {{total.moema | number : 2}}</p>
				<p ng-if="report.date">Total Jardins: R$ {{total.jardins | number : 2}}</p>
				<p ng-if="report.date">Total Museu: R$ {{total.museu | number : 2}}</p>
				<p ng-if="report.date">Total Morumbi: R$ {{total.morumbi | number : 2}}</p>
				<p ng-if="report.date">Total Site: R$ {{total.ecommerce | number : 2}}</p>
				<p ng-if="report.date">Total Projetos: R$ {{total.project | number : 2}}</p>

				<br>

				<div ng-if="report.date" ng-repeat="data in salesTable">

					<h3 ng-init="thisId.idSales=data.idSales">Venda No. {{data.idSales}}</h3>
	
					<p>Loja: {{data.store | properCase}}</p>
					<p>Vendedor: {{data.firstName}}</p>

					<h4>Lista de Compra</h4>
	
					<table class="table table-bordered table-condensed">
					
						<thead>
							<tr>
								<th>Nome do Produto</th>
								<th>Preço (R$)</th>
								<th>Qtd.</th>
								<th>Desconto</th>
								<th>Subtotal</th>
							</tr>
						</thead>
					
						<tbody>
							<tr ng-repeat="data2 in saleList | filter: thisId">
								<td>{{data2.title}} <small>(SKU: {{data2.sku}} | {{data2.model}} - {{data2.variant}})</small></td>
								<td>R$ {{data2.price | number : 2}}</td>
								<td>{{data2.qty}}</td>
								<td>R$ {{data2.discount | number : 2}}</td>
								<td>R$ {{(data2.price * data2.qty) - data2.discount | number : 2}}</td>
							</tr>
							<tr>
								<td></td>
								<td colspan="3">Subtotal</td>
								<td>R$ {{getTotal(data.idSales) | number:2}}</td>
							</tr>
							<tr>
								<td></td>
								<td colspan="3">Desconto</td>
								<td>R$ {{data.discount | number : 2}}</td>
							</tr>
							<tr>
								<td></td>
								<td  colspan="3" class="tc-bold">Total</td>
								<td>R$ {{data.totalPrice | number:2}}</td>
							</tr>
						</tbody>
					
					</table>

					<div class="row" ng-show="data.freight > 0"><div class="col-sm-4 col-sm-offset-8">

						<table class="table table-bordered table-condensed">
							<tbody>
								<tr>
									<td class="tc-bold">Frete</td>
									<td class="tc-bold">R$ {{data.freight | number:2}}</td>
								</tr>
								<tr>
									<td class="tc-bold">Total</td>
									<td class="tc-bold">R$ {{ data.totalPrice -- data.freight | number:2 }}</td>
								</tr>
							</tbody>
						</table>

					</div></div>

					<h4>Pagamento</h4>
	
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Pagamento</th>
								<th>Bandeira</th>
								<th>Valor</th>
								<th>Parcelas</th>
								<th>Data</th>
								<th>Nota</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="data3 in payList | filter: thisId">
								<td>{{data3.payType}}</td>
								<td>{{data3.banner}} {{data3.mode}}</td>
								<td>R$ {{data3.value | number:2}}</td>
								<td>{{data3.installment}}</td>
								<td>{{ (data3.payDate !== '0000-00-00') ? (data3.payDate | date: 'dd/MM/yyyy') : '' }}</td>
								<td>{{data3.note}}</td>
							</tr>
						</tbody>
					</table>
	
					<br>

				</div>

			</div>

		</uib-tab>

		<uib-tab index="1" heading="Buscar Venda">

			<form class="form-inline tc-top-20 tc-sidepad-15">
			
				<div class="form-group">
			
					<label class="tc-right-25">Informar a Venda:</label>
					<div class="input-group">
						<input type="text" class="form-control input-sm" ng-model="search2.idSales">
						<div class="input-group-addon add-cursor" ng-click="getSales2()">Buscar</div>
					</div>
			
				</div>
			
			</form>

			<div class="tc-sidepad-15" ng-if="saleInfo">

				<h3>Venda No. {{saleInfo[0].idSales}}</h3>

				<p>Loja: {{saleInfo[0].store | properCase}}</p>
				<p>Vendedor: {{saleInfo[0].firstName}}</p>
				<p>Data: {{saleInfo[0].date}}</p>
				<p><br></p>

				<h4>Lista de Compra</h4>

				<table class="table table-bordered table-condensed">
				
					<thead>
						<tr>
							<th>Nome do Produto</th>
							<th>Preço (R$)</th>
							<th>Qtd.</th>
							<th>Desconto</th>
							<th>Subtotal</th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data4 in saleList2">
							<td>{{data4.title}} <small>(SKU: {{data4.sku}} | {{data4.model}} - {{data4.variant}})</small></td>
							<td>R$ {{data4.price | number : 2}}</td>
							<td>{{data4.qty}}</td>
							<td>R$ {{data4.discount | number : 2}}</td>
							<td>R$ {{(data4.price * data4.qty) - data4.discount | number : 2}}</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3">Subtotal</td>
							<td>R$ {{getTotal2() | number:2}}</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3">Desconto</td>
							<td>R$ {{saleInfo[0].discount | number:2}}</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3" class="tc-bold">Total</td>
							<td>R$ {{saleInfo[0].totalPrice | number:2}}</td>
						</tr>
					</tbody>
				
				</table>

				<div class="row" ng-show="saleInfo[0].freight > 0"><div class="col-sm-4 col-sm-offset-8">

					<table class="table table-bordered table-condensed">
						<tbody>
							<tr>
								<td class="tc-bold">Frete</td>
								<td class="tc-bold">R$ {{saleInfo[0].freight | number:2}}</td>
							</tr>
							<tr>
								<td class="tc-bold">Total</td>
								<td class="tc-bold">R$ {{ saleInfo[0].totalPrice -- saleInfo[0].freight | number:2 }}</td>
							</tr>
						</tbody>
					</table>

				</div></div>

				<h4>Pagamento</h4>

				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Pagamento</th>
							<th>Bandeira</th>
							<th>Valor</th>
							<th>Parcelas</th>
							<th>Data</th>
							<th>Nota</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="data5 in payList2">
							<td>{{data5.payType}}</td>
							<td>{{data5.banner}} {{data5.mode}}</td>
							<td>R$ {{data5.value | number:2}}</td>
							<td>{{data5.installment}}</td>
							<td>{{ (data5.payDate !== '0000-00-00') ? (data5.payDate | date: 'dd/MM/yyyy') : '' }}</td>
							<td>{{data5.note}}</td>
						</tr>
					</tbody>
				</table>

			</div>

		</uib-tab>

	</uib-tabset>

</div>