<h1 class="wp-spotlite-settings-main-title"><?php _e( 'WP Spotlight Search Settings',  WP_SPOTLITE_SEARCH_NAME); ?></h1>
<div class="wp-spotlite-settings-table" id="wp-spotlite-settings">
	<form action="admin.php?page=wp_spotlite_menu" method="post">
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Search options', WP_SPOTLITE_SEARCH_NAME ); ?></th>
				<td>
					<?php echo WP_Spotlite_Core::get_searchabel_post_types_checkbox();?>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
	</form>
</div>