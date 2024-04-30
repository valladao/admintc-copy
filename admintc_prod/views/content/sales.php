<div class="row">

	<form>

		<div class="col-sm-4">

			<div class="form-group">
				<label>Buscar:</label>
				<input class="form-control input-sm" type="text" placeholder="Produto, SKU ou Cód. de Barras" ng-model="searchFilter" ng-change="clearPriority()">
				<input class="form-control" type="hidden" name="store" value="<?php echo ucfirst($this->session->userdata('store')); ?>" disabled>
				<input class="form-control" type="hidden" name="username" value="<?php echo ucfirst($this->session->userdata('username')); ?>" disabled>
			</div>

		</div>

		<div class="col-sm-3">

			<div class="form-group">
				<label>Fornecedor:</label>
				<select class="form-control input-sm" ng-model="search.supplier">
					<option></option>
					<option ng-repeat="option in suppliers" value="{{option.supplier}}">{{option.supplier}}</option>
				</select>
			</div>

		</div>

		<div class="col-sm-3">

			<div class="form-group">
				<label>Tipo:</label>
				<select class="form-control input-sm" ng-model="search.type">
					<option></option>
					<option ng-repeat="option in types" value="{{option.type}}">{{option.type}}</option>
				</select>
			</div>

		</div>

		<div class="col-sm-2">

			<div class="form-group">
				<p class="tc-bot-20"></p>
				<div class="col-sm-8">
					<button class="btn btn-primary form-control" ng-click="cleanSearch()">Limpar</button>
				</div>
			</div>

		</div>

	</form>

</div>

