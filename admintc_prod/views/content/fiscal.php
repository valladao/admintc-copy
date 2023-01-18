<div class="row">

	<uib-tabset active="activeTab">

		<uib-tab index="0" heading="Cadastrar Produtos">

			<input type="hidden" name="store" value="<?php echo $this->session->userdata('store'); ?>">

			<table class="table table-bordered tc-top-20">

					<thead>
						<tr>
							<th>Unidade Venda</th>
							<th>Descrição</th>
							<th>Descrição ECF</th>
							<th>Identificação</th>
							<th>Preço de Venda 1</th>
							<th>NCM</th>
							<th>CFOP</th>
							<th>ICMS</th>
							<th>Redução ICMS</th>
							<th>IPI</th>
							<th>CSOSN</th>
							<th>Situação Tributária A</th>
							<th>Situação Tributária B</th>
							<th>Sit Trib PIS</th>
							<th>Sit Trib COFINS</th>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data in fiscalTable | filter: search">
							<td>UN</td>
							<td>
								{{data.title | uppercase}}{{data.model | notModel}}{{data.variant | notStandard}}
							</td>
							<td>
								{{data.title | uppercase}}{{data.model | notModel}}{{data.variant | notStandard}}
							</td>
							<td>{{data.sku}}</td>
							<td>{{data.salesPrice | dot2comma}}</td>
							<td>{{data.ncm}}</td>
							<td>5102</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>102</td>
							<td>{{data.cstDigit | originNum}}</td>
							<td>0</td>
							<td>7</td>
							<td>7</td>
						</tr>
					</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanList()">Limpar lista</button>

		</uib-tab>

		<uib-tab index="1" heading="Transferências Moema" ng-if="store == 'jardins' || store == 'morumbi' || store == 'museu'">

			<h4 ng-if="store == 'jardins' || store == 'morumbi' || store == 'museu'" class="tc-top-20">Transferências para Moema:</h4>

			<table class="table table-bordered" ng-if="store == 'jardins' || store == 'morumbi' || store == 'museu'" class="tc-marginside-15">
				
					<thead>
						<tr>
							<td>Cód.</td>
							<td>Descrição</td>
							<td>NCM</td>
							<td>Origem</td>
							<td>CST</td>
							<td>CFOP</td>
							<td>Unid.</td>
							<td>Quantidade</td>
							<td>Valor Unit.</td>
							<td>Valor Total</td>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data2 in transferTable | filter: search2 | filter: moema">
							<td>{{data2.sku}}</td>
							<td>
								{{data2.title | uppercase}}{{data2.model | notModel}}{{data2.variant | notStandard}}
							</td>
							<td>{{data2.ncm}}</td>
							<td>{{data2.cstDigit | originNum}}</td>
							<td>400</td>
							<td>5152</td>
							<td>UN</td>
							<td>{{data2.txQty}}</td>
							<td>{{data2.unitCost | dot2comma}}</td>
							<td>{{data2.txQty * data2.unitCost | number:2 | dot2comma}}</td>
						</tr>
					</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanTransfer(store,'moema')" ng-if="store == 'jardins' || store == 'morumbi' || store == 'museu'" >Limpar lista</button>

		</uib-tab>

		<uib-tab index="2" heading="Transferências Jardins" ng-if="store == 'moema' || store == 'morumbi' || store == 'museu'">

			<h4 ng-if="store == 'moema' || store == 'morumbi' || store == 'museu'" class="tc-top-20">Transferências para Jardins:</h4>

			<table class="table table-bordered" ng-if="store == 'moema' || store == 'morumbi' || store == 'museu'" class="tc-marginside-15">
				
					<thead>
						<tr>
							<td>Cód.</td>
							<td>Descrição</td>
							<td>NCM</td>
							<td>Origem</td>
							<td>CST</td>
							<td>CFOP</td>
							<td>Unid.</td>
							<td>Quantidade</td>
							<td>Valor Unit.</td>
							<td>Valor Total</td>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data2 in transferTable | filter: search2 | filter: jardins">
							<td>{{data2.sku}}</td>
							<td>
								{{data2.title | uppercase}}{{data2.model | notModel}}{{data2.variant | notStandard}}
							</td>
							<td>{{data2.ncm}}</td>
							<td>{{data2.cstDigit | originNum}}</td>
							<td>400</td>
							<td>5152</td>
							<td>UN</td>
							<td>{{data2.txQty}}</td>
							<td>{{data2.unitCost | dot2comma}}</td>
							<td>{{data2.txQty * data2.unitCost | number:2 | dot2comma}}</td>
						</tr>
					</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanTransfer(store,'jardins')" ng-if="store == 'moema' || store == 'morumbi' || store == 'museu'" >Limpar lista</button>

		</uib-tab>

		<uib-tab index="3" heading="Transferências Morumbi" ng-if="store == 'moema' || store == 'jardins' || store == 'museu'">

			<h4 ng-if="store == 'moema' || store == 'jardins' || store == 'museu'" class="tc-top-20">Transferências para Morumbi:</h4>

			<table class="table table-bordered" ng-if="store == 'moema' || store == 'jardins' || store == 'museu'" class="tc-marginside-15">
				
					<thead>
						<tr>
							<td>Cód.</td>
							<td>Descrição</td>
							<td>NCM</td>
							<td>Origem</td>
							<td>CST</td>
							<td>CFOP</td>
							<td>Unid.</td>
							<td>Quantidade</td>
							<td>Valor Unit.</td>
							<td>Valor Total</td>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data2 in transferTable | filter: search2 | filter: morumbi">
							<td>{{data2.sku}}</td>
							<td>
								{{data2.title | uppercase}}{{data2.model | notModel}}{{data2.variant | notStandard}}
							</td>
							<td>{{data2.ncm}}</td>
							<td>{{data2.cstDigit | originNum}}</td>
							<td>400</td>
							<td>5152</td>
							<td>UN</td>
							<td>{{data2.txQty}}</td>
							<td>{{data2.unitCost | dot2comma}}</td>
							<td>{{data2.txQty * data2.unitCost | number:2 | dot2comma}}</td>
						</tr>
					</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanTransfer(store,'morumbi')" ng-if="store == 'moema' || store == 'jardins' || store == 'museu'" >Limpar lista</button>

		</uib-tab>

		<uib-tab index="4" heading="Transferências Museu" ng-if="store == 'moema' || store == 'jardins' || store == 'morumbi'">

			<h4 ng-if="store == 'moema' || store == 'jardins' || store == 'morumbi'" class="tc-top-20">Transferências para Museu:</h4>

			<table class="table table-bordered" ng-if="store == 'moema' || store == 'jardins' || store == 'morumbi'" class="tc-marginside-15">
				
					<thead>
						<tr>
							<td>Cód.</td>
							<td>Descrição</td>
							<td>NCM</td>
							<td>Origem</td>
							<td>CST</td>
							<td>CFOP</td>
							<td>Unid.</td>
							<td>Quantidade</td>
							<td>Valor Unit.</td>
							<td>Valor Total</td>
						</tr>
					</thead>
				
					<tbody>
						<tr ng-repeat="data2 in transferTable | filter: search2 | filter: museu">
							<td>{{data2.sku}}</td>
							<td>
								{{data2.title | uppercase}}{{data2.model | notModel}}{{data2.variant | notStandard}}
							</td>
							<td>{{data2.ncm}}</td>
							<td>{{data2.cstDigit | originNum}}</td>
							<td>400</td>
							<td>5152</td>
							<td>UN</td>
							<td>{{data2.txQty}}</td>
							<td>{{data2.unitCost | dot2comma}}</td>
							<td>{{data2.txQty * data2.unitCost | number:2 | dot2comma}}</td>
						</tr>
					</tbody>

			</table>

			<button class="btn btn-info btn-lg" ng-click="cleanTransfer(store,'museu')" ng-if="store == 'moema' || store == 'jardins' || store == 'morumbi'" >Limpar lista</button>

		</uib-tab>

		<uib-tab index="5" heading="Cupons Fiscais">

			<table class="table table-bordered tc-top-20">

				<thead>

					<th>Venda</th>
					<th>Data</th>
					<th>Loja</th>
					<th>Depósito</th>
					<th></th>

				</thead>

				<tbody>
					
					<tr ng-repeat="data5 in salesTable | filter: storeFilter">

						<td>{{data5.idSales}}</td>
						<td>{{data5.date | date : 'dd/MM/yyyy'}}</td>
						<td>{{data5.store | properCase}}</td>
						<td>{{data5.totalPrice | dot2comma}}</td>
						<td class="text-center">
							<button class="btn btn-success btn-xs" ng-click="receiptDone(data5.idSales)"><i class="fa fa-fw fa-check"></i></button>
						</td>

					</tr>

				</tbody>

			</table>

		</uib-tab>

	</uib-tabset>

</div>