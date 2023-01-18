<div class="row">
  <div class="tc-top-20">
    <form class="form-inline">
      <div class="form-group tc-right-25">
        <label for="seller">Vendedor </label>
        <select class="form-control" id="seller" ng-model="commision.salesman">
          <option ng-repeat="option in salesman" value="{{option.username}}">{{option.firstName}}</option>
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
      <div class="form-group tc-right-25">
        <label for="exampleInputEmail2">Mês </label>
        <input type="email" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com">
      </div>
      <div class="form-group tc-right-25">
        <label for="exampleInputEmail2">Ano </label>
        <input type="email" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com">
      </div>
      <button type="submit" class="btn btn-default">Send invitation</button>
    </form>
  </div>
</div>
