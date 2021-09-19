<?php
    global $current_user;
?>

<div id="gym_modal" class="modal fade" tabindex="-1" data-width="370" data-backdrop="static" data-keyboard="false" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&#215;</button>
    <h3><?php _e('Welcome Dear', 'gym'); ?> <?php get_currentuserinfo(); echo $current_user->user_login; ?></h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
		<div class="span4 thumbnail">
		<?php echo get_avatar($current_user->ID, '225'); ?>
		</div>
		<div class="span8">
		<span><?php _e('User Name:', 'gym'); ?> <?php get_currentuserinfo(); echo $current_user->user_login; ?></span><br />	
		<span><?php _e('User ID:', 'gym'); ?> <?php get_currentuserinfo(); echo $current_user->ID; ?></span><br />	
		<span><?php _e('Display Name:', 'gym'); ?> <?php get_currentuserinfo(); echo $current_user->display_name; ?></span><br />
		<span><?php _e('E-mail:', 'gym'); ?> <?php get_currentuserinfo(); echo $current_user->user_email; ?></span>
		</div>
	</div>
  </div>
  <div class="modal-footer" style="text-align:center;">
	<?php
    wp_loginout(home_url()); // Display "Log Out" link.
    echo " ~ ";
    wp_register('', ''); // Display "Site Admin" link.
    echo " ~ ";
    echo '<a href="'. get_edit_user_link() .'">'. __('Profile', 'gym') .'</a>'; //Display "Profile" link.
        ?>
  </div>
   <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>

</div>