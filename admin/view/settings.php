<h1 class="wp-spotlight-settings-main-title"><?php _e( 'WP Spotlight Search Settings',  WP_SPOTLIGHT_SEARCH_NAME); ?></h1>
<form target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_donations">
	<input type="hidden" name="business" value="rajkuppus@gmail.com">
	<input type="hidden" name="item_name" value="WP Spotlight Search">
	<input type="hidden" name="item_number" value="">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="amount" id="amount_f69666c6f8576f1dc315821705387e01" value="">
	<input type="hidden" name="no_note" value="1">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="lc" value="EN_US">
	<input type="hidden" name="bn" value="WPPlugin_SP">
	<input type="hidden" name="return" value="">
	<input type="hidden" name="cancel_return" value="">
	<input class="wpedon_paypalbuttonimage" type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make your payments with PayPal. It is free, secure, effective." style="border: none;"><img alt="" border="0" style="border:none;display:none;" src="https://www.paypal.com/EN_US/i/scr/pixel.gif" width="1" height="1">
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