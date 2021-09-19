<?php
function gym_Modal_login_shortcode()
{
    add_action('wp_footer', 'gym_modal_form');
    if (!is_user_logged_in()) :
    return '
		<button type="button" class="btn '.default_buttons().' '.btn_block().' '.default_sizes().'" data-toggle="modal" data-target="#gym_modal">'.login_button_text().'</button>
	'; else:
    if (get_option('option_usermodal') != 1):
    return '
		<button type="button" class="btn '.default_buttons().' '.btn_block().' '.default_sizes().'" data-toggle="modal" data-target="#gym_modal">'. login_button_text() .'</button>
	'; else:
    return '
		<button type="button" class="btn '.default_buttons().' '.btn_block().' '.default_sizes().' disabled" disabled="disabled">'. login_button_text() .'</button>
	';
    endif;
    endif;
}
add_shortcode('GyM_Modal_Login', 'gym_Modal_login_shortcode');
