<?php
/**
 * Admin class
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\HARVESTAPPCLIENTPORTAL;

use SCALATER\HARVESTAPPCLIENTPORTAL\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Class Admin
 *
 * @package SCALATER\HARVESTAPPCLIENTPORTAL
 */
class Admin extends Base {
	use Singleton;

	/**
	 * Adding action hooks
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'create_setting_page' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
		add_action( 'show_user_profile', [ $this, 'extra_user_profile_fields' ] );
		add_action( 'edit_user_profile', [ $this, 'extra_user_profile_fields' ] );
		add_action( 'personal_options_update', [ $this, 'save_extra_user_profile_fields' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save_extra_user_profile_fields' ] );
	}

	public function save_extra_user_profile_fields( $user_id ) {
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
			return;
		}
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return;
        }
        if(!isset($_POST['harvest_client'])){
            return;
        }
        $harvest_client = intval(sanitize_text_field( $_POST['harvest_client'] ));

        update_user_meta( $user_id, 'harvest_client', $harvest_client);
    }

	public function extra_user_profile_fields( $user ) {
        ?>
        <h3><?php _e('Pick Harvest Client for this user', 'harvestapp-client-portal-wordpress'); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="harvest_client"><?php _e('Harvest Client', 'harvestapp-client-portal-wordpress'); ?></label></th>
                <td>
                    <select name="harvest_client" id="harvest_client">
                        <option value="">Select Client</option>
                        <?
                        $account_id = get_option( 'harvestapp_client_portal_wordpress_user_id' );
                        $personal_token = get_option( 'harvestapp_client_portal_wordpress_personal_token' );
                        $clients = array(array('id'=>0,'name'=>'No Client'));
                        if(!empty($account_id) && !empty($personal_token)){
                            $harvest = new Harvest($account_id, $personal_token);
	                        $clients = $harvest->getClients();
                        }
                        $harvest_client = get_the_author_meta( 'harvest_client', $user->ID );
                        foreach ($clients as $client) {
                            echo sprintf( "<option %s value=\"%s\">%s</option>", selected( $harvest_client, $client['id'], false ), $client['id'], $client['name'] );
                        }
                        ?>
                    </select>
                    <br />
                    <span class="description"><?php _e('Please pick the correct client from Harvest.', 'harvestapp-client-portal-wordpress'); ?></span>
                </td>
            </tr>
        </table>
        <?php
    }

	public function create_setting_page() {
		add_options_page( __( 'Harvets APP', 'harvestapp-client-portal-wordpress' ), __( 'Harvets APP', 'harvestapp-client-portal-wordpress' ), 'manage_options', $this->get_slug(), [ $this, 'harvestapp_client_portal_wordpress_page' ] );
	}

	public function harvestapp_client_portal_wordpress_page() {
		?>
		<div class="wrap">

			<div id="icon-options-general" class="icon32"><br></div>
			<h2> <?php _e( 'Harvest Client Portal', 'harvestapp-client-portal-wordpress' ); ?></h2>
			<div style="overflow: auto;">
				<span style="font-size: 13px; float:right;"><?php _e( 'Proudly brought to you by ', 'harvestapp-client-portal-wordpress' ); ?><a href="https://www.scalater.com/" target="_new">Scalater</a>.</span>
			</div>

			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'harvestapp_client_portal_wordpress_option' ); ?>
				<?php do_settings_sections( 'harvestapp_client_portal_wordpress_option' ); ?>
				<?php submit_button(); ?>
			</form>

		</div>
		<?php
	}

	public function settings_init() {
		add_settings_section( 'harvestapp_client_portal_wordpress_section', '', '', 'harvestapp_client_portal_wordpress_option' );

		add_settings_field( 'harvestapp_client_portal_wordpress_user_id', __( 'User Id', 'harvestapp-client-portal-wordpress' ), [ $this, 'harvestapp_client_portal_wordpress_user_id_cb' ], 'harvestapp_client_portal_wordpress_option', 'harvestapp_client_portal_wordpress_section' );
		add_settings_field( 'harvestapp_client_portal_wordpress_personal_token', __( 'Personal Access Token', 'harvestapp-client-portal-wordpress' ), [ $this, 'harvestapp_client_portal_wordpress_personal_token_cb' ], 'harvestapp_client_portal_wordpress_option', 'harvestapp_client_portal_wordpress_section' );

		register_setting( 'harvestapp_client_portal_wordpress_option', 'harvestapp_client_portal_wordpress_user_id' );
		register_setting( 'harvestapp_client_portal_wordpress_option', 'harvestapp_client_portal_wordpress_personal_token' );
	}

	public function harvestapp_client_portal_wordpress_user_id_cb() {
		$value = get_option( 'harvestapp_client_portal_wordpress_user_id' );
		?>
		<p>
			<input type="password" name="harvestapp_client_portal_wordpress_user_id" value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>" style="width: 350px;">
		</p>
		<?php
	}

	public function harvestapp_client_portal_wordpress_personal_token_cb() {
		$value = get_option( 'harvestapp_client_portal_wordpress_personal_token' );
		?>
		<p>
			<input type="password" name="harvestapp_client_portal_wordpress_personal_token" value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>" style="width: 350px;">
		</p>
		<?php
	}
}