<div class="row">

	<uib-tabset active="activeTab">

		<uib-tab index="0" heading="Lista de Venda">

			<div class="tc-pad-15">

				<table class="table table-bordered table-condensed">

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
							<th>Incluir</th>
						</tr>
					</thead>

					<tbody>
						<tr ng-repeat="data in (filteredItems = (
						productTable |
						filter: search |
						filter: ignoreAccents
						)).slice(
							((currentPage-1)*3), ((currentPage)*3)
						)">
							<td class="tc-center">
								<img ng-src="{{data.picture}}" width="75" height="100" ng-show="imageActive && data.picture">
							</td>
							<td>{{data.title}}</td>
							<td>{{data.model}} - {{data.variant}}</td>
							<td>{{data.type}}</td>
							<td>{{data.supplier}}</td>
							<td>{{data.sku}}</td>
							<td>R$ {{data.salesPrice}}</td>
							<td ng-if="store == 'Moema'">{{data.store1}}</td>
							<td ng-if="store == 'Jardins'">{{data.store2}}</td>
							<td ng-if="store == 'Museu'">{{data.store3}}</td>
							<td ng-if="store == 'Morumbi'">{{data.store4}}</td>
							<td class="text-center">
								<button class="btn btn-success btn-xs" ng-click="select(data)"><i class="fa fa-fw fa-angle-double-down"></i></button>
							</td>
						</tr>
					</tbody>

				</table>

				<div class="row">
					<div class="col-sm-8">
						<h4>Lista de Venda</h4>
					</div>
					<div class="col-sm-4">

						<ul uib-pagination total-items="filteredItems.length" ng-model="currentPage" items-per-page="3" max-size="0" class="pagination pagination-sm tc-right tc-left-15 tc-top-0 tc-bot-0" previous-text="<<" next-text=">>"></ul>

						<button ng-hide="imageActive" ng-click="activateImage()" class="btn btn-secondary btn-sm tc-right tc-left-15">
							<i class="fa fa-fw fa-image"></i>
						</button>
						<button ng-show="imageActive" ng-click="activateImage()" class="btn btn-secondary btn-sm tc-right tc-left-15 active">
							<i class="fa fa-fw fa-image"></i>
						</button>
					</div>
				</div>

				<table class="table table-bordered table-condensed">

					<thead>
						<tr>
							<th style="text-align: center;">#</th>
							<th>Nome do Produto</th>
							<th>Preço (R$)</th>
							<th>Qtd.</th>
							<th>Desconto</th>
							<th>Subtotal</th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						<tr ng-repeat="data in salesTable">
							<td style="text-align: center;">{{data.listCount + 1 }}</td>
							<td>{{data.title}} <small>(SKU: {{data.sku}} | {{data.model}} - {{data.variant}})</small></td>
							<td>R$ {{data.salesPrice}}</td>
							<td><div class="input-group">
								<div class="input-group-addon" ng-click="lessQty(data)"><i class="fa fa-fw fa-minus"></i></div>
								<input type="number" class="form-control input-sm" ng-model="data.salesQty" min="1" max="{{data.maxQty}}">
								<div class="input-group-addon" ng-click="plusQty(data)"><i class="fa fa-fw fa-plus"></i></div>
							</div></td>
							<td><div class="input-group">
								<div class="input-group-addon">R$ </div>
								<input class="form-control input-sm" ng-model="data.discount">
							</div></td>
							<td>R$ {{(data.salesPrice * data.salesQty) - data.discount | number : 2}}</td>
							<td><button class="btn btn-info btn-xs" ng-click="delete($index)"><i class="fa fa-fw fa-times"></i></button></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td colspan="3">Subtotal</td>
							<td>R$ {{getTotal() | number:2}}</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td colspan="3">Desconto</td>
							<td>
								<div class="input-group">
									<div class="input-group-addon">R$ </div>
									<input class="form-control input-sm" ng-model="sale.discount">
								</div>
							</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td class="tc-bold">Total</td>
							<td>{{sale.qty}}</td>
							<td></td>
							<td class="tc-bold">R$ {{sale.total = (subtotal - sale.discount) | number:2}}</td>
							<td></td>
						</tr>
					</tbody>

				</table>



				<div class="row tc-marginside-15">

					<div class="alert alert-danger col-sm-7" role="alert" ng-if="alertNow1">{{alertMsg1}}</div>

					<button class="btn btn-success btn-lg tc-right col-sm-3" ng-click="closeList(sale.total)">Finalizar Venda >></button>

				</div>

			</div>

		</uib-tab>

		<uib-tab index="1" heading="Pagamento" disable="disablePay">

			<div class="row">

				<div class="col-sm-8">

					<div class="tc-pad-15">

						<h4>Cartão</h4>

						<div class="row">

							<div class="col-sm-12">

								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th>Maquineta</th>
											<th>Bandeira</th>
											<th>Valor</th>
											<th>Parcelas</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<select class="form-control input-sm" ng-model="card.payType">
													<option>Pagseguro</option>
													<option>Stone</option>
												</select>
											</td>
											<td ng-class="{'has-error': noCardBanner}">
												<select class="form-control input-sm" ng-model="card.banner">
													<option>Master Débito</option>
													<option>Master Crédito</option>
													<option>Visa Débito</option>
													<option>Visa Crédito</option>
													<option>Elo Débito</option>
													<option>Elo Crédito</option>
													<option>AMEX</option>
													<option>Hipercard</option>
													<option>Hiper</option>
													<option>Dinners</option>
													<option>Banricompras</option>
												</select>
											</td>
											<td>
												<div class="input-group" ng-class="{'has-error': noCardValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="card.value">
													<div class="input-group-addon" ng-click="getAll('card')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td>
												<select class="form-control input-sm" ng-model="card.installment" ng-show="disableReview">
													<option>1x</option>
													<option>2x</option>
													<option>3x</option>
												</select>
												<select class="form-control input-sm" ng-model="card.installment" ng-hide="disableReview">
													<option>1x</option>
													<option>2x</option>
													<option>3x</option>
													<option>4x</option>
													<option>5x</option>
													<option>6x</option>
													<option>7x</option>
													<option>8x</option>
													<option>9x</option>
													<option>10x</option>
												</select>
											</td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('card')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4>Dinheiro</h4>

						<div class="row">

							<div class="col-sm-8">

								<table class="table table-bordered table-condensed col-sm-6">
									<thead>
										<tr>
											<th>Valor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="input-group" ng-class="{'has-error': noMoneyValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="money.value">
													<div class="input-group-addon" ng-click="getAll('money')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('money')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4>Cheque	<button class="btn btn-default btn-xs" ng-click="showMe('check')"><i class="fa fa-fw fa-folder-open"></i></button></h4>

						<div class="row" ng-show="showCheck">

							<div class="col-sm-12">

								<table class="table table-bordered table-condensed col-sm-6">
									<thead>
										<tr>
											<th>Valor</th>
											<th>Data</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="input-group" ng-class="{'has-error': noCheckValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="check.value">
													<div class="input-group-addon" ng-click="getAll('check')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td>
												<div class="input-group" ng-class="{'has-error': noCheckDate}">
													<input type="text" class="form-control input-sm" ng-model="check.date" is-open="popup1.opened" uib-datepicker-popup>
													<div class="input-group-addon" ng-click="open1()"><i class="fa fa-fw fa-calendar"></i></div>
												</div>
											</td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('check')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4>Depósito	<button class="btn btn-default btn-xs" ng-click="showMe('deposit')"><i class="fa fa-fw fa-folder-open"></i></button></h4>

						<div class="row" ng-show="showDeposit">

							<div class="col-sm-12">

								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th>Banco</th>
											<th>Valor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td ng-class="{'has-error': noDepositBank}">
												<select class="form-control input-sm" ng-model="deposit.bank" ng-show="disableReview">
													<option>Sicredi</option>
													<option>Santander</option>
													<option>Itaú</option>
												</select>
												<select class="form-control input-sm" ng-model="deposit.bank" ng-hide="disableReview">
													<option>Sicredi</option>
													<option>Santander</option>
													<option>Itaú</option>
													<option>Bradesco</option>
												</select>
											</td>
											<td>
												<div class="input-group" ng-class="{'has-error': noDepositValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="deposit.value">
													<div class="input-group-addon" ng-click="getAll('deposit')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('deposit')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4>Boleto	<button class="btn btn-default btn-xs" ng-click="showMe('bill')"><i class="fa fa-fw fa-folder-open"></i></button></h4>

						<div class="row" ng-show="showBill">

							<div class="col-sm-12">

								<table class="table table-bordered table-condensed col-sm-6">
									<thead>
										<tr>
											<th>Valor</th>
											<th>Data</th>
											<th>Canal de Venda</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="input-group" ng-class="{'has-error': noBillValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="bill.value">
													<div class="input-group-addon" ng-click="getAll('bill')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td>
												<div class="input-group" ng-class="{'has-error': noBillDate}">
													<input type="text" class="form-control input-sm" ng-model="bill.date" is-open="popup2.opened" uib-datepicker-popup>
													<div class="input-group-addon" ng-click="open2()"><i class="fa fa-fw fa-calendar"></i></div>
												</div>
											</td>
											<td ng-class="{'has-error': noBillChannel}"><select class="form-control input-sm" ng-model="bill.channel">
												<option>Instagram</option>
												<option>WhatsApp</option>
												<option>Telefone</option>
												<option>Loja</option>
											</select></td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('bill')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4>À cobrar	<button class="btn btn-default btn-xs" ng-click="showMe('pending')"><i class="fa fa-fw fa-folder-open"></i></button></h4>

						<div class="row" ng-show="showPending">

							<div class="col-sm-12">

								<table class="table table-bordered table-condensed col-sm-6">
									<thead>
										<tr>
											<th>Valor</th>
											<th>Data</th>
											<th>Nota</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="input-group" ng-class="{'has-error': noPendingValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="pending.value">
													<div class="input-group-addon" ng-click="getAll('pending')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td>
												<div class="input-group" ng-class="{'has-error': noPendingDate}">
													<input type="text" class="form-control input-sm" ng-model="pending.date" is-open="popup3.opened" uib-datepicker-popup>
													<div class="input-group-addon" ng-click="open3()"><i class="fa fa-fw fa-calendar"></i></div>
												</div>
											</td>
											<td ng-class="{'has-error': noPendingNote}"><input type="text" class="form-control input-sm" ng-model="pending.note"></td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('pending')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4>Frete	<button class="btn btn-default btn-xs" ng-click="showMe('freight')"><i class="fa fa-fw fa-folder-open"></i></button></h4>

						<div class="row" ng-show="showFreight">

							<div class="col-sm-8">

								<table class="table table-bordered table-condensed col-sm-6">
									<thead>
										<tr>
											<th>Valor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="input-group" ng-class="{'has-error': noFreightValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="freight.value">
												</div>
											</td>
											<td><button class="btn btn-success btn-sm" ng-click="addFreight()"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

						<h4  ng-hide="disableReview">Troca	<button class="btn btn-default btn-xs" ng-click="showMe('change')"><i class="fa fa-fw fa-folder-open"></i></button></h4>

						<div class="row" ng-show="showChange">

							<div class="col-sm-12">

								<table class="table table-bordered table-condensed col-sm-6">
									<thead>
										<tr>
											<th>Venda</th>
											<th>SKUs</th>
											<th>Valor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td ng-class="{'has-error': noChangeSale}">
												<input type="text" class="form-control input-sm" ng-model="change.sale">
											</td>
											<td ng-class="{'has-error': noChangeSkus}">
												<input type="text" class="form-control input-sm" ng-model="change.skus">
											</td>
											<td>
												<div class="input-group" ng-class="{'has-error': noChangeValue}">
													<div class="input-group-addon">R$ </div>
													<input type="text" class="form-control input-sm" ng-model="change.value">
													<div class="input-group-addon" ng-click="getAll('change')"><i class="fa fa-fw fa-reply-all"></i></div>
												</div>
											</td>
											<td><button class="btn btn-success btn-sm" ng-click="addPay('change')"><i class="fa fa-fw fa-plus"></i></button></td>
										</tr>
									</tbody>
								</table>

							</div>

						</div>

					</div>

				</div>

				<div class="col-sm-4">

					<div class="panel panel-default tc-top-20">

						<div class="panel-heading">
							<h3 class="panel-title tc-bold">Pagamento Total: R$ {{sale.total + sale.freight | number:2}}</h3>
						</div>

						<div class="panel-body">

							<p class="tc-bold">Valor pendente: R$ {{ sale.payPending = (sale.total - payTotal() + sale.freight) | number:2}}</p>

							<p class="tc-bold" ng-show="haveFreight">Frete: R$ {{sale.freight | number:2}}</p>

							<table class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th>Pagamento</th>
										<th>Valor</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in payTable">
										<td>{{ data.payType }}</td>
										<td>R$ {{ data.value | number: 2 }}</td>
										<td><button class="btn btn-info btn-xs" ng-click="delPay($index)"><i class="fa fa-fw fa-times"></i></button></td>
									</tr>
								</tbody>
							</table>

							<div class="row text-center">
								<button class="btn btn-success" ng-click="closePay()">Fechar Pagamento >></button>
							</div>

						</div>

					</div>

					<div ng-if="alertNow">
						<pre class="alert alert-danger">{{alertMsg}}</pre>
					</div>

				</div>

			</div>

		</uib-tab>

		<uib-tab index="2" heading="Fechar" disable="disableReview">

			<div class="row tc-sidepad-15">

				<div class="tc-sidepad-15">

						<div class="alert alert-warning tc-top-10 tc-alert">
							<strong>ATENÇÃO: </strong>
							Por favor, verificar AQUI se a venda foi corretamente anotada antes de prosseguir.
						</div>

					<p>Dia da Compra: {{sale.date}}</p>

					<p>Loja: <?php
						echo ucfirst($this->session->userdata('store'));
					?>
						<button type="button" class="btn btn-link" ng-click="changeStore = !changeStore" ng-init="changeStore=false">modificar</button>

						<div class="checkbox tc-sidepad-15" ng-show="changeStore">
							<label>
								<input type="checkbox" ng-model="sale.ecommerce"> E-Commerce
							</label>
						</div>

					</p>

					<p>Vendedor: <?php
						echo $this->session->userdata('firstName');
					?>
						<button type="button" class="btn btn-link" ng-click="changeSalesman = !changeSalesman" ng-init="changeSalesman=false">modificar</button>

						<div class="row tc-sidepad-15" ng-show="changeSalesman"><div class="col-sm-4">
							<select class="form-control input-sm" ng-model="sale.salesman">
								<option ng-repeat="option in salesman" value="{{option.username}}">{{option.firstName}}</option>
							</select>
						</div></div>

					</p>

					<h4 class="tc-top-20">Venda e Pagamento</h4>

					<table class="table table-bordered table-condensed">

						<thead>
							<tr>
								<th style="text-align: center;">#</th>
								<th>Nome do Produto</th>
								<th>Preço (R$)</th>
								<th>Qtd.</th>
								<th>Desconto</th>
								<th>Subtotal</th>
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="data in salesTable">
								<td style="text-align: center;">{{data.listCount + 1 }}</td>
								<td>{{data.title}} <small>(SKU: {{data.sku}} | {{data.model}} - {{data.variant}})</small></td>
								<td>R$ {{data.salesPrice}}</td>
								<td>{{data.salesQty}}</td>
								<td>R$ {{data.discount | number : 2}}</td>
								<td>R$ {{(data.salesPrice * data.salesQty) - data.discount | number : 2}}</td>
							</tr>
							<tr>
								<td colspan="2"></td>
								<td colspan="3">Subtotal</td>
								<td>R$ {{getTotal() | number:2}}</td>
							</tr>
							<tr>
								<td colspan="2"></td>
								<td colspan="3">Desconto</td>
								<td>R$ {{sale.discount | number:2}}</td>
							</tr>
							<tr>
								<td colspan="2"></td>
								<td class="tc-bold" ng-hide="haveFreight">Total</td>
								<td class="tc-bold" ng-show="haveFreight">Subtotal</td>
								<td>{{sale.qty}}</td>
								<td></td>
								<td class="tc-bold">R$ {{sale.total | number:2}}</td>
							</tr>
						</tbody>

					</table>

					<div class="row" ng-show="haveFreight"><div class="col-sm-4 col-sm-offset-8">

						<table class="table table-bordered table-condensed">
							<tbody>
								<tr>
									<td class="tc-bold">Frete</td>
									<td class="tc-bold">R$ {{sale.freight | number:2}}</td>
								</tr>
								<tr>
									<td class="tc-bold">Total</td>
									<td class="tc-bold">R$ {{sale.total + sale.freight | number:2}}</td>
								</tr>
							</tbody>
						</table>

					</div></div>

					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Pagamento</th>
								<th>Bandeira</th>
								<th>Parcelas</th>
								<th>Data</th>
								<th>Nota</th>
								<th>Valor</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="data in payTable">
								<td>{{ data.payType }}</td>
								<td>{{ data.banner }}<span ng-if="data.mode"> - </span>{{ data.mode }}</td>
								<td>{{ data.installment }}</td>
								<td>{{ data.date }}</td>
								<td>{{ data.note }}</td>
								<td>R$ {{ data.value | number: 2 }}</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td colspan="2" class="tc-bold">Total</td>
								<td class="tc-bold">R$ {{ payTotal() | number:2 }}</td>
							</tr>
						</tbody>
					</table>

					<div class="row" ng-hide="true">

						<div class="alert alert-warning tc-right col-sm-6">
							Essa venda tem desconto o parcelamento fora do permitido. <span class="tc-bold">Necessário aprovação da GERÊNCIA.</span>. Prossiga caso já tenha sido aprovado.
						</div>

					</div>

					<button class="btn btn-success tc-right" ng-click="!isButtonClicked && (isButtonClicked=true) && salesClose()" ng-dblclick="false" ng-disabled="isButtonClicked">Finalizar Venda >></button>

				</div>

			</div>

		</uib-tab>

	</uib-tabset>

</div>