<h1 class="wp-spotlight-settings-main-title"><?php _e( 'WP Spotlight Search Settings',  WP_SPOTLIGHT_SEARCH_NAME); ?></h1>
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