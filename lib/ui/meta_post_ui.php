<?php /* Handles basic ui wrapper for all taxonomy meta sections */
global $Mmm_Bingo;?>
<div class="mmb_wrapper mmm_postmeta_wrapper">

	<div class="row form-horizontal">
		<?php wp_nonce_field( 'mm_nonce', 'mm_nonce' ); ?>

		<?php
			$post_id = get_the_ID();

			echo MmmToolsNamespace\OutputThemeData($options, $values, $Mmm_Bingo);
		?>
	</div>
</div>