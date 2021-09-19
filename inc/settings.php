<?php
add_action('admin_menu', 'gym_modal_create_menu');

function gym_modal_create_menu()
{
    add_options_page(__('Modal Login', 'gym'), __('Modal Login', 'gym'), 'administrator', __FILE__, 'gym_modal_settings_page', __FILE__);
    add_action('admin_init', 'gym_modal_register_mysettings');
}


function gym_modal_register_mysettings()
{
    register_setting('gym-modal-settings-group', 'option_checkbox');
    register_setting('gym-modal-settings-group', 'option_bs3patch');
    register_setting('gym-modal-settings-group', 'option_usermodal');
    register_setting('gym-modal-settings-group', 'can_register_option');
    register_setting('gym-modal-settings-group', 'button_text');
    register_setting('gym-modal-settings-group', 'button_text2');
    register_setting('gym-modal-settings-group', 'default_buttons');
    register_setting('gym-modal-settings-group', 'default_sizes');
    register_setting('gym-modal-settings-group', 'gym_login_redirect');
    register_setting('gym-modal-settings-group', 'gym_register_redirect');
}

function gym_modal_settings_page()
{
    ?>
<div class="wrap">
<h2><?php _e('GyM Modal Login', 'gym'); ?></h2>

<form method="post" action="options.php">
    <?php settings_fields('gym-modal-settings-group'); ?>
    <?php do_settings_sections('gym-modal-settings-group'); ?>
    <table class="form-table">
		<p class="update-nag"><?php _e('Using <code>[GyM_Modal_Login]</code> shortcode, to display modal login.', 'gym'); ?></p>
		
        <tr valign="top">
        <th scope="row"><?php _e('Non-use BootStrap modules', 'gym'); ?></th>
        <td>
		<input name="option_checkbox" type="checkbox" value="1" <?php checked('1', get_option('option_checkbox')); ?> />
		<p class="description"><?php _e('If your theme support bootstrap, Check this option.', 'gym'); ?></p>
		</td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('Do you use from bootstrap +3?', 'gym'); ?></th>
        <td>
		<input name="option_bs3patch" type="checkbox" value="1" <?php checked('1', get_option('option_bs3patch')); ?> />
		<p class="description"><?php _e('If you use from bootstrap +3 in your theme, Check this option.', 'gym'); ?></p>
		</td>
        </tr>
		
		
        <tr valign="top">
        <th scope="row"><?php _e('Deactivate User Panel', 'gym'); ?></th>
        <td>
		<input name="option_usermodal" type="checkbox" value="1" <?php checked('1', get_option('option_usermodal')); ?> />
		<p class="description"><?php _e('This option disables user modal profile button.', 'gym'); ?></p>
		</td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('disable user registration?', 'gym'); ?></th>
        <td>
		<input name="can_register_option" type="checkbox" value="1" <?php checked('1', get_option('can_register_option')); ?> />
		<p class="description"><?php _e('This option disables user registration form.', 'gym'); ?></p>
		</td>
        </tr>	
		
        <tr valign="top">
        <th scope="row"><?php _e('Login Button Text', 'gym'); ?></th>
        <td><input type="text" name="button_text" value="<?php echo get_option('button_text'); ?>" /></td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('Profile Button Text', 'gym'); ?></th>
        <td><input type="text" name="button_text2" value="<?php echo get_option('button_text2'); ?>" /></td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('Default buttons', 'gym'); ?></th>
		<td>
		<fieldset>
		<label><input name="default_buttons" type="radio" value="0" <?php checked('0', get_option('default_buttons')); ?> />btn-primary</label><br />
		<label><input name="default_buttons" type="radio" value="1" <?php checked('1', get_option('default_buttons')); ?> />btn-info</label><br />
		<label><input name="default_buttons" type="radio" value="2" <?php checked('2', get_option('default_buttons')); ?> />btn-success</label><br />
		<label><input name="default_buttons" type="radio" value="3" <?php checked('3', get_option('default_buttons')); ?> />btn-warning</label><br />
		<label><input name="default_buttons" type="radio" value="4" <?php checked('4', get_option('default_buttons')); ?> />btn-danger</label><br />
		<label><input name="default_buttons" type="radio" value="5" <?php checked('5', get_option('default_buttons')); ?> />btn-inverse</label>
		<p class="description"><?php _e('choose your button style.', 'gym'); ?></p>
		</fieldset>
		</td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('Button sizes', 'gym'); ?></th>
		<td>
		<fieldset>
		<label><input name="default_sizes" type="radio" value="0" <?php checked('0', get_option('default_sizes')); ?> />btn-large</label><br />
		<label><input name="default_sizes" type="radio" value="1" <?php checked('1', get_option('default_sizes')); ?> />btn-small</label><br />
		<label><input name="default_sizes" type="radio" value="2" <?php checked('2', get_option('default_sizes')); ?> />btn-mini</label>
		<p class="description"><?php _e('choose your button size.', 'gym'); ?></p>
		</fieldset>
		</td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('login redirect URL', 'gym'); ?></th>
        <td><input type="text" name="gym_login_redirect" value="<?php echo get_option('gym_login_redirect'); ?>" /></td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php _e('Registration redirect URL', 'gym'); ?></th>
        <td><input type="text" name="gym_register_redirect" value="<?php echo get_option('gym_register_redirect'); ?>" /></td>
        </tr>
		
    </table>
    <?php submit_button(); ?>

</form>
</div>
<?php
} ?>