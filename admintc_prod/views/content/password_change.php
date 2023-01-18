<div class="col-sm-5 tc-top-20">

  <form>

		<input class="form-control" type="hidden" name="username" value="<?php echo ucfirst($this->session->userdata('username')); ?>" disabled>

    <div class="form-group">
      <label for="currentPassword">Senha Atual</label>
      <input type="password" class="form-control" id="currentPassword" placeholder="Preencha aqui sua senha atual." ng-model="password.current">
    </div>

    <div class="form-group">
      <label for="newPassword">Nova Senha</label>
      <input type="password" class="form-control" id="newPassword" placeholder="Nova Senha" ng-model="password.new">
    </div>

    <div class="form-group">
      <label for="repeatPassword">Confirmar Senha</label>
      <input type="password" class="form-control" id="repeatPassword" placeholder="Repetir Nova Senha" ng-model="password.repeat">
    </div>

    <button class="btn btn-default tc-top-20" ng-click="changePassword()">Enviar</button>

  </form>

  <div class="row tc-top-20">
    <div class="alert alert-danger" role="alert" ng-show="error">{{error}}</div>
    <div class="alert alert-warning" role="alert" ng-show="wait">Aguarde...</div>
    <div class="alert alert-success" role="alert" ng-show="updated">Senha Atualizada!</div>
  </div>

</div>
