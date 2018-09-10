<h1 class="wp-spotlight-settings-main-title"><?php _e( 'WP Spotlight Search Settings',  WP_SPOTLIGHT_SEARCH_NAME); ?></h1>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="MWVDKFWB7WMY2">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>

<div class="wp-spotlight-settings-table" id="wp-spotlight-settings">
	<form action="admin.php?page=wp_spotlight_menu" method="post">
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Search options', WP_SPOTLIGHT_SEARCH_NAME ); ?></th>
				<td>
					<?php echo WP_Spotlight_Core::get_searchabel_post_types_checkbox();?>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
	</form>
</div>