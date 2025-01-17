<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/ares/main.php');?>
<?php
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('app')!=get_app_info('restricted_to_app'))
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/autoresponders-edit?i='.get_app_info('restricted_to_app').'&a='.$_GET['a'].'&ae='.$_GET['ae'].'"</script>';
			exit;
		}
	}
	$edit = true;
	$dark_mode = get_app_info('dark_mode');
	
	$time_condition_number = '';
	$selected_minutes = '';
	$selected_hours = '';
	$selected_days = '';
	$selected_weeks = '';
	$selected_months = '';
	$selected_before = '';
	$selected_after = '';
	$selected_on = '';
	
	$list_id = get_ares_data('list');
	$ae = isset($_GET['ae']) && is_numeric($_GET['ae']) ? mysqli_real_escape_string($mysqli, (int)$_GET['ae']) : 0;
?>

<script src="<?php echo get_app_info('path');?>/js/ckeditor/ckeditor.js?8"></script>
<?php 
	if(get_saved_data('wysiwyg'))
	{
		$html_code_msg = '<span class="wysiwyg-note">'._('Switch to HTML editor if the WYSIWYG editor is causing your newsletter to look weird.').'</span>';
		include('js/create/editor.php');
	}
	else
		$html_code_msg = '<span class="wysiwyg-note">'._('Switch to the WYSIWYG editor to use formatting tools.').'</span>';
?>

<!-- Validation -->
<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/validate.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#edit-form").validate({
			rules: {
				subject: {
					required: true	
				},
				from_name: {
					required: true	
				},
				from_email: {
					required: true,
					email: true
				},
				reply_to: {
					required: true,
					email: true
				},
				html: {
					required: true
				},
				time_condition_number: {
					required: true
				}
			},
			messages: {
				subject: "<?php echo addslashes(_('The subject of your email is required'));?>",
				from_name: "<?php echo addslashes(_('\'From name\' is required'));?>",
				from_email: "<?php echo addslashes(_('A valid \'From email\' is required'));?>",
				reply_to: "<?php echo addslashes(_('A valid \'Reply to\' email is required'));?>",
				html: "<?php echo addslashes(_('Your HTML code is required'));?>",
				time_condition_number: "<?php echo addslashes(_('Please specify a number'));?>"
			}
		});
				
		//drip
		$("#time_condition_intervals").change(function(){			
			if($(this).find(":selected").text()=='<?php echo _('immediately');?>')
				$("#time_condition_number").hide();
			else
			{
				$("#time_condition_number").show();
				$("#time_condition_number").select();
			}
		});
		
		//others
		$("#time_condition_beforeafter").change(function(){			
			if($(this).find(":selected").text()=='<?php echo _('on');?>')
			{
				$("#time_condition_number").hide();
    			$("#time_condition_intervals").hide();
			}
			else
			{
				$("#time_condition_number").show();
    			$("#time_condition_intervals").show();
    			$("#time_condition_number").select();
			}
		});
		
		//Check if Grammarly extension is installed in the browser, if so, inform the user
		setTimeout(	function()	{if($("grammarly-btn").length) $("#grammarly-error").slideDown();}, 5000);
		
		<?php if(!get_saved_data('wysiwyg')):?>
			//Save campaign only
			$("#autoresponder-save-only-btn").click(function(e){
				e.preventDefault(); 
				$('<input>').attr({type: 'hidden',id: 'save-only',name: 'save-only',value: 1}).appendTo('form');
				$("#edit-form").submit();
			});
		<?php endif;?>
	});
</script>

