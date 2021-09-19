<?php
function login_button_text()
{
    if (!is_user_logged_in()):
    if (get_option('button_text') == null):
        return __('Login', 'gym'); else:
        return get_option('button_text');
    endif; else:
    if (get_option('button_text2') == null):
        return __('Profile', 'gym'); else:
        return get_option('button_text2');
    endif;
    endif;
}

function default_buttons()
{
    if (get_option('default_buttons')==0) {
        return 'btn-primary';
    } elseif (get_option('default_buttons')==1) {
        return 'btn-info';
    } elseif (get_option('default_buttons')==2) {
        return 'btn-success';
    } elseif (get_option('default_buttons')==3) {
        return 'btn-warning';
    } elseif (get_option('default_buttons')==4) {
        return 'btn-danger';
    } elseif (get_option('default_buttons')==5) {
        return 'btn-inverse';
    }
}

function btn_block()
{
    return 'btn-block';
}

function default_sizes()
{
    if (get_option('default_sizes')==0) {
        return 'btn-large';
    } elseif (get_option('default_sizes')==1) {
        return 'btn-small';
    } elseif (get_option('default_sizes')==2) {
        return 'btn-mini';
    }
}

// Update User View
function gym_modal_update_user_view()
{
    if (is_user_logged_in() && is_single()) :
        
        global $post;
    $user_id = get_current_user_id();
    $posts = get_user_meta($user_id, 'gym_viewed_posts', true);
    if (!is_array($posts)) {
        $posts = array();
    }
    if (sizeof($posts)>4) {
        array_shift($posts);
    }
    if (!in_array($post->ID, $posts)) {
        $posts[] = $post->ID;
    }
    update_user_meta($user_id, 'gym_viewed_posts', $posts);
        
    endif;
}
add_action('wp_head', 'gym_modal_update_user_view');
