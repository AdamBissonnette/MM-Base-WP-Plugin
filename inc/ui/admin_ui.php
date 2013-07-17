<div class="mmpm_wrapper">
	<div class="row">
		<div class="span12">
			<h3>Mmm Trackables Options</h3>
		</div>
	</div>

	<div class="row">
		<form id="<?php echo $this->_setting_prefix . '_settings_form'; ?>" name="<?php echo $this->_setting_prefix . '_settings_form'; ?>" onsubmit="javascript: SaveOptions(this);" class="form-horizontal" method="post">
		
		<?php
			global $trackable_options;
			
			echo OutputMMData($trackable_options);
		?>
		
		<div class="row">
			<div class="span12">
				<div class="form-actions clearfix">
					<a href="#" id="btnOptionsSave" name="mm_pm_settings_saved" class="btn btn-primary">Save</a>
					<input type="reset" class="btn" />
				</div>
			</div>
		</div>
		</form>
	</div>


	<div class="modal hide fade" id="mm-dialog">
	    <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h3 id="mm-dialog-title"></h3>
	    </div>
	    <div class="modal-body" id="mm-dialog-message">
	    
	    </div>
	    <div class="modal-footer">
	        <a href="#" data-dismiss="modal" class="btn">Close</a>
	    </div>
	</div>
</div>