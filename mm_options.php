<div class="mmem_wrapper container">
	<div class="row">
		<div class="span12">
			<?php screen_icon(); ?>
			<h2><?php echo $_plugin_name ?> Manager</h2>
		</div>
	</div>
	<div class="row" style="margin-top: 30px;">
		<div class="span10 tabbable">
			<ul class="nav nav-tabs">
				<li><a href="#overview" data-toggle="tab"><i class="icon-home"></i> Getting Started</a></li>
				<li class="active"><a id="plisttab" href="#list" data-toggle="tab"><i class="icon-list"></i> List</a></li>
				<li><a id="addtab" href="#add" data-toggle="tab"><i class="icon-plus"></i> Add / Edit</a></li>
				<li><a id="optionstab" href="#options" data-toggle="tab"><i class="icon-cog"></i> Options</a></li>
			</ul>
			
			
			<div class="tab-content">
				<div id="overview" class="tab-pane">
					<legend>Getting Started</legend>
					<p>Looks like you're new here!  Follow these steps and you'll be on your way to fortunes untold.</p>
					
					<ol>
						<li>Setup this plugin in the <a id="btnOverviewOptions" href="#">Options</a> tab</li>
						<li>Create events</li>
						<li>Output the events using the available shortcodes</li>
						<li>Review the attendees of your events on the event report page</li>
					</ol>
				</div>
				
				<div id="purchases" class="tab-pane">
					<legend>Active Events</legend>
					
<?php
//echo genActiveEventReport();
?>
				</div>
			
				<div id="options" class="tab-pane">
				    <form id="mm_em_settings_form" name="mm_em_settings_form" class="form-horizontal" method="post">
				    <fieldset>
				    	<legend>Options</legend>
				        
				        <?php echo GenInput($_plugin_slug . "variable", "Variable", "X", "", $this->_settings[$_plugin_slug . 'variable'], "Note: Things."); ?>
				        
				        <div class="control-group">
				        	<label class="control-label" for="mm_em_notifyemail">Notify Email</label>
				        	<div class="controls">
					        	
					        	<input id="mm_em_notifyemail" class="req" type="text" name="mm_em_notifyemail" value="<?php echo($this->_settings['mm_em_notifyemail']); ?>" />
					        	<p class="help-block">note: The email we will send notifications to when selecting a recipient is not an option for the given form</p>
				        	</div>
				        </div>
				        
				        <div class="form-actions clearfix">
				            <a href="#" id="btnOptionsSave" name="mm_em_settings_saved" class="btn btn-primary">Save</a>
				            <input type="reset" class="btn" />
				        </div>
				        </fieldset>
				    </form>
			    </div>
			    
			    <div id="list" class="tab-pane active">
			        <form name="mm_em_event_list_form" method="post">
			        	<legend>Object List</legend>
			        	
			        	<div class="well">
			                <a id="btnListAdd" href="#add" class="btn-primary btn">
				                <i class="icon-plus-sign icon-white"></i> 
				                Add Object
			                </a>
			            </div>
	
<?php
	//OutputEventList();
?>       		
			            </table>   
			            
			        </form>
			    </div>
			    
			    <div id="add" class="tab-pane">
				    <form id="mm_object_form" name="mm_object_form" method="post" class="form-horizontal">
				    	<legend>Add / Edit Object</legend>
				    	<fieldset class="row">
				    		<div class="span10">
								<input id="<?php echo $_plugin_slug; ?>pid" type="hidden" value="-1" />
								
								<?php
									echo GenInput($_plugin_slug . "oname", "Name");
								?>
								
								<div class="control-group">
									<label class="control-label" for="<?php echo $_plugin_slug; ?>pdesc">Description</label>
									<div class="controls">
										<?php
											$id = $_plugin_slug . 'pdesc';
											wp_editor( "", $id);
										 ?>
									</div>
								</div>
					        </div>
					        
					        <div class="span8 form-actions clearfix">
					            <a href="javascript: void(0);" id="btnObjectSave" class="btn btn-primary">Save</a>
					            <input type="reset" class="btn" />
					        </div>
				        </fieldset>
				    </form>
			    </div>
		    </div>
	    </div>
	</div>
</div>