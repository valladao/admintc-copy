<div class="container">

<?php

$class = array('class' => 'form-signin');
echo form_open('external/validate', $class);

if (isset($error))
{
	echo '<p class="text-danger">'.$error.'</p>';
	//echo '<p class="text-error">Usuário e/ou senha inválida. Acesso não permitido.</p>';
}
else
{
	echo '<p class="text-danger">'.$error.'</p>';
	//echo '<p class="text">Acesso restrito apenas aos funcionários.</p>';
}

?>

	<h2 class="form-signin-heading">Idenfique-se / Entrar</h2>
	<div class="form-group tc-top-20">
		<label class="sr-only">Usuário</label>
		<input type="text" class="form-control" name="username" placeholder="Usuário" required autofocus>
	</div>
	<div class="form-group">
		<label class="sr-only">Senha</label>
		<input type="password" class="form-control" name="password" placeholder="Senha" required>
	</div>
	<div class="form-group">
	
		<div class="col-sm-4">
			<label class="control-label">Loja:</label>
		</div>
	
		<div class="col-sm-8">
			<div class="radio-inline">
				<label>
					<input type="radio" name="store" value="moema">
					Moema
				</label>
			</div>
	
			<div class="radio-inline">
				<label>
					<input type="radio" name="store" value="jardins">
					Jardins
				</label>
			</div>

			<div class="radio-inline">
				<label>
					<input type="radio" name="store" value="museu">
					Museu
				</label>
			</div>

			<div class="radio-inline tc-left-0">
				<label>
					<input type="radio" name="store" value="morumbi">
					Morumbi
				</label>
			</div>

		</div>
	</div>
	<div class="col-sm-6 col-sm-offset-3 tc-top-20">

<?php
	$data = array(
		'class' => 'btn btn-primary btn-block',
		'type' => 'submit',
		'content' => 'Entrar'
	);
		
	echo form_button($data);
?>

	</div>

<?php

echo form_close();

?>

</div>