<div class="row-fluid">
    <div class="span2">
        <?php include('includes/sidebar.php');?>
    </div> 
    <div class="span10">
    	<div class="row-fluid">
	    	<div>
		    	<p class="lead">
		    	<?php if(get_app_info('is_sub_user')):?>
			    	<?php echo get_app_data('app_name');?>
		    	<?php else:?>
			    	<a href="<?php echo get_app_info('path'); ?>/edit-brand?i=<?php echo get_app_info('app');?>" data-placement="right" title="<?php echo _('Edit brand settings');?>"><?php echo get_app_data('app_name');?> <span class="icon icon-pencil top-brand-pencil"></span></a>
		    	<?php endif;?>
		    </p>
	    	</div>
	    	<h2><?php echo _('Edit autoresponder email');?></h2>
	    	<strong><?php echo get_ares_type_name('type');?></strong>: <a href="<?php echo get_app_info('path')?>/autoresponders-emails?i=<?php echo get_app_info('app')?>&a=<?php echo $_GET['a']?>" title=""><span class="label label-info"><?php echo get_ares_data('name');?></span></a>
	    	<br/><br/>
    	</div>
    	
    	<form action="<?php echo get_app_info('path')?>/includes/ares/save-autoresponder-email.php?i=<?php echo get_app_info('app')?>&a=<?php echo $_GET['a']?>&ae=<?php echo $ae?>&edit=true" method="POST" accept-charset="utf-8" class="form-vertical" id="edit-form" enctype="multipart/form-data">
    	
    	<div class="row-fluid">
    		<div class="span12 well">
    		
    			<?php if(get_ares_data('type')==1):?>
    			
    			<?php 
    				$saved_time_condition = get_saved_data('time_condition');
    				
	    			if($saved_time_condition=='immediately')
	    			{
	    				$selected_immediately = 'selected';
	    			}
	    			else
	    			{
	    				$selected_immediately = '';
	    				
		    			if(strpos($saved_time_condition,_('minutes')) !== false)
		    				$selected_minutes = 'selected';
		    			else if(strpos($saved_time_condition,_('hours')) !== false)
		    				$selected_hours = 'selected';
		    			else if(strpos($saved_time_condition,_('days')) !== false)
		    				$selected_days = 'selected';
		    			else if(strpos($saved_time_condition,_('weeks')) !== false)
		    				$selected_weeks = 'selected';
		    			else if(strpos($saved_time_condition,_('months')) !== false)
		    				$selected_months = 'selected';
		    			
		    			echo '<script type="text/javascript">
	    				$(document).ready(function() {
	    					$("#time_condition_number").show();
	    					$("#time_condition_intervals").show();
						});
	    				</script>';
	    				
	    				$time_condition_number_array = explode(' ', substr($saved_time_condition, 1));
	    				$time_condition_number = $time_condition_number_array[0];
		    		}
    			?>
    			
    			<div>
		    		<i class="icon-time"></i> <?php echo _('Send email');?> <input type="text" name="time_condition_number" id="time_condition_number" value="<?php echo $time_condition_number;?>" style="width: 20px; text-align:center; margin-top: 8px; height: 19px;">
		    		<select name="time_condition_intervals" id="time_condition_intervals" style="width: auto; margin-top: 7px;">
		    			<option value="immediately" <?php echo $selected_immediately;?>><?php echo _('immediately');?></option>
			    		<option value="minutes" <?php echo $selected_minutes;?>><?php echo _('minutes');?></option>
			    		<option value="hours" <?php echo $selected_hours;?>><?php echo _('hours');?></option>
			    		<option value="days" <?php echo $selected_days;?>><?php echo _('days');?></option>
			    		<option value="weeks" <?php echo $selected_weeks;?>><?php echo _('weeks');?></option>
			    		<option value="months" <?php echo $selected_months;?>><?php echo _('months');?></option>
		    		</select>
		    		<?php echo _('after they subscribe');?>
		    		<?php echo _('and');?> 
		    		<a href="javascript:void(0)" title="" class="btn" id="apply-to-segment-btn"><i class="icon icon-filter"></i> <?php echo _('apply to these segments only');?></a>
		    		 
		    		<input type="hidden" name="time_condition_beforeafter" id="time_condition_beforeafter" value="after">
		    		<script type="text/javascript">
	    				$("#time_condition_number").hide();
	    			</script>
    			</div>
	    		 
	    		<?php else:?>
	    		
	    		<?php 
    				$saved_time_condition = get_saved_data('time_condition');
    				
	    			if($saved_time_condition=='')
	    			{
		    			$selected_on = 'selected';
	    			}
	    			else
	    			{
		    			if(strpos($saved_time_condition,'minutes') !== false)
		    				$selected_minutes = 'selected';
		    			else if(strpos($saved_time_condition,'hours') !== false)
		    				$selected_hours = 'selected';
		    			else if(strpos($saved_time_condition,'days') !== false)
		    				$selected_days = 'selected';
		    			else if(strpos($saved_time_condition,'weeks') !== false)
		    				$selected_weeks = 'selected';
		    			else if(strpos($saved_time_condition,'months') !== false)
		    				$selected_months = 'selected';
		    				
		    			echo '<script type="text/javascript">
	    				$(document).ready(function() {
	    					$("#time_condition_number").show();
	    					$("#time_condition_intervals").show();
						});
	    				</script>';
	    				
	    				$time_condition_number_array = explode(' ', substr($saved_time_condition, 1));
	    				$time_condition_number = $time_condition_number_array[0];
	    				
	    				if(substr($saved_time_condition, 0, 1)=='-')
	    					$selected_before = 'selected';
	    				else if(substr($saved_time_condition, 0, 1)=='+')
	    					$selected_after = 'selected';
		    		}
    			?>
	    		
	    		<div>
		    		<i class="icon-time"></i> <?php echo _('Send email');?> <input type="text" name="time_condition_number" id="time_condition_number" value="<?php echo $time_condition_number;?>" style="width: 20px; text-align:center; margin-top: 8px; height: 19px;">
		    		<select name="time_condition_intervals" id="time_condition_intervals" style="width: auto; margin-top: 7px;">
			    		<option value="minutes" <?php echo $selected_minutes;?>><?php echo _('minutes');?></option>
			    		<option value="hours" <?php echo $selected_hours;?>><?php echo _('hours');?></option>
			    		<option value="days" <?php echo $selected_days;?>><?php echo _('days');?></option>
			    		<option value="weeks" <?php echo $selected_weeks;?>><?php echo _('weeks');?></option>
			    		<option value="months" <?php echo $selected_months;?>><?php echo _('months');?></option>
		    		</select>
		    		<select name="time_condition_beforeafter" id="time_condition_beforeafter" style="width: auto; margin-top: 7px;">
		    			<option value="on" <?php echo $selected_on;?>><?php echo _('on');?></option>
		    			<option value="before" <?php echo $selected_before;?>><?php echo _('before');?></option>
		    			<option value="after" <?php echo $selected_after;?>><?php echo _('after');?></option>
		    		</select>
		    		<?php echo _('each subscriber\'s');?> '<?php echo get_ares_data('custom_field');?>'
		    		<?php echo _('and');?> 
		    		<a href="javascript:void(0)" title="" class="btn" id="apply-to-segment-btn"><i class="icon icon-filter"></i> <?php echo _('apply to these segments only');?></a>
		    		
		    		 <script type="text/javascript">
		    			$("#time_condition_number").hide();
		    			$("#time_condition_intervals").hide();
		    		</script>
	    		</div>
	    		 
	    		<?php endif;?>
	    		
	    		<!-- Select segments -->
	    		<div id="segments-select" style="display:<?php echo have_segments($list_id) && (get_saved_data('segs')!='' || get_saved_data('segs_excl')!='') ? 'block' : 'none';?>">
		    		<!-- Segments multi select -->
		    		<div class="controls" style="float:left; width: 320px; margin-right: 20px;">
			    	  <p><span class="icon icon-plus-sign"></span> <?php echo _('Only send to these segments');?></p>
		              <select multiple="multiple" id="seg_list" name="seg_list[]" class="seg_select_multiselect_list">
							<?php if(have_segments($list_id)):?>
								<?php 
									$q = 'SELECT id, name FROM seg WHERE app = '.get_app_info('app').' AND list = '.$list_id.' ORDER BY id DESC';
									$r = mysqli_query($mysqli, $q);
									if ($r && mysqli_num_rows($r) > 0)
									{
									    while($row = mysqli_fetch_array($r))
									    {
									    	$seg_id = $row['id'];
											$seg_name = $row['name'];
											$list_selected = '';
											
											$q2 = 'SELECT segs FROM ares_emails WHERE id = '.$ae;
											$r2 = mysqli_query($mysqli, $q2);
											if ($r2)
											{
											    while($row = mysqli_fetch_array($r2))
											    {
													$segs = $row['segs'];
													$segs_array = explode(',', $segs);
													if(in_array($seg_id, $segs_array))
														$list_selected = 'selected';
											    }  
											}
											
											echo '<option value="'.$seg_id.'" id="seg_'.$seg_id.'" '.$list_selected.'>'.$seg_name.'</option>';
										}
									}
								?>
							<?php else:?>
								<option disabled><?php echo _('No segments found in this list');?></option>
							<?php endif;?>
		              </select>
		    		</div>
		              
		            <!-- Exclude segments multi select -->
		    		<div class="controls" style="float:left; width: 320px; ">
			    	  <p><span class="icon icon-minus-sign"></span> <?php echo _('Don\'t include emails in these segments');?></p>
		              <select multiple="multiple" id="seg_list_exclude" name="seg_list_exclude[]" class="seg_select_multiselect_list">
							<?php if(have_segments($list_id)):?>
								<?php 
									$q = 'SELECT id, name FROM seg WHERE app = '.get_app_info('app').' AND list = '.$list_id.' ORDER BY id DESC';
									$r = mysqli_query($mysqli, $q);
									if ($r && mysqli_num_rows($r) > 0)
									{
									    while($row = mysqli_fetch_array($r))
									    {
									    	$seg_id = $row['id'];
											$seg_name = $row['name'];
											$list_selected = '';
											
											$q2 = 'SELECT segs_excl FROM ares_emails WHERE id = '.$ae;
											$r2 = mysqli_query($mysqli, $q2);
											if ($r2)
											{
											    while($row = mysqli_fetch_array($r2))
											    {
													$segs = $row['segs_excl'];
													$segs_array = explode(',', $segs);
													if(in_array($seg_id, $segs_array))
														$list_selected = 'selected';
											    }  
											}
											
											echo '<option value="'.$seg_id.'" id="excl_seg_'.$seg_id.'" '.$list_selected.'>'.$seg_name.'</option>';
										}
									}
								?>
							<?php else:?>
								<option disabled><?php echo _('No segments found in this list');?></option>
							<?php endif;?>
		              </select><br/>
		            </div>
	    		</div>
	    		
	    		<script type="text/javascript">
		    		$(document).ready(function() {
		    			$("#apply-to-segment-btn").click(function(){
			    			$("#segments-select").toggle("slide");
		    			});
		    			
		    			//select list count
						$("select#seg_list_exclude, select#seg_list").change(function () {
						  
						  var inseg_array = [];
						  var exseg_array = [];
						  
						  var inlist_selected = [];	  
						  var exlist_selected = [];	  
						  
						  $("select#seg_list_exclude option").each(function(){
							  $("#excl_seg_"+$(this).val()).removeAttr("disabled");
						  });
						  $("select#seg_list :selected").each(function(i, selected){
							  inlist_selected[i] = $(selected).val(); 
							  inseg_array.push(inlist_selected[i]);
							  $("#excl_seg_"+inlist_selected[i]).attr("disabled", true);
							  $("#excl_seg_"+inlist_selected[i]).removeAttr("selected");
							  		  
						  });	  
						  $("select#seg_list_exclude :selected").each(function(i, selected){
							  exlist_selected[i] = $(selected).val(); 
							  exseg_array.push(exlist_selected[i]);
						  });
						  
					      if(inseg_array.length!=0) 
					      {
						      inlists_seg = inseg_array.join(",");
						      $("#in_list_seg").val(inlists_seg);
						  }
					      else 
					      {
						      inlists_seg = 0;
						      $("#in_list_seg").val("");
						  }
						  if(exseg_array.length!=0) 
						  {
							  exlists_seg = exseg_array.join(",");
							  $("#ex_list_seg").val(exlists_seg);
						  }
						  else 
						  {
							  exlists_seg = 0;
							  $("#ex_list_seg").val("");
						  }
					      
					    })
					    .trigger('change');
		    		});
	    		</script>
	    		<!-- / Select segments -->
    		</div>
    	</div>
    	
    	<div class="row-fluid">
		    <div class="span3">
			    
		    	<label class="control-label" for="subject"><?php echo _('Subject');?></label>
		    	<div class="control-group">
			    	<div class="controls">
		              <input type="text" class="input-xlarge" id="subject" name="subject" placeholder="<?php echo _('Subject of this email');?>" value="<?php echo get_saved_data('title');?>">
		            </div>
		        </div>
		        
		        <label class="control-label" for="from_name"><?php echo _('From name');?></label>
		    	<div class="control-group">
			    	<div class="controls">
		              <input type="text" class="input-xlarge" id="from_name" name="from_name" placeholder="<?php echo _('From name');?>" value="<?php echo get_saved_data('from_name');?>">
		            </div>
		        </div>
		        
		        <label class="control-label" for="from_email"><?php echo _('From email');?></label>
		    	<div class="control-group">
			    	<div class="controls">
		              <input type="text" class="input-xlarge" <?php if(get_app_info('is_sub_user')) echo 'readonly="readonly"';?> id="from_email" name="from_email" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_saved_data('from_email');?>">
		            </div>
		        </div>
		        
		        <label class="control-label" for="reply_to"><?php echo _('Reply to email');?></label>
		    	<div class="control-group">
			    	<div class="controls">
		              <input type="text" class="input-xlarge" id="reply_to" name="reply_to" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_saved_data('reply_to');?>">
		            </div>
		        </div>
		        
		        <label class="control-label" for="plain"><?php echo _('Plain text version');?></label>
	            <div class="control-group">
			    	<div class="controls">
		              <textarea class="input-xlarge" id="plain" name="plain" rows="10" placeholder="<?php echo _('Plain text version of this email');?>"><?php echo get_saved_data('plain_text');?></textarea>
		            </div>
		        </div>
		        
		        <label class="control-label" for="query_string"><?php echo _('Query string');?> <a href="javascript:void(0)" title="<?php echo _("Optionally append a query string to all links in your email newsletter. A good use case is Google Analytics tracking. Don't include '?' in your query string.");?>"><span class="icon icon-question-sign"></span></a></label>
		    	<div class="control-group">
			    	<div class="controls">
		              <input type="text" class="input-xlarge" id="query_string" name="query_string" placeholder="eg. utm_source=newsletter&utm_medium=sendy&utm_campaign=email_marketing" value="<?php echo get_saved_data('query_string');?>" style="width: 100%;">
		            </div>
		        </div><br/>
		        
		        <?php 
			        $allowed_attachments = get_app_data('allowed_attachments');
			        $allowed_attachments_array = array_map('trim', explode(',', $allowed_attachments));
			        $allowed_attachments_exts = implode(', ', $allowed_attachments_array);
			        if($allowed_attachments!=''):
			    ?>
		        <label class="control-label" for="attachments"><?php echo _('Attachments');?></label>
	            <div class="control-group">
			    	<div class="controls">
			    		<input type="file" id="attachments" name="attachments[]" multiple />
		            </div>
		            <p class="thirtytwo"><i><?php echo _('Allowed file types');?>: <?php echo $allowed_attachments_exts;?></i></p>
		        </div>
		        <?php endif;?>
		        
		        <?php 
			        if (file_exists('uploads/attachments/a'.$ae))
					{
						if($handle = opendir('uploads/attachments/a'.$ae))
						{
							$i = -1;
						    while (false !== ($file = readdir($handle))) 
						    {
						    	if($file!='.' && $file!='..'):
				    ?>
									<ul id="attachments">
										<li id="attachment<?php echo $i;?>" <?php echo get_app_info('dark_mode') ? 'style="color:black;"' : '';?>>
											<?php 
												$filen = $file;
												if(strlen($filen)>30) $filen = substr($file, 0, 30).'...';
												echo $filen;
											?> 
											(<?php echo round((filesize('uploads/attachments/a'.$ae.'/'.$file)/1000000), 2);?>MB) 
											<a href="<?php echo get_app_info('path');?>/includes/ares/delete-attachment.php" data-filename="<?php echo $file;?>" title="<?php echo _('Delete');?>" id="delete<?php echo $i;?>" <?php echo get_app_info('dark_mode') ? 'style="color:black;"' : '';?>><i class="icon icon-trash"></i></a>
											<script type="text/javascript">
												$("#delete<?php echo $i?>").click(function(e){
													e.preventDefault();
													filename = $(this).data("filename");
													ares_id = "<?php echo $ae?>";
													url = $(this).attr("href");
													c = confirm('<?php echo _('Confirm delete');?> \"'+filename+'\"?');
													
													if(c)
													{
														$.post(url, { filename: filename, ares_id: ares_id },
														  function(data) {
														      if(data)
														      {
														      	$("#attachment<?php echo $i?>").fadeOut();
														      }
														      else
														      {
														      	alert("<?php echo _('Sorry, unable to delete. Please try again later!');?>");
														      }
														  }
														);
													}
												});
											</script>
										</li>
									</ul>
					<?php
								endif;
								
								$i++;
						    }
						
						    closedir($handle);
						}
					}
		        ?>
		        <br/>	
		        
		        <p>
			        <?php echo _('Track opens');?>: 
			        <div class="btn-group tracking" data-toggle="buttons-radio">
					  <a href="javascript:void(0)" title="<?php echo _('Enable opens tracking');?>" class="btn" id="opens_tracking_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
						<a href="javascript:void(0)" title="<?php echo _('Disable opens tracking');?>" class="btn" id="opens_tracking_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						<a href="javascript:void(0)" title="<?php echo _('Track opens without identifying users to respect their privacy');?>" class="btn" id="opens_tracking_anon"><i class="icon icon-ok"></i> <?php echo _('Anonymously');?></a>
					</div>
					
					<script type="text/javascript">
						$(document).ready(function() {
							<?php 
								$opens_tracking = get_saved_data('opens_tracking');
								if($opens_tracking==1):
							?>
							$("#opens_tracking_on").button('toggle');
							$("#opens").val("1");
							<?php elseif($opens_tracking==0):?>
							$("#opens_tracking_off").button('toggle');
							$("#opens").val("0");
							<?php elseif($opens_tracking==2):?>
							$("#opens_tracking_anon").button('toggle');
							$("#opens").val("2");
							<?php endif;?>
							
							$("#opens_tracking_on").click(function(){
								$("#opens").val("1");
							});
							$("#opens_tracking_off").click(function(){
								$("#opens").val("0");
							});
							$("#opens_tracking_anon").click(function(){
								$("#opens").val("2");
							});
						});
					</script>
		        </p>
		        <br/>
		        <p>
			        <?php echo _('Track clicks');?>: 
			        <div class="btn-group tracking" data-toggle="buttons-radio">
					  <a href="javascript:void(0)" title="<?php echo _('Enable clicks tracking');?>" class="btn" id="clicks_tracking_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
						<a href="javascript:void(0)" title="<?php echo _('Disable clicks tracking');?>" class="btn" id="clicks_tracking_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						<a href="javascript:void(0)" title="<?php echo _('Track clicks without identifying users to respect their privacy');?>" class="btn" id="clicks_tracking_anon"><i class="icon icon-ok"></i> <?php echo _('Anonymously');?></a>
					</div>
					
					<script type="text/javascript">
						$(document).ready(function() {
							<?php 
								$links_tracking = get_saved_data('links_tracking');
								if($links_tracking==1):
							?>
							$("#clicks_tracking_on").button('toggle');
							$("#clicks").val("1");
							<?php elseif($links_tracking==0):?>
							$("#clicks_tracking_off").button('toggle');
							$("#clicks").val("0");
							<?php elseif($links_tracking==2):?>
							$("#clicks_tracking_anon").button('toggle');
							$("#clicks").val("2");
							<?php endif;?>
							
							$("#clicks_tracking_on").click(function(){
								$("#clicks").val("1");
							});
							$("#clicks_tracking_off").click(function(){
								$("#clicks").val("0");
							});
							$("#clicks_tracking_anon").click(function(){
								$("#clicks").val("2");
							});
						});
					</script>
		        </p>
		        <input type="hidden" name="opens" id="opens" value="">
		        <input type="hidden" name="clicks" id="clicks" value="">
		        <input type="hidden" name="in_list_seg" id="in_list_seg">
		        <input type="hidden" name="ex_list_seg" id="ex_list_seg">
		        
		        <br/><br/>
		        
		        <input type="hidden" name="ares_type" value="<?php echo get_ares_data('type');?>">
		        
		        <a href="javascript:void(0)" id="autoresponder-save-only-btn" class="btn"><i class="icon-ok icon-white"></i> <?php echo _('Save');?></a>
		        <button type="submit" class="btn btn-inverse"><i class="icon-ok icon-white"></i> <?php echo _('Save & exit');?></button>
		        
		        <br/><br/>
			        
		        <div class="control-group">
			    	<div>
				    	<div class="checkbox">
						  <label><input type="checkbox" name="save_as_template"><?php echo _('Also save as template?');?></label>
						</div>
			    	</div>
		        </div>
		        
		        <br/><br/>
		        <a href="<?php echo get_app_info('path');?>/autoresponders-emails?i=<?php echo get_app_info('app')?>&a=<?php echo get_ares_data('id');?>" title=""><i class="icon icon-chevron-left"></i> <?php echo _('Back to autoresponder email list');?></a>
		        
		    </div>   
		    <div class="span9">
			    <!-- Grammarly error -->
			    <?php 
				    $app_path_no_http_array = explode('/', get_app_info('path'));
				    $app_path_no_http = $app_path_no_http_array[2];
			    ?>
			    <div class="alert alert-error" id="grammarly-error" style="display:none;">
				  <button class="close" onclick="$('.alert-error').hide();">×</button>
				  <h3><span class="icon icon-warning-sign"></span> <?php echo _('Disable Grammarly extension in your browser');?></h3><br/>
				  <p><?php echo _('You have \'Grammarly\' extension installed in your browser. Grammarly injects thousands of lines of code into the email editor. As a result your email will have an extremely huge file size, look weird and you may not be able to save or send your campaign. To avoid all these, please do the following:');?></p>
				  <ol>
					  <li><?php echo _('Disable Grammarly extension in your browser (please see image below)');?></li>
					  <li><?php echo _('Then refresh this page');?></li>
				  </ol>
				  <p><img src="<?php echo get_app_info('path');?>/img/turn-off-grammarly.gif" title=""/></p>
				</div>
				<!-- Grammarly error -->
				
		    	<p>
			    	<label class="control-label" for="html"><?php echo _('HTML code');?></label>
			    	<div class="btn-group">
			    	<?php if(get_saved_data('wysiwyg')):?>
					  <button class="btn" id="toggle-wysiwyg"><?php echo _('Save and switch to HTML editor');?></button> <?php echo $html_code_msg;?>
					<?php else:?>
					  <button class="btn" id="toggle-wysiwyg"><?php echo _('Save and switch to WYSIWYG editor');?></button> <?php echo $html_code_msg;?>
					<?php endif;?>
					<script type="text/javascript">
						$("#toggle-wysiwyg").click(function(e){
							e.preventDefault();
							
							$('<input>').attr({
							    type: 'hidden',
							    id: 'w_clicked',
							    name: 'w_clicked',
							    value: '1',
							}).appendTo("#edit-form");
							
							$("#subject").rules("remove");
							$("#html").rules("remove");
							if($("#subject").val()=="") $("#subject").val("<?php echo _('Untitled');?>");
							
							$.post('<?php echo get_app_info('path');?>/includes/ares/toggle-wysiwyg.php', { toggle: $("#toggle-wysiwyg").text(), ae: "<?php echo $ae;?>" },
							  function(data) {
							      if(data)
							      {
							      	$("#edit-form").submit();
							      }
							      else
							      {
							      	alert("<?php echo _('Sorry, unable to toggle. Please try again later!');?>");
							      }
							  }
							);
						});
					</script>
					</div>
					<br/>
		            <div class="control-group">
				    	<div class="controls">
			              <textarea class="input-xlarge" id="html" name="html" rows="10" placeholder="<?php echo _('Email content');?>"><?php echo get_saved_data('html_text');?></textarea>
			            </div>
			        </div>
			    	<p><?php echo _('Use the following tags in your subject, plain text or HTML code and they\'ll automatically be formatted when your campaign is sent. For web version and unsubscribe tags, you can style them with inline CSS.');?></p><br/>
			    	<div class="row-fluid">
				    	<?php include('includes/helpers/personalization.tags.php');?>
			    	</div>
		    	</p>
		    </div> 
		</div>
		</form>
	</div>
</div>
<?php include('includes/footer.php');?>
