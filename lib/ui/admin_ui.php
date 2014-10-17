<?php
	global $MMM_Class_Manager;

	$page = $_GET["page"];

	$active_page = str_replace("Mmm_Class_Manager_", "", $page);

	$plugin = "";
	$admin = "";

	switch ($active_page) {
		case 'Classes':
				$plugin = "active";
			break;
		case 'Admin':
				$admin = "active";
			break;
	}
?>

<div class="mmpm_wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3>MmmClass Options</h3>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-12">
				<ul class="nav nav-pills">
					<li class="<?php echo $plugin ?>"><a href="#plugin" data-toggle="tab">Classes / Reporting</a></li>
					<li class="<?php echo $admin ?>"><a href="#admin" data-toggle="tab">Settings</a></li>
				</ul>
				<div class="row tab-content">
					<div class="tab-pane <?php echo $plugin ?>" id="plugin">
						<?php							
							echo MmmToolsNamespace\OutputThemeData($mmm_plugin_data, null, $MMM_Class_Manager);
						?>
					</div>
					<div class="tab-pane <?php echo $admin ?>" id="admin">
						<form id="theme_settings" onsubmit="javascript: SaveOptions(this);" class="form-horizontal" method="post">
							<?php							
								echo MmmToolsNamespace\OutputThemeData($mmm_class_options, null, $MMM_Class_Manager);
							?>
							
							<div class="form-controls">
								<div class="col-sm-12">
									<div class="form-actions clearfix">
										<a href="#" id="btnOptionsSave" name="mmm_settings_saved" class="btn btn-primary">Save</a>
										<input type="reset" class="btn" />
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="mm-dialog">
			<div class="modal-dialog">
				<div class="modal-content">
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
		</div>

		<?php add_thickbox(); ?>


	</div>
</div>