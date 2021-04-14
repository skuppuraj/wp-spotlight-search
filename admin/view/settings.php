<div><h1 class="wp-spotlight-settings-main-title" style="display: inline-block;"><?php _e( 'WP Spotlight Search Settings',  WP_SPOTLIGHT_SEARCH_NAME); ?></h1><a href="https://wordpress.org/support/plugin/wp-spotlight-search/reviews/#new-post" style="color: #23282d;text-decoration: none;" target="_blank"><img src="<?php echo WP_SPOTLIGHT_SEARCH_URL?>/assets/images/rating.png" style="height: 25px;padding-left: 5px;"> Share your experience
</a>
</div>
	<h4>You can control all search functionalities here. You can search following items by ID, Title and Name.</h4>
<div class="wp-spotlight-settings-table" id="wp-spotlight-settings">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donation">
		<table class="form-table">
			<tbody>
				<tr>
					<th>Plugin Support:</th>
					<td>
						<div>
							<label><p>WP Spotlight Search is free to use - life is wonderful and lovely! It has required a great deal of time and effort to develop and you <br>can help support this development by <strong>making a small donation</strong>.&nbsp;You get useful software and I get to carry on making it better.</p></label>
							<br>
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="business" value="rajkuppus@gmail.com">
							<input type="hidden" name="item_name" value="WP Spotlight Search (WordPress Plugin)">
							<input type="hidden" name="buyer_credit_promo_code" value="">
							<input type="hidden" name="buyer_credit_product_category" value="">
							<input type="hidden" name="buyer_credit_shipping_method" value="">
							<input type="hidden" name="buyer_credit_user_address_change" value="">
							<input type="hidden" name="no_shipping" value="1">
							<input type="hidden" name="return" value="<?php echo admin_url("admin.php?page=wp_spotlight_menu"); ?>">
							<input type="hidden" name="no_note" value="1">
							<input type="hidden" name="currency_code" value="USD">
							<input type="hidden" name="tax" value="0">
							<input type="hidden" name="lc" value="US">
							<input type="hidden" name="bn" value="PP-DonationsBF">
							<div class="donation-amount">$<input type="number" name="amount" min="1" value="5">
								<span>
									<img draggable="false" class="emoji" alt="😀" src="https://s.w.org/images/core/emoji/11/svg/1f600.svg">
								</span>
								<input type="submit" class="button-primary" value="Support me 💰">
							</div>
							<div style="margin:10px;font-weight: 700">Contribute from $1 dollar</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<br><br>
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