<uib-tabset active="activeTab">

	<uib-tab index="0" heading="Novos Produtos" ng-click="clickTab()">

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
					<tr ng-repeat="data in (filteredItems = (
					productTable | 
					filter: firstSku | 
					filter: search
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
						<td>
							<button class="btn btn-success btn-xs" ng-click="selectSKU(data)"><i class="fa fa-fw fa-forward"></i></button>
						</td>
					</tr>
				</tbody>
			
			</table>
		
			<ul uib-pagination total-items="filteredItems.length" ng-model="currentPage" ng-change="pageChanged()" items-per-page="itemsPerPage" max-size="maxSize" boundary-links="true" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></ul>
		
			<h4>Produtos encontrados: {{filteredItems.length}}</h4>
		
		</div>

	</uib-tab>

	<uib-tab index="1" heading="Produto" disable="prodTab"  ng-click="clickTab()">

		<div class="col-sm-8 tc-top-20">

			<form class="form-horizontal">
	
				<div class="form-group">
					<label class="control-label col-sm-3">Nome do Produto:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" ng-model="product.title" disabled>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Fornecedor:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" ng-model="product.supplier" disabled>
					</div>
				</div>
	
				<div class="form-group">
					<label class="control-label col-sm-3">Tipo:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" ng-model="product.type" disabled>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-1"></label>
					<div class="col-sm-9">
						<table class="table table-bordered">
							
							<thead>
								<tr>
									<th>Variações</th>
									<th>SKU</th>
									<th>Qtd.</th>
									<th>Preço</th>
								</tr>
							</thead>
		
							<tbody>
								<tr ng-repeat="data in variants">
									<td>{{data.model}} - {{data.variant}}</td>
									<td>{{data.sku}}</td>
									<td>{{data.total}}</td>
									<td>{{data.salesPrice}}</td>
								</tr>
							</tbody>
		
						</table>
					</div>
				</div>

				<!-- =============================================================================================================== -->
				<hr>

				<div ng-if="product.sku">
	
					<div class="form-group" ng-if="enableSubtype">
						<label class="control-label col-sm-3">Subcategoria:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.subtype" ng-disabled="disableSubtype" ng-change="subtypeSelected()">

								<option ng-if="product.type=='Jóias'">Medalha</option>
								<option ng-if="product.type=='Jóias'">Gargantilha</option>
								<option ng-if="product.type=='Jóias'">Pingentes</option>
								<option ng-if="product.type=='Jóias'">Corrente</option>
								<option ng-if="product.type=='Jóias'">Cruz</option>
								<option ng-if="product.type=='Jóias'">Conjunto</option>
								<option ng-if="product.type=='Jóias'">Terço</option>
								<option ng-if="product.type=='Jóias'">Pulseira</option>
								<option ng-if="product.type=='Jóias'">Cristo</option>

								<option ng-if="product.type=='Terços'">Terço</option>
								<option ng-if="product.type=='Terços'">Mini Terço</option>
								<option ng-if="product.type=='Terços'">Dezena</option>

								<option ng-if="product.type=='Presépios'">Presépio</option>
								<option ng-if="product.type=='Presépios'">Sagrada Família</option>
								<option ng-if="product.type=='Presépios'">Peças de Presépio</option>
								<option ng-if="product.type=='Presépios'">Menino Jesus</option>
								<option ng-if="product.type=='Presépios'">Manjedoura</option>
								<option ng-if="product.type=='Presépios'">Cenário</option>

								<option ng-if="product.type=='Porta Santo'">Peanha</option>
								<option ng-if="product.type=='Porta Santo'">Oratório</option>
								<option ng-if="product.type=='Porta Santo'">Capela</option>

								<option ng-if="product.type=='Batismo e Nascimento'">Medalhão de Berço</option>
								<option ng-if="product.type=='Batismo e Nascimento'">Porta Água Benta</option>
								<option ng-if="product.type=='Batismo e Nascimento'">Anjo de Cristal</option>
								<option ng-if="product.type=='Batismo e Nascimento'">Roupa de Batismo</option>
								<option ng-if="product.type=='Batismo e Nascimento'">Capelinha</option>
								<option ng-if="product.type=='Batismo e Nascimento'">Vitral</option>
								<option ng-if="product.type=='Batismo e Nascimento'">Imagem</option>

								<option ng-if="product.type=='Primeira Comunhão'">Adorno</option>
								<option ng-if="product.type=='Primeira Comunhão'">Capelinha</option>
								<option ng-if="product.type=='Primeira Comunhão'">Vitral</option>
								<option ng-if="product.type=='Primeira Comunhão'">Porta Terço</option>
								<option ng-if="product.type=='Primeira Comunhão'">Livro</option>
								<option ng-if="product.type=='Primeira Comunhão'">Anjo de Cristal</option>
								<option ng-if="product.type=='Primeira Comunhão'">Imã</option>
								<option ng-if="product.type=='Primeira Comunhão'">Cordão</option>
								<option ng-if="product.type=='Primeira Comunhão'">Terço</option>
								<option ng-if="product.type=='Primeira Comunhão'">Cruz</option>
								<option ng-if="product.type=='Primeira Comunhão'">Lembrancinha</option>
								<option ng-if="product.type=='Primeira Comunhão'">Imagem</option>

								<option ng-if="product.type=='Anjos'">Imagem</option>
								<option ng-if="product.type=='Anjos'">Adorno</option>
								<option ng-if="product.type=='Anjos'">Candelabro</option>

								<option ng-if="product.type=='Diversos'">Ovo Russo</option>
								<option ng-if="product.type=='Diversos'">Coroa</option>
								<option ng-if="product.type=='Diversos'">Medalha</option>
								<option ng-if="product.type=='Diversos'">Almofadinha</option>
								<option ng-if="product.type=='Diversos'">Caixa</option>
								<option ng-if="product.type=='Diversos'">Caixa Livro</option>
								<option ng-if="product.type=='Diversos'">Porta Água Benta</option>
								<option ng-if="product.type=='Diversos'">Bíblia</option>
								<option ng-if="product.type=='Diversos'">Porta Bíblia</option>
								<option ng-if="product.type=='Diversos'">Gaiola</option>
								<option ng-if="product.type=='Diversos'">Candelabro</option>
								<option ng-if="product.type=='Diversos'">Castiçal</option>
								<option ng-if="product.type=='Diversos'">Sino</option>
								<option ng-if="product.type=='Diversos'">Vela</option>
								<option ng-if="product.type=='Diversos'">Guirlanda</option>
								<option ng-if="product.type=='Diversos'">Fruteira</option>
								<option ng-if="product.type=='Diversos'">Caderneta</option>
								<option ng-if="product.type=='Diversos'">Enfeite de Árvore</option>

								<option ng-if="product.type=='Adornos'">Adorno</option>
								<option ng-if="product.type=='Adornos'">Capelinha</option>
								<option ng-if="product.type=='Adornos'">Adorno para Maçaneta</option>
								<option ng-if="product.type=='Adornos'">Adorno de Porta</option>
								<option ng-if="product.type=='Adornos'">Dezena de Porta</option>
								<option ng-if="product.type=='Adornos'">Vitral</option>
								<option ng-if="product.type=='Adornos'">Adorno de Prata</option>

								<option ng-if="product.type=='Arte Sacra'">Talha</option>
								<option ng-if="product.type=='Arte Sacra'">Imagem</option>
								<option ng-if="product.type=='Arte Sacra'">Ícone</option>

								<option ng-if="product.type=='Crucifixos'">Crucifixo</option>
								<option ng-if="product.type=='Crucifixos'">Crucifixo de Mesa</option>

							</select>
						</div>
					</div>

					<div class="form-group" ng-if="product.type=='Porta Santo'">
						<label class="control-label col-sm-3">Tipo:</label>
						<div class="col-sm-7">
							<div class="radio">
								<label><input type="radio" ng-model="aux.type" value="Figura" ng-change="openRadio()">Figura</label>
							</div>
							<div class="radio">
								<label><input type="radio" ng-model="aux.type" value="Estilo" ng-change="openRadio()">Estilo</label>
							</div>
						</div>
					</div>
	
					<div class="form-group" ng-if="enableFigure">
						<label class="control-label col-sm-3">Figura Base:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.baseFigure"  ng-disabled="disableBaseFigure">
								<option>Nossa Senhora</option>
								<option>Jesus</option>
								<option>Santa</option>
								<option>Santo</option>
								<option>Arcanjo</option>
								<option>Anjo</option>
								<option>Diversos</option>
							</select>
						</div>
					</div>
	
					<div class="form-group" ng-if="enableFigure">
						<label class="control-label col-sm-3">Figura:</label>
						<div class="col-sm-7">

							<select class="form-control input-sm" ng-model="internet.figure">

								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora das Graças</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora de Fátima</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora Aparecida</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Imaculado Coração de Maria</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Madona</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora de Lourdes</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora Grávida</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora de Guadalupe</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Carmo</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Desterro</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora de Nazaré</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora de Međugorje</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora de Schoenstatt</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora Auxiliadora</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Assunção</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Boa Viagem</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Conceição</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Perpétuo Socorro</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Confiança</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Divina Providência</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Guia</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Rosa Mística</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Sabedoria</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora da Saúde</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora das Cabeças</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora das Dores</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora Desatadora dos Nós</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Bom Parto</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Leite</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Perpétuo Socorro</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Rosário</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Socorro</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora dos Navegantes</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora do Sorriso</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora Mãe Amabilíssima</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Nossa Senhora Mãe de Deus</option>
								<option ng-if="internet.baseFigure=='Nossa Senhora'">Pietà</option>

								<option ng-if="internet.baseFigure=='Jesus'">Jesus</option>
								<option ng-if="internet.baseFigure=='Jesus'">Sagrado Coração de Jesus</option>
								<option ng-if="internet.baseFigure=='Jesus'">Jesus Misericordioso</option>
								<option ng-if="internet.baseFigure=='Jesus'">Menino Jesus de Praga</option>
								<option ng-if="internet.baseFigure=='Jesus'">Jesus Ressuscitado</option>
								<option ng-if="internet.baseFigure=='Jesus'">Face de Cristo</option>
								<option ng-if="internet.baseFigure=='Jesus'">Jesus Orando</option>
								<option ng-if="internet.baseFigure=='Jesus'">Bom Pastor</option>
								<option ng-if="internet.baseFigure=='Jesus'">Divino Menino Jesus</option>
								<option ng-if="internet.baseFigure=='Jesus'">Jesus Nazareno</option>
								<option ng-if="internet.baseFigure=='Jesus'">Bom Jesus</option>

								<option ng-if="internet.baseFigure=='Santa'">Santa Rita de Cássia</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Terezinha</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Bárbara</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Cecília</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Clara</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Edwiges</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Luzia</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Ana</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Catarina</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Helena</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Apolônia</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Filomena</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Ágata</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Laura</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Madre Paulina</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Marta</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Teresa de Ávila</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Ifigênia</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Beatriz</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Joana d'Arc</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Isabel</option>
								<option ng-if="internet.baseFigure=='Santa'">Santa Faustina</option>

								<option ng-if="internet.baseFigure=='Santo'">São Francisco de Assis</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo Expedito</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo Antônio</option>
								<option ng-if="internet.baseFigure=='Santo'">São Bento</option>
								<option ng-if="internet.baseFigure=='Santo'">São Jorge</option>
								<option ng-if="internet.baseFigure=='Santo'">São José</option>
								<option ng-if="internet.baseFigure=='Santo'">São Paulo</option>
								<option ng-if="internet.baseFigure=='Santo'">São Pedro</option>
								<option ng-if="internet.baseFigure=='Santo'">Padre Pio</option>
								<option ng-if="internet.baseFigure=='Santo'">São João</option>
								<option ng-if="internet.baseFigure=='Santo'">São João Batista</option>
								<option ng-if="internet.baseFigure=='Santo'">São Judas Tadeu</option>
								<option ng-if="internet.baseFigure=='Santo'">Frei Galvão</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo Afonso</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo Amaro</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo André</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo Agostinho</option>
								<option ng-if="internet.baseFigure=='Santo'">Santo Ivo</option>
								<option ng-if="internet.baseFigure=='Santo'">São Benedito</option>
								<option ng-if="internet.baseFigure=='Santo'">São Brás</option>
								<option ng-if="internet.baseFigure=='Santo'">São Cosme e Damião</option>
								<option ng-if="internet.baseFigure=='Santo'">São Cristóvão</option>
								<option ng-if="internet.baseFigure=='Santo'">São Damião</option>
								<option ng-if="internet.baseFigure=='Santo'">São Geraldo</option>
								<option ng-if="internet.baseFigure=='Santo'">São Jerônimo</option>
								<option ng-if="internet.baseFigure=='Santo'">São Lázaro</option>
								<option ng-if="internet.baseFigure=='Santo'">São Longuinho</option>
								<option ng-if="internet.baseFigure=='Santo'">São Lourenço</option>
								<option ng-if="internet.baseFigure=='Santo'">São Lucas</option>
								<option ng-if="internet.baseFigure=='Santo'">São Martinho de Porres</option>
								<option ng-if="internet.baseFigure=='Santo'">São Mateus</option>
								<option ng-if="internet.baseFigure=='Santo'">São Nicolau</option>
								<option ng-if="internet.baseFigure=='Santo'">São Pelegrino</option>
								<option ng-if="internet.baseFigure=='Santo'">São Roque</option>
								<option ng-if="internet.baseFigure=='Santo'">São Sebastião</option>

								<option ng-if="internet.baseFigure=='Arcanjo'">São Miguel Arcanjo</option>
								<option ng-if="internet.baseFigure=='Arcanjo'">São Gabriel Arcanjo</option>
								<option ng-if="internet.baseFigure=='Arcanjo'">São Rafael</option>

								<option ng-if="internet.baseFigure=='Anjo'">Anjo da Guarda</option>
								<option ng-if="internet.baseFigure=='Anjo'">Arcanjo</option>
								<option ng-if="internet.baseFigure=='Anjo'">Querubim</option>
								<option ng-if="internet.baseFigure=='Anjo'">Anjo</option>

								<option ng-if="internet.baseFigure=='Diversos'">Sagrada Família</option>
								<option ng-if="internet.baseFigure=='Diversos'">Divino Espirito Santo</option>
								<option ng-if="internet.baseFigure=='Diversos'">Cruz</option>
								<option ng-if="internet.baseFigure=='Diversos'">Santa Ceia</option>
								<option ng-if="internet.baseFigure=='Diversos'">Cálice de Primeira Comunhão</option>
								<option ng-if="internet.baseFigure=='Diversos'">Divino Pai Eterno</option>
								<option ng-if="internet.baseFigure=='Diversos'">Pai Nosso</option>
								<option ng-if="internet.baseFigure=='Diversos'">10 Mandamentos</option>
								<option ng-if="internet.baseFigure=='Diversos'">Pombos</option>

							</select>
						</div>
					</div>
	
					<div class="form-group" ng-if="enableMaterial">
						<label class="control-label col-sm-3">Material:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.material" ng-disabled="disableMaterial">

								<option ng-if="product.type=='Terços'">Cristal</option>
								<option ng-if="product.type=='Terços'">Murano</option>
								<option ng-if="product.type=='Terços'">Cloisonné</option>
								<option ng-if="product.type=='Terços'">Hematita</option>
								<option ng-if="product.type=='Terços'">Swarovski</option>
								<option ng-if="product.type=='Terços'">Madrepérola</option>
								<option ng-if="product.type=='Terços'">Porcelana</option>
								<option ng-if="product.type=='Terços'">Mármore</option>
								<option ng-if="product.type=='Terços'">Olho Gato</option>
								<option ng-if="product.type=='Terços'">Ágata</option>
								<option ng-if="product.type=='Terços'">Ametista</option>
								<option ng-if="product.type=='Terços'">Malaquita</option>
								<option ng-if="product.type=='Terços'">Turquesa</option>
								<option ng-if="product.type=='Terços'">Ônix</option>
								<option ng-if="product.type=='Terços'">Madeira de Oliveira</option>
								<option ng-if="product.type=='Terços'">Pedra Vulcânica</option>
								<option ng-if="product.type=='Terços'">Pérola</option>
								<option ng-if="product.type=='Terços'">Quartzo</option>
								<option ng-if="product.type=='Terços'">Cornalina</option>
								<option ng-if="product.type=='Terços'">Granada</option>

								<option ng-if="product.type=='Jóias'">Prata</option>
								<option ng-if="product.type=='Jóias'">Prata com banho de Ouro</option>
								<option ng-if="product.type=='Jóias'">Prata com banho de Ródio</option>
								<option ng-if="product.type=='Jóias'">Banho de Ouro</option>
								<option ng-if="product.type=='Jóias'">Madrepérola</option>
								<option ng-if="product.type=='Jóias'">Ouro</option>

								<option ng-if="product.type=='Escapulários'">Prata</option>
								<option ng-if="product.type=='Escapulários'">Prata com banho de Ouro</option>
								<option ng-if="product.type=='Escapulários'">Banho de Ouro</option>
								<option ng-if="product.type=='Escapulários'">Madrepérola</option>
								<option ng-if="product.type=='Escapulários'">Ouro</option>

								<option>Madeira</option>
								<option>Plástico</option>
								<option>Resina</option>
								<option>Pó de Mármore</option>
								<option>Porcelana</option>
								<option>Vidro</option>
								<option>Metal</option>
								<option>Pedra</option>
								<option>Tecido</option>
								
							</select>
						</div>
					</div>

					<div class="form-group" ng-if="enableChainMaterial">
						<label class="control-label col-sm-3">Material da Corrente:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.chainMaterial">
								<option>Alpaca</option>
								<option>Zamak</option>
								<option>Prata</option>
								<option>Metal</option>
							</select>
						</div>
					</div>
	
					<div class="form-group" ng-if="enablePainting">
						<label class="control-label col-sm-3">Pintura:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.painting">
								<option>Colorida</option>
								<option>Branca</option>
								<option>Marfim</option>
								<option>Bronze</option>
								<option>Policromia</option>
								<option>Sem pintura</option>
							</select>
						</div>
					</div>
	
					<div class="form-group" ng-if="enableShape">
						<label class="control-label col-sm-3">Forma:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.shape">
								<option>Redondo</option>
								<option>Oval</option>
								<option>Brasão</option>
								<option>Cruz</option>
								<option>Retangular</option>
								<option>Nossa Senhora Aparecida</option>
							</select>
						</div>
					</div>

					<div class="form-group" ng-if="enableMedal">
						<label class="control-label col-sm-3">Medalha:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.medal">
								<option>Nossa Senhora</option>
								<option>Nossa Senhora de Fátima</option>
								<option>Nossa Senhora das Graças</option>
							</select>
						</div>
					</div>

					<div class="form-group" ng-if="product.type=='Porta Santo'">
						<label class="control-label col-sm-3"></label>
						<div class="col-sm-2">De:</div>
						<div class="col-sm-2">Até:</div>
						<div class="col-sm-2"></div>
						<div class="col-sm-1"></div>						
					</div>

					<div class="form-group" ng-if="product.type=='Porta Santo'">
						<label class="control-label col-sm-3">Sugerimos para imagens:</label>
						<div class="col-sm-2">
							<input type="number" class="form-control input-sm" ng-model="internet.forImage1" novalidate>
						</div>
						<div class="col-sm-2">
							<input type="number" class="form-control input-sm" ng-model="internet.forImage2" novalidate>
						</div>
						<div class="col-sm-2">
							<select class="form-control input-sm" ng-model="internet.forImage3">
								<option>cm</option>
								<option>m</option>
								<option>mm</option>
							</select>
						</div>
						<div class="col-sm-1">A</div>
					</div>

					<div class="form-group" ng-if="product.type=='Porta Terço'">
						<label class="control-label col-sm-3">Para Terços de até</label>
						<div class="col-sm-2">
							<div class="input-group">
								<input type="number" class="form-control input-sm" ng-model="internet.forRosaries">
								<div class="input-group-addon">cm</div>
							</div>
						</div>
					</div>

					<div class="form-group" ng-if="internet.subtype=='Peças de Presépio'">
						<label class="control-label col-sm-3">Para Presépios de</label>
						<div class="col-sm-2">
							<div class="input-group">
								<input type="number" class="form-control input-sm" ng-model="internet.forCribs">
								<div class="input-group-addon">cm</div>
							</div>
						</div>
					</div>

					<div class="form-group" ng-if="enableStoneSize">
						<label class="control-label col-sm-3">Tamanho da Conta:</label>
						<div class="col-sm-2">
							<div class="input-group">
								<input type="number" class="form-control input-sm" ng-model="internet.stoneSize">
								<div class="input-group-addon"><small>mm</small></div>
							</div>
						</div>
					</div>

					<div class="form-group" ng-if="true">
						<label class="control-label col-sm-3">País:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="internet.country" ng-disabled="disableCountry">

								<option>Nacional</option>
								<option>Italiano</option>
								<option>Português</option>
								<option>Importado</option>

							</select>
						</div>
					</div>

					<div class="form-group" ng-if="enableStyle">
						<label class="control-label col-sm-3">Estilo:</label>
						<div class="col-sm-7">
							<select class="form-control input-sm" ng-model="special.style">

								<option>Tradicional</option>
								<option>Barroco</option>
								<option>Neobarroco</option>
								<option>Clássico</option>
								<option>Rustico</option>
								<option>Infantil</option>
								<option>Estilizado</option>

							</select>
						</div>
					</div>

					<div class="form-group" ng-if="internet.subtype=='Presépio'">

						<label class="control-label col-sm-3">Peças do Presépio:</label>

						<div class="col-sm-2">

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.jesus" disabled>
										Jesus
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.melchior" disabled>
										Melchior
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.angel">
										Anjo da Glória
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.chicken">
										Galinha
								</label>
							</div>

						</div>

						<div class="col-sm-2">

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.mary" disabled>
										Maria
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.baltasar" disabled>
										Baltasar
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.shepherd">
										Pastor
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.sheep">
										Ovelha
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.house">
										Casinha
								</label>
							</div>

						</div>

						<div class="col-sm-3">

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.joseph" disabled>
										José
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.gaspar" disabled>
										Gaspar
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.horse">
										Cavalo
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.cow">
										Vaca
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="crib.camel">
										Camelo
								</label>
							</div>

						</div>

					</div>

					<div class="form-group" ng-if="internet.subtype=='Presépio'">
						<label class="control-label col-sm-3">Quantidade de Peças</label>
						<div class="col-sm-2">
							<div class="input-group">
								<input type="number" class="form-control input-sm" ng-model="internet.parts">
								<div class="input-group-addon">peças</div>
							</div>
						</div>
					</div>

					<div class="form-group" ng-if="true">

						<label class="control-label col-sm-3">Apelo:</label>

						<div class="col-sm-3">

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="appeal.gift">
										Presente de aniversário
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="appeal.souvenir">
										Lembrancinha
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="appeal.decor">
										Decoração
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="appeal.protection">
										Proteção
								</label>
							</div>

						</div>

						<div class="col-sm-4">

							<div class="checkbox" ng-if="appeal.decor">
								<label>
									<input type="checkbox" ng-model="appeal.external">
										Ambiente Externo
								</label>
							</div>

						</div>

					</div>

					<div class="form-group" ng-if="true">

						<label class="control-label col-sm-3">Acessórios:</label>

						<div class="col-sm-3">

							<div class="checkbox" ng-if="product.type=='Imagens'">
								<label>
									<input type="checkbox" ng-model="accessory.glassEye">
										Olhos de Vidro
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Imagens'">
								<label>
									<input type="checkbox" ng-model="accessory.halo">
										Auréola
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Imagens'">
								<label>
									<input type="checkbox" ng-model="accessory.resplendor">
										Resplendor
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Imagens'">
								<label>
									<input type="checkbox" ng-model="accessory.crown">
										Coroa
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Imagens'">
								<label>
									<input type="checkbox" ng-model="accessory.niche">
										com Nicho
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Imagens'">
								<label>
									<input type="checkbox" ng-model="accessory.lamp">
										com Abajur
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Jóias'">
								<label>
									<input type="checkbox" ng-model="accessory.motherpearl">
										de Madrepérola
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Jóias'">
								<label>
									<input type="checkbox" ng-model="accessory.zirconia">
										com Zircônia
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Jóias'">
								<label>
									<input type="checkbox" ng-model="accessory.chain">
										com Corrente
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Jóias' || product.type=='Escapulários' || product.type=='Terços'">
								<label>
									<input type="checkbox" ng-model="accessory.clasp">
										com Fecho
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="accessory.swarovski">
										com Swarovski
								</label>
							</div>

							<div class="checkbox" ng-if="product.type=='Escapulários'">
								<label>
									<input type="checkbox" ng-click="openFigure()">
										com Figura
								</label>
							</div>

							<div class="checkbox" ng-if="false">
								<label>
									<input type="checkbox" ng-model="accessory.rhinestones">
										com Strass
								</label>
							</div>

							<div class="checkbox" ng-if="false">
								<label>
									<input type="checkbox" ng-model="accessory.tie">
										com Laço
								</label>
							</div>

							<div class="checkbox" ng-if="false">
								<label>
									<input type="checkbox" ng-model="accessory.candles">
										com Velas
								</label>
							</div>

						</div>

						<div class="col-sm-4">							

							<div class="checkbox" ng-if="internet.material=='Pó de Mármore'">
								<label>
									<input type="checkbox" ng-model="accessory.bronze">
										Acabamento em Bronze
								</label>
							</div>

							<div class="checkbox" ng-if="internet.figure=='Nossa Senhora de Fátima'">
								<label>
									<input type="checkbox" ng-model="accessory.immaculate">
										Imaculado Coração
								</label>
							</div>

							<div class="checkbox" ng-if="internet.figure=='Nossa Senhora de Fátima'">
								<label>
									<input type="checkbox" ng-model="accessory.sheperds">
										com Pastores
								</label>
							</div>

							<div class="checkbox" ng-if="internet.figure=='Nossa Senhora de Lourdes'">
								<label>
									<input type="checkbox" ng-model="accessory.bernadette">
										com Bernadete
								</label>
							</div>

							<div class="checkbox" ng-if="internet.figure=='Nossa Senhora Grávida'">
								<label>
									<input type="checkbox" ng-model="accessory.donkey">
										no Burrinho
								</label>
							</div>

						</div>

					</div>

					<div class="form-group" ng-if="true">

						<label class="control-label col-sm-3">Ocasiões:</label>

						<div class="col-sm-4">

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.christmas">
										Natal
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.firstCommunion">
										Primeira Comunhão
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.baptism">
										Batismo
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.birth">
										Nascimento e Maternidade
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.mothersGift">
										para Mães
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.fathersGift">
										para Pais
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.kids" ng-disabled="event.firstCommunion || event.baptism">
										para Crianças
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.chrism">
										Crisma
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.marriage">
										Casamento
								</label>
							</div>

							<div class="checkbox">
								<label>
									<input type="checkbox" ng-model="event.jubilee">
										Bodas
								</label>
							</div>

						</div>

						<div class="col-sm-4">

							<div class="checkbox" ng-if="event.baptism">
								<label>
									<input type="checkbox" ng-model="event.godfather">
										para Padrinhos
								</label>
							</div>

							<div class="checkbox" ng-if="event.baptism || event.firstCommunion">
								<label>
									<input type="checkbox" ng-model="event.invited">
										para Convidados
								</label>
							</div>

							<div class="checkbox" ng-if="internet.subtype=='Terço'">
								<label>
									<input type="checkbox" ng-model="event.neck">
										para Pescoço
								</label>
							</div>

							<div class="checkbox" ng-if="internet.subtype=='Terço'">
								<label>
									<input type="checkbox" ng-model="event.bride">
										de Noiva
								</label>
							</div>

							<div class="checkbox" ng-if="internet.subtype=='Dezena'">
								<label>
									<input type="checkbox" ng-model="event.car">
										para Carro
								</label>
							</div>

						</div>

					</div>

					<div  ng-repeat="data in variants">
						
						<!-- =============================================================================================================== -->
						<hr>

						<div class="form-group">
							<label class="control-label col-sm-3">SKU:</label>
							<div class="col-sm-3">
								<input type="text" class="form-control input-sm" ng-value="data.sku" disabled>
							</div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-sm-3">Variação:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" ng-value="variableName(data.model,data.variant)" disabled>
							</div>
						</div>
	
						<div class="form-group" ng-if="enableColor">
							<label class="control-label col-sm-3">Cor:</label>
							<div class="col-sm-7">
								<select class="form-control input-sm" ng-model="internet.variant[$index].color">
									<option>Vermelho</option>
									<option>Azul</option>
									<option>Rosa</option>
									<option>Verde</option>
									<option>Amarelo</option>
									<option>Cinza</option>
									<option>Preta</option>
									<option>Transparente</option>
									<option>Colorido</option>
									<option>Dourada</option>
									<option>Prateada</option>
								</select>
							</div>
						</div>

						<div class="form-group" ng-if="accessory.chain">
							<label class="control-label col-sm-3">Tamanho da Corrente:</label>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].chainSize">
									<div class="input-group-addon"><small>cm</small></div>
								</div>
							</div>
						</div>
	
						<div class="form-group" ng-if="true">
							<label class="control-label col-sm-3">Tamanho do Produto:</label>
							<div class="col-sm-2">
								<select class="form-control input-sm" ng-model="internet.variant[$index].sizetype">
									<option>1D</option>
									<option>2D</option>
									<option>3D</option>
								</select>
							</div>
							<div class="col-sm-2">
								<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].size1" novalidate>
							</div>
							<div class="col-sm-2">
								<select class="form-control input-sm" ng-model="internet.variant[$index].size2">
									<option>cm</option>
									<option>m</option>
									<option>mm</option>
								</select>
							</div>
							<div class="col-sm-1">A</div>
						</div>
	
						<div class="form-group" ng-if="internet.variant[$index].sizetype == '2D' || internet.variant[$index].sizetype == '3D'">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-2"></div>
							<div class="col-sm-2">
								<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].size3">
							</div>
							<div class="col-sm-2"></div>
							<div class="col-sm-1">C</div>
						</div>
	
						<div class="form-group" ng-if="internet.variant[$index].sizetype == '3D'">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-2"></div>
							<div class="col-sm-2">
								<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].size4">
							</div>
							<div class="col-sm-2"></div>
							<div class="col-sm-1">L</div>
						</div>
	
						<div class="form-group" ng-if="true">
							<label class="control-label col-sm-3">Caixa:</label>
							<div class="col-sm-7">
								<select class="form-control input-sm" ng-model="internet.variant[$index].box">
									<option>Caixa 1 - 46 x 15 x 22 cm</option>
									<option>Caixa 2 - 39 x 14 x 22 cm</option>
									<option>Caixa 3 - 28,5 x 11 x 16,5 cm</option>
									<option>Caixa 4 - 23 x 10,5 x 10,5 cm</option>
									<option>Caixa 5 - 20,5 x 8 x 15 cm</option>
									<option>Outra</option>
								</select>
							</div>
						</div>
	
						<div class="form-group" ng-if="internet.variant[$index].box == 'Outra'">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-2">
								Altura (cm)
							</div>
							<div class="col-sm-2">
								Comprim. (cm)
							</div>
							<div class="col-sm-2">
								Largura (cm)
							</div>
						</div>
		
						<div class="form-group" ng-if="internet.variant[$index].box == 'Outra'">
							<label class="control-label col-sm-3">Tamanho da Caixa:</label>
							<div class="col-sm-2">
								<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].boxsize1">
							</div>
							<div class="col-sm-2">
								<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].boxsize2">
							</div>
							<div class="col-sm-2">
								<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].boxsize3">
							</div>
						</div>
		
						<div class="form-group" ng-if="true">
							<label class="control-label col-sm-3">Peso (gramas):<br>com caixa</label>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="number" class="form-control input-sm" ng-model="internet.variant[$index].weight">
									<div class="input-group-addon">g</div>
								</div>
							</div>
						</div>

					</div>

					<button class="btn btn-success tc-left" ng-click="nextButton()">Próximo</button>

				</div>

			</form>

		</div>

		<div class="col-sm-4 tc-top-20">

			<div class="row">
				<img ng-src="{{product.picture}}" alt="..." class="img-thumbnail" width="300" height="400">
			</div>

			<div class="row tc-top-20" ng-repeat="data in images">
				<img ng-src="{{data.src}}" alt="..." class="img-thumbnail" width="225" height="300">
			</div>

		</div>

	</uib-tab>

	<uib-tab index="2" heading="Shopify" disable="shopifyTab"  ng-click="clickTab()">

		<div class="col-sm-8 tc-top-20">

			<form class="form-horizontal">
	
				<div class="form-group">
					<label class="control-label col-sm-3">Título Shopify:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" ng-model="shopify.title" ng-disabled="titleDisable">
					</div>
				</div>

				<div class="form-group" ng-hide="titleDisable">
					<label class="control-label col-sm-3"></label>
					<div class="col-sm-7">

						<div class="checkbox"><label><input type="checkbox">
							Seguir aqui o padrão de Título para o produto definido na loja.
						</label></div>
						
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Descritivo Atual:<br><small><i>Aproveitar as partes interessantes</i></small></label>

					<div class="col-sm-7">
						<p ng-bind-html="fromShopify.body_html"></p>
					</div>

				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Novo Descritivo:</label>

					<div class="col-sm-7">
						<textarea class="form-control" rows="30" ng-model="shopify.description"></textarea>
					</div>

				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Lista de Detalhes:</label>

					<div class="col-sm-7">
						<p ng-bind-html="shopify.details"></p>
					</div>

				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Instruções para o Descritivo:</label>
					<div class="col-sm-7">

						<div class="checkbox"><label><input type="checkbox">
							Dividir o texto em parágrafos para facilitar a leitura e, para não ficar um bloco único.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Iniciar explicando o produto. A dica é imaginar como você estaria explicando/vendendo esse produto para um cliente na loja física. Lembrar os pontos que a Michelle sempre menciona sobre esse produto (argumentos/apelos de venda).
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Deve ficar bem claro a qualidade diferenciada do produto. Falar sobre a qualidade do material que o produto é feito.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Falar sobre os simbolismos que você encontra no produto.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Esse produto tem algum dizer escrito, frase, texto. Escrever aqui no descritivo.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Verificar a "Lista de Detalhes" acima e mencionar no descritivo os que forem importante.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Com relação ao "Tamanho" do produto, explicar como ele foi medido.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Olhar bem as fotos para procurar detalhes do produto que podem ser mencionados no descritivo.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Ler o descritivo em voz alta para verificar se está bem escrito.
						</label></div>

						<div class="checkbox"><label><input type="checkbox">
							Ao final, usar sempre o corretor ortográfico do Word para evitar erros.
						</label></div>

					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Instruções específicas:</label>
					<div class="col-sm-7">

						<p class="tc-bot-0">Deve falar sobra as ocasiões em que esse produto pode ser comprado:</p>

						<div class="checkbox" ng-if="event.christmas"><label><input type="checkbox">
							Natal
						</label></div>

						<div class="checkbox" ng-if="event.firstCommunion"><label><input type="checkbox">
							Primeira Comunhão
						</label></div>

						<div class="checkbox" ng-if="event.baptism"><label><input type="checkbox">
							Batismo
						</label></div>

						<div class="checkbox" ng-if="event.birth"><label><input type="checkbox">
							Nascimento
						</label></div>

						<div class="checkbox" ng-if="event.mothersGift"><label><input type="checkbox">
							Para Mães
						</label></div>
						<div class="checkbox" ng-if="event.fathersGift"><label><input type="checkbox">
							Para Pais
						</label></div>

						<div class="checkbox" ng-if="event.kids"><label><input type="checkbox">
							Para Crianças
						</label></div>

						<div class="checkbox" ng-if="event.chrism"><label><input type="checkbox">
							Crisma
						</label></div>
						<div class="checkbox" ng-if="event.marriage"><label><input type="checkbox">
							Casamento
						</label></div>
						<div class="checkbox" ng-if="event.jubilee"><label><input type="checkbox">
							Bodas
						</label></div>

						<div class="checkbox" ng-if="event.godfather"><label><input type="checkbox">
							Para Padrinhos
						</label></div>

						<div class="checkbox" ng-if="event.neck"><label><input type="checkbox">
							Terço para Pescoço
						</label></div>

						<div class="checkbox" ng-if="event.bride"><label><input type="checkbox">
							Terço para Noiva
						</label></div>

						<div class="checkbox" ng-if="event.invited"><label><input type="checkbox">
							Para Convidados
						</label></div>

						<div class="checkbox" ng-if="event.car"><label><input type="checkbox">
							Para Carro
						</label></div>

						<p class="tc-top-10"></p>

						<div class="checkbox" ng-if="product.type == 'Imagens'"><label><input type="checkbox">
							Detalhar os acabamentos especiais que essa imagem possui.
						</label></div>

						<div class="checkbox" ng-if="product.type == 'Anjo'"><label><input type="checkbox">
							Detalhar os acabamentos especiais que essa imagem possui.
						</label></div>

						<div class="checkbox" ng-if="enablePainting"><label><input type="checkbox">
							Detalhar a pintura que a imagem possui.
						</label></div>

						<div class="checkbox" ng-if="appeal.external"><label><input type="checkbox">
							Mencionar que o produto é para ambiente externo.
						</label></div>

						<div class="checkbox" ng-if="accessory.tie"><label><input type="checkbox">
							Mencionar que o produto inclui o laço.
						</label></div>

						<div class="checkbox" ng-if="appeal.protection"><label><input type="checkbox">
							Esse produto é para proteção? Falar sobre isso.
						</label></div>

						<div class="tc-top-10" ng-if="internet.baseFigure == 'Nossa Senhora'">
							<p>A respeito da Nossa Senhora escolhida, mencionar bem resumidamente (caso houver) os itens abaixo:</p>

							<div class="checkbox"><label><input type="checkbox">
								Um pouco da história?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que ela é padroeira?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Ela protege de algo em especial?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								O que os católicos pedem para ela?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Sua data de comemoração.
							</label></div>

						</div>

						<div class="tc-top-10" ng-if="internet.baseFigure == 'Santa'">
							<p>A respeito do Santa escolhida, mencionar bem resumidamente (caso houver) os itens abaixo:</p>

							<div class="checkbox"><label><input type="checkbox">
								Quem é?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que é padroeira?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que ela protege?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								O que os católicos pedem para esse Santo?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Sua data de comemoração.
							</label></div>

						</div>

						<div class="tc-top-10" ng-if="internet.baseFigure == 'Santo'">
							<p>A respeito do Santo escolhido, mencionar bem resumidamente (caso houver) os itens abaixo:</p>

							<div class="checkbox"><label><input type="checkbox">
								Quem é?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que é padroeiro?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que ele protege?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								O que os católicos pedem para esse Santo?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Sua data de comemoração.
							</label></div>

						</div>

						<div class="tc-top-10" ng-if="internet.baseFigure == 'Arcanjo'">
							<p>A respeito do Arcanjo escolhida, mencionar bem resumidamente (caso houver) os itens abaixo:</p>

							<div class="checkbox"><label><input type="checkbox">
								Quem é?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que é padroeiro?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que ele protege?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								O que os católicos pedem para esse Arcanjo?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Sua data de comemoração.
							</label></div>

						</div>

						<div class="tc-top-10" ng-if="internet.figure == 'Divino Espirito Santo'">
							<p>A respeito do Divino Espirito Santo, mencionar bem resumidamente:</p>

							<div class="checkbox"><label><input type="checkbox">
								O que é?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Do que ele protege?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								O que os católicos pedem para o Divino?
							</label></div>
							<div class="checkbox"><label><input type="checkbox">
								Sua data de comemoração.
							</label></div>

						</div>

					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">TAGs:</label>
					<div class="col-sm-7">
						<textarea class="form-control" rows="3" ng-model="shopify.tags" disabled></textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">TAGs Antigas:</label>
					<div class="col-sm-7">
						<textarea class="form-control" ng-model="oldTags" disabled></textarea>
					</div>
				</div>

				<button class="btn btn-success tc-left" ng-click="nextButton2()">Próximo</button>

			</form>

		</div>
		
		<div class="col-sm-4 tc-top-20">

			<div class="row">
				<img ng-src="{{product.picture}}" alt="..." class="img-thumbnail" width="300" height="400">
			</div>

			<div class="row tc-top-20" ng-repeat="data in images">
				<img ng-src="{{data.src}}" alt="..." class="img-thumbnail" width="225" height="300">
			</div>

		</div>

	</uib-tab>

	<uib-tab index="3" heading="Google SEO" disable="googleTab">

		<div class="col-sm-12 tc-top-20">
			
			<p>Aqui você vai escrever as o título e o descritivo do produto conforme ele aparece na busca do Google. O que é importante considerar:</p>

			<ul>
				<li>Quando o cliente buscar no Google e observar os vários resultados da busca, sugeridos pelo Google, ele irá "escanear" (isso é, ler/visualizar rapidamente) alguns deles. Alguns vão CHAMAR A ATENÇÃO. Destes, ele irá ler um pouco melhor e, se decidir que pode ser útil, ele normalmente abre o resultado em "outra aba" do navegador.</li>
				<li>Dentre os resultado, o Google vai contabilizando os mostrados que têm a maior quantidade de cliques. Com o tempo o Google vai ordenando os resultado que têm o maior número de cliques para cima e os de menor número para baixo.</li>
				<li>Desta forma, nosso objetivo aqui é, tanto para o título quanto para a descrição, CHAMAR A ATENÇÃO do cliente. Também é necessário que ao escanear/ler, o cliente tenha a sensação de que na página clicada ele vai ACHAR O QUE ESTÁ BUSCANDO.</li>
			</ul>			
		</div>

		<div class="col-sm-8 tc-top-20">

			<form class="form-horizontal">

				<div class="form-group">

					<label class="control-label col-sm-3">Título Google:</label>

					<div class="col-sm-7">

						<input type="text" class="form-control" ng-model="google.title">

						<strong>{{googleTitleMax - google.title.length}}</strong>
		
						<div>
							<p class="tc-top-10 tc-bot-0">Requisitos:</p>
		
							<div class="checkbox tc-top-0"><label><input type="checkbox">
								Preencher o máximo possível com as informações mais importantes até o limite do Google (60 caracteres).
							</label></div>
						</div>

					</div>

				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Descritivo Google:</label>
					<div class="col-sm-7">
						<textarea class="form-control" rows="5"></textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">URL Página:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control">
					</div>
				</div>

			</form>

			<div class="panel panel-default"><div class="panel-body">

				<cite class="google-cite">www.lojaterracotta.com.br</cite>
				<i class="fas fa-sort-down google-i"></i>

				<h3 class="google-h3">TerraCotta Arte Sacra</h3>

				<span class="google-span">A TerraCotta Arte Sacra é sua loja de artigos religiosos católicos. Os mais belos produtos católicos do mundo para você. Imagens, Terços, Primeira Comunhão, ...</span>

			</div></div>

			<h3>Exemplos:</h3>

			<div class="panel panel-default"><div class="panel-body">

				<cite class="google-cite">www.lojaterracotta.com.br</cite>
				<i class="fas fa-sort-down google-i"></i>
	
				<h3 class="google-h3">Imagem de Nossa Senhora de Fátima de Pó de Mármore - 30 cm</h3>

				<span class="google-span">A TerraCotta Arte Sacra é sua loja de artigos religiosos católicos. Os mais belos produtos católicos do mundo para você. Imagens, Terços, Primeira Comunhão, ...</span>

			</div></div>
		
		</div>
		
	</uib-tab>

</uib-tabset>