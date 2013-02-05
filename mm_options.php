<div class="mmem_wrapper container">
	<div class="row">
		<div class="span12">
			<?php screen_icon(); ?>
			<h2><?php echo $this->_plugin_name ?></h2>
		</div>
	</div>
	<div class="row" style="margin-top: 30px;">
		<div class="span10 tabbable">
			<ul class="nav nav-tabs">
				<li class="active"><a id="optionstab" href="#options" data-toggle="tab"><i class="icon-cog"></i> Options</a></li>
			</ul>			
				<div id="options" class="tab-pane active">
				    <form id="mm_fl_settings_form" name="mm_fl_settings_form" class="form-horizontal" method="post">
				    <fieldset>
				    	<legend>Options</legend>
				        
				        <?php
				        	$listTemplate = '<ul class="mm-dir-list">%s</ul>';
							$listItemTemplate = '<li><a href="%s">%s</a></li>';
				        
				        	if ($this->_settings[$_plugin_slug . 'list_format'] != '')
							{
								$listTemplate = $this->_settings[$_plugin_slug . 'list_format'];
							}
							
							if ($this->_settings[$_plugin_slug . 'item_format'] != '')
							{
								$listItemTemplate = $this->_settings[$_plugin_slug . 'item_format'];
							}
				        
				        	echo genInput("mm_fl_list_format", "Html List Format", $placeholder = "", $validationType = "req", $listTemplate, $note = "");
				        	echo genInput("mm_fl_item_format", "Html Item Format", $placeholder = "", $validationType = "req", $listItemTemplate, $note = "");
				         ?>
				        
				        <div class="form-actions clearfix">
				            <a href="#" id="btnOptionsSave" name="mm_em_settings_saved" class="btn btn-primary">Save</a>
				            <input type="reset" class="btn" />
				        </div>
				        </fieldset>
				    </form>
			    </div>
		    </div>
	    </div>
	</div>
</div>