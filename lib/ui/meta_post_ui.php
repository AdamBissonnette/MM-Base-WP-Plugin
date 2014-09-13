<?php /* Handles basic ui wrapper for all taxonomy meta sections */
global $Mmm_Class_Manager;?>
<div class="mmpm_wrapper">

	<div class="row form-horizontal">
		<?php wp_nonce_field( 'mm_nonce', 'mm_nonce' ); ?>

		<?php
			echo OutputMMData($options, $values, $Mmm_Class_Manager);
		?>
	</div>
</div>