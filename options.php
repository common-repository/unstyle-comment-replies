<div class="wrap">
	<h2>Unstyle Comment Replies</h2>
	
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		
		<table class="form-table">
		
			<tr valign="top">
				<td>
					<strong>Classes to Remove</strong><br/>
					These are any classes that need to be removed from the existing code, one per line. You can generally leave these at the default.
				</td>
			</tr>
			<tr>
				<td><textarea name="kru_remove_zebra_classes" id="kru_remove_zebra_classes" style="width: 400px; height: 100px;"><?php echo get_option('kru_remove_zebra_classes'); ?></textarea></td>
			</tr>
			
			<tr valign="top">
				<td>
					<strong>Classes to Add</strong><br/>
					These are the classes that will be looped through and added in.  On each line, you can list as many classes as you want to add for that iteration, space-separated.
				</td>
			</tr>
			<tr>
				<td><textarea name="kru_add_zebra_classes" id="kru_add_zebra_classes" style="width: 400px; height: 100px;"><?php echo get_option('kru_add_zebra_classes'); ?></textarea></td>
			</tr>

		
		</table>
		
		<input type="hidden" name="action" value="update" />
		
		<input type="hidden" name="page_options" value="kru_remove_zebra_classes,kru_add_zebra_classes" />
		
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>