<?php
namespace WP_SPOTLIGHT\Admin;
use WP_SPOTLIGHT\Controllers\SettingsController;

class Settings{
    private $settings_controller;

    public function __construct() {
        $this->settings_controller = new SettingsController();
    }
    public function get_settings_controller() {
		return $this->settings_controller;
	}

    public function wp_spotlight_menu_page(){
        ?>
			<div class="wp_spotlight_search_settings_page">
                <?php 
                    $this->setting_header();
                    $this->table_header();
                    $this->get_settings_controller()->the_nonce(); 
                    $this->build_options();
                    $this->table_footer();
                ?>
            </div>
        <?php
    }

    public function setting_header(){
        ?>
            <div>
                <h1 class="wp-spotlight-settings-main-title" style="display: inline-block;">
                    <?php _e( 'WP Spotlight Search Settings',  WP_SPOTLIGHT_SEARCH_SLUG); ?>
                </h1>
                <a href="https://wordpress.org/support/plugin/wp-spotlight-search/reviews/#new-post" target="_blank">
                    <img src="<?php echo WP_SPOTLIGHT_SEARCH_PLUGIN_URL?>/assets/images/rating.png" style="height: 25px;padding-left: 5px;">Share your experience
                </a>
            </div>
	        <h4>You can control all search functionalities here. You can search following items by ID, Title and Name.</h4>
        <?php
    }

    public function table_header(){
		?>  
            <form method="post" >
            <table class="form-table">
            <tbody>
		<?php
	}

    public function table_footer(){
		?>
            </tbody>
            
            </table>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </form>
		<?php
	}

    public function build_options(){
        ?>
            <tr>
				<th scope="row"><?php _e( 'Search options', WP_SPOTLIGHT_SEARCH_SLUG ); ?></th>
				</td>
			</tr>
            <?php $this->get_searchabel_post_types_checkbox();?>
        <?php
    }

    public function get_searchabel_post_types_checkbox(){
		$other_types[0] = array('type'=>'users', 'label' => 'Users');
		$other_types[1] = array('type'=>'comments', 'label' => 'Comments');
		$other_types[2] = array('type'=>'post_meta', 'label' => 'Search post by post meta (E.g: Advanced Custom Fields)');
	    $searchabel_post_type = $this->get_searchabel_post_types();
	    $response = '';
	    $searchabel_post_type = array_merge($searchabel_post_type, $other_types);
	    foreach ($searchabel_post_type as $key => $value) {
	        $type = $value['type'];
	        $label = $value['label'];
	        $selected = '';
	        if ($this->get_settings_controller()->get_setting($type) === "1") {
	            $selected = 'checked';
	        }

            ?>
            <tr>
                
                    <td>
                        <label class='wp-spotlight-settings-checkbox'> 
                            <input type='hidden' value='0' name='_wp_spotlight_search__setting[<?php esc_attr_e($type); ?>][string]'> 
                            <input type='checkbox' value='1' name='_wp_spotlight_search__setting[<?php esc_attr_e($type); ?>][string]' <?php echo $selected; ?> /> <?php echo esc_html($label); ?>
                        </label>
                </td>
            </tr>
            <?php
	    }

	}

    public function get_searchabel_post_types(){
	    $post_types = get_post_types('', 'object');
	    $searchabel_post_type = array();
	    foreach ($post_types as $key => $post) {
	        if ($key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
	            continue;
	        }
	        $post_tmep = array();
	        $post_tmep['type'] = $key;
	        $post_tmep['label'] = $post->label;
	        array_push($searchabel_post_type, $post_tmep);
	    }
	    return $searchabel_post_type;
	}
}
