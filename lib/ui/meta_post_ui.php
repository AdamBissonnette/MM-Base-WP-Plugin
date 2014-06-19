<?php /* Handles basic ui wrapper for all taxonomy meta sections */ ?>
<div class="mmpm_wrapper">

	<div class="row form-horizontal">
		<?php wp_nonce_field( 'mm_nonce', 'mm_nonce' ); ?>

		<?php
			echo OutputThemeData($options, $values);
		?>
	</div>
</div>