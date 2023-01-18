<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<a class="tc-logo navbar-brand" href="/"><img alt="TerraCotta" src="/assets/img/logo-white.png"></a>
</div>

<!-- Top Menu Items -->

<div class="nav navbar-left top-nav">
	<h3 class="navbar-text tc-page-title"><?php

		echo $title;

	?></h3>
</div>

<ul class="nav navbar-right top-nav">
	<li>
		<a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> Loja <?php

			echo ucfirst($this->session->userdata('store'));

		?></a>
	</li>
	<li class="dropdown" uib-dropdown>

		<a href="" class="dropdown-toggle" data-toggle="dropdown" uib-dropdown-toggle><i class="fa fa-user"></i> <?php

			// Insert name
			echo $this->session->userdata('firstName').' '.$this->session->userdata('lastName');

		?> <b class="caret"></b></a>

		<ul class="dropdown-menu" uib-dropdown-menu>
			<li>
				<a href="<?php

					 echo site_url('pages/password_change');

				?>"><i class="fa fa-fw fa-lock"></i> Trocar Senha</a>
			</li>
			<li>
				<a href="<?php

					 echo site_url('helper/logout');

				?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
			</li>
		</ul>
	</li>
</ul>
