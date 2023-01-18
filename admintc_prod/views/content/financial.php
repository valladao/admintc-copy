<div class="row">

	<uib-tabset active="activeTab">

		<uib-tab index="0" heading="Estoque">

			<table class="table table-bordered tc-top-20">

				<thead>

					<th>Número</th>
					<th>Data</th>
					<th>Favorecido</th>
					<th>Categoria</th>
					<th>Status</th>
					<th>Retirada</th>
					<th>Depósito</th>
					<th>Total</th>
					<th>Comentário</th>

				</thead>

				<tbody>
					
					<tr ng-repeat="data in inputTable">
						<td>{{data.idNfes}}</td>
						<td>{{data.date | date : 'dd/MM/yyyy'}}</td>
						<td>Estoque TerraCotta</td>
						<td>Estoque:Entrada por Compra</td>
						<td></td>
						<td></td>
						<td>{{data.total | dot2comma}}</td>
						<td></td>
						<td>Fornecedor: {{data.supplier}}<span ng-if="data.romaneio"> - Romaneio: {{data.romaneio}}</span></td>
					</tr>

					<tr ng-repeat="data2 in costTable">
						<td>{{data2.idSales}}</td>
						<td>{{data2.date | date : 'dd/MM/yyyy'}}</td>
						<td>Cliente TerraCotta</td>
						<td>Estoque:Saída por Venda</td>
						<td></td>
						<td>{{data2.totalCost | dot2comma}}</td>
						<td></td>
						<td></td>
						<td>Fornecedor: {{data2.supplier}}</td>
					</tr>

					<tr ng-repeat="data8 in moveListTable">
						<td>{{data8.idMoves}}</td>
						<td>{{data8.date | date : 'dd/MM/yyyy'}}</td>
						<td>Sem Favorecido</td>
						<td>
							<p ng-if="data8.changeQty < 0">Estoque:Perda</p>
							<p ng-if="data8.changeQty > 0">Estoque:Retorno</p>
						</td>
						<td></td>
						<td>
							<p ng-if="data8.changeQty < 0">{{costAbs(data8.changeQty,data8.unitCost)}}</p>
						</td>
						<td>
							<p ng-if="data8.changeQty > 0">{{costAbs(data8.changeQty,data8.unitCost)}}</p>
						</td>
						<td></td>
						<td>Fornecedor: {{data8.supplier}} - {{data8.reason}}</td>
					</tr>

				</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanStock()">Limpar lista</button>

		</uib-tab>

		<uib-tab index="1" heading="Pagamentos">

			<table class="table table-bordered tc-top-20">

				<thead>

					<th>Número</th>
					<th>Data</th>
					<th>Favorecido</th>
					<th>Categoria</th>
					<th>Status</th>
					<th>Retirada</th>
					<th>Depósito</th>
					<th>Total</th>
					<th>Comentário</th>

				</thead>

				<tbody>
					
					<tr ng-repeat="data3 in payTable">

						<td>{{data3.idSales}}</td>
						<td>{{data3.date | date : 'dd/MM/yyyy'}}</td>
						<td>Loja {{data3.store | properCase}}</td>
						<td>Pagamentos:{{data3.payType}}</td>
						<td></td>
						<td></td>
						<td>{{data3.value | dot2comma}}</td>
						<td></td>
						<td>{{getDesc(data3)}}</td>

					</tr>

				</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanPay()">Limpar lista</button>

		</uib-tab>

		<uib-tab index="3" heading="DRE">

			<table class="table table-bordered tc-top-20">

				<thead>

					<th>Número</th>
					<th>Data</th>
					<th>Favorecido</th>
					<th>Categoria</th>
					<th>Status</th>
					<th>Retirada</th>
					<th>Depósito</th>
					<th>Total</th>
					<th>Comentário</th>

				</thead>

				<tbody>
					
					<tr ng-repeat="data4 in salesTable">

						<td>{{data4.idSales}}</td>
						<td>{{data4.date | date : 'dd/MM/yyyy'}}</td>
						<td>Loja {{data4.store | properCase}}</td>
						<td>Receita:Loja {{data4.store | properCase}}</td>
						<td></td>
						<td></td>
						<td>{{data4.totalPrice | dot2comma}}</td>
						<td></td>
						<td></td>

					</tr>
					<tr ng-repeat="data5 in salesTable">

						<td>{{data5.idSales}}</td>
						<td>{{data5.date | date : 'dd/MM/yyyy'}}</td>
						<td>Cliente TerraCotta</td>
						<td>CPV:Venda {{data5.store | properCase}}</td>
						<td></td>
						<td>{{data5.totalCost | dot2comma}}</td>
						<td></td>
						<td></td>
						<td></td>
						
					</tr>

					<tr ng-repeat="data6 in salesTable | filter: nonZero">

						<td>{{data6.idSales}}</td>
						<td>{{data6.date | date : 'dd/MM/yyyy'}}</td>
						<td>Frete - Pagamento Cliente</td>
						<td>Custos do E-Commerce:Correios e Frete</td>
						<td></td>
						<td></td>
						<td>{{data6.freight | dot2comma}}</td>
						<td></td>
						<td></td>
						
					</tr>

					<tr ng-repeat="data7 in moveTable">

						<td>{{data7.idMoves}}</td>
						<td>{{data7.date | date : 'dd/MM/yyyy'}}</td>
						<td>Sem Favorecido</td>
						<td>{{getCategory(data7.reason,data7.store)}}</td>
						<td></td>
						<td>{{moveNeg(data7.moveTotal)}}</td>
						<td>{{movePos(data7.moveTotal)}}</td>
						<td></td>
						<td>{{getNote(data7.reason,data7.note,data7.username,data7.changeSale)}}</td>
					
					</tr>

				</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanSales()">Limpar lista</button>

		</uib-tab>

	</uib-tabset>

</div>