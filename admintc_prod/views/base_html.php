<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<?php

$this->load->view('resources/head');

?>

<body ng-app="TerraCottaApp">

	<div id="wrapper">

<?php

$this->load->view('resources/navigation');

?>

		<div id="page-wrapper">

			<div class="container-fluid">

<?php

$this->load->view('resources/switch_content');

?>

			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- /#page-wrapper -->


	</div>
	<!-- /#wrapper -->

</body>

</html>