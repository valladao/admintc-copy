<input class="form-control" type="hidden" name="username" value="<?php echo ucfirst($this->session->userdata('username')); ?>" disabled>

<uib-tabset>

  <uib-tab index="0" heading="Mês Atual">

    <div class="row">
      <div class="col-sm-5 tc-top-20">
        <h4 class="bg-warning tc-alert tc-center">
          {{month1 | translateMonth }} / {{year1}}
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

  </uib-tab>

  <uib-tab index="1" heading="Mês Anterior">

    <div class="row">
      <div class="col-sm-5 tc-top-20">
        <h4 class="bg-warning tc-alert tc-center">
          {{month2 | translateMonth }} / {{year2}}
        </h4>
      </div>
    </div>

    <div class="row">

      <div class="col-sm-5 tc-top-20">

        <h4>Meta Pessoal</h4>

        <div class="progress">
          <div class="progress-bar progress-bar-success" ng-style="{ width: personalBar2 }">
            {{sellerText2}}
          </div>
        </div>

      </div>

    </div>

    <div class="row">

      <div class="col-sm-5 tc-top-20">

        <h4>Meta Loja</h4>

        <div class="progress">
          <div class="progress-bar" ng-class="{'progress-bar-success': storeBar21 == '60%'}" ng-style="{ width: storeBar21 }">
            {{storeText21}}
          </div>
          <div class="progress-bar progress-bar-warning" ng-style="{ width: storeBar22 }">
            {{storeText22}}
          </div>
          <div class="progress-bar progress-bar-danger" ng-style="{ width: storeBar23 }">
            {{storeText23}}
          </div>
        </div>

        <h4>Meta TerraCotta</h4>

        <div class="progress">
          <div class="progress-bar" ng-class="{'progress-bar-success': terracottaBar21 == '60%'}" ng-style="{ width: terracottaBar21 }">
            {{terracottaText21}}
          </div>
          <div class="progress-bar progress-bar-warning" ng-style="{ width: terracottaBar22 }">
            {{terracottaText22}}
          </div>
          <div class="progress-bar progress-bar-danger" ng-style="{ width: terracottaBar23 }">
            {{terracottaText23}}
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
                <h2 class="tc-top-10">R$ {{commission2 | dot2comma}}</h2>
              </td>
            </tr>
            <tr>
              <td class="tc-no-boarder" colspan="2">
                <h4 class="tc-center tc-y-center">{{commission_text2}}</h4>
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

  </uib-tab>

</uib-tabset>