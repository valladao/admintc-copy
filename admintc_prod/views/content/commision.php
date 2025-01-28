<div class="row">
  <div class="tc-top-20">
    <form class="form-inline">
      <div class="form-group tc-left-25 tc-right-25">
        <label for="seller">Vendedor </label>
        <select class="form-control tc-left-15" id="seller" ng-model="commision.salesman">
          <option ng-repeat="option in salesman" value="{{option.username}}">{{option.firstName}}</option>
        </select>
      </div>
      <div class="form-group tc-right-25">
        <label for="month">Mês </label>
        <select class="form-control tc-left-15" id="month" ng-model="commision.month">
          <option value="1">Janeiro</option>
          <option value="2">Fevereiro</option>
          <option value="3">Março</option>
          <option value="4">Abril</option>
          <option value="5">Maio</option>
          <option value="6">Junho</option>
          <option value="7">Julho</option>
          <option value="8">Agosto</option>
          <option value="9">Setembro</option>
          <option value="10">Outubro</option>
          <option value="11">Novembro</option>
          <option value="12">Dezembro</option>
        </select>
      </div>
      <div class="form-group tc-right-25">
        <label for="year">Ano </label>
        <select class="form-control tc-left-15" id="year" ng-model="commision.year">
          <option value="2021">2021</option>
          <option value="2022">2022</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
          <option value="2025">2025</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary" ng-click="getCommision()">Buscar</button>
    </form>
  </div>
</div>

    <div class="row">
      <div class="col-sm-5 tc-top-20">
        <h4 class="bg-warning tc-alert tc-center">
          <span ng-if="commision.salesman && commision.month && commision.year">{{commision.salesman | properCase }} | {{commision.month | translateMonth2 }} / {{commision.year}}</span>
        </h4>
      </div>
    </div>

    <div class="row">

      <div class="col-sm-5 tc-top-20">

        <h4>Meta Pessoal</h4>

        <div class="progress">
          <div class="progress-bar progress-bar-success" ng-style="{ width: personalBar1 }">
            {{sellerText1}}
          </div>
        </div>

      </div>

    </div>

    <div class="row">

      <div class="col-sm-5 tc-top-20">

        <h4>Meta Loja</h4>

        <div class="progress">
          <div class="progress-bar" ng-class="{'progress-bar-success': storeBar11 == '60%'}" ng-style="{ width: storeBar11 }">
            {{storeText11}}
          </div>
          <div class="progress-bar progress-bar-warning" ng-style="{ width: storeBar12 }">
            {{storeText12}}
          </div>
          <div class="progress-bar progress-bar-danger" ng-style="{ width: storeBar13 }">
            {{storeText13}}
          </div>
        </div>

        <h4>Meta TerraCotta</h4>

        <div class="progress">
          <div class="progress-bar" ng-class="{'progress-bar-success': terracottaBar11 == '60%' }" ng-style="{ width: terracottaBar11 }">
            {{terracottaText11}}
          </div>
          <div class="progress-bar progress-bar-warning" ng-style="{ width: terracottaBar12 }">
            {{terracottaText12}}
          </div>
          <div class="progress-bar progress-bar-danger" ng-style="{ width: terracottaBar13 }">
            {{terracottaText13}}
          </div>
        </div>

      </div>

    </div>

    <div class="row">
      <div class="col-sm-5 tc-top-20">
        <table class="table">
          <tbody>
            <tr>
              <td class="tc-y-center tc-center tc-no-boarder">
                <h4>Valor | Estimativa:</h4>
              </td>
              <td class="tc-y-center tc-no-boarder">
                <h2 class="tc-top-10">R$ {{commission1 | dot2comma}}</h2>
              </td>
            </tr>
            <tr>
              <td class="tc-no-boarder" colspan="2">
                <h4 class="tc-center tc-y-center">{{commission_text1}}</h4>
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
