<form id="login" action="login" method="post">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&#215;</button>
    <h3><?php _e('User Login', 'gym'); ?></h3>
  </div> 
  <div class="modal-body">
  	<div class="status"></div>
    <div class="row-fluid control-group">
		<label><?php __('Username', 'gym'); ?>
        <input type="text" name="username" id="username" class="span12" value="" <?php __('Username', 'gym'); ?>/></label>
		<label><?php  __('Password', 'gym'); ?>
        <input type="password" name="password" id="password" class="span12" value="" <?php __('Password', 'gym'); ?>/></label>
		<label class="checkbox">
		<input name="rememberme" type="checkbox" id="rememberme" value="forever"><?php _e('Remember Me', 'gym'); ?>
		</label>
		<?php if (get_option('can_register_option') != 1): ?>
		<span class="label label-important"><a href="#register_tab" data-toggle="tab"><?php _e('Not registered?', 'gym'); ?></a></span>
		<?php endif; ?>
		<span class="label label-info"><a href="#lostpass_tab" data-toggle="tab"><?php _e('Lost your password?', 'gym'); ?></a></span>	
    </div>
  </div>
  <div class="modal-footer">
        <button type="submit" name="submit" id="wp-submit" class="btn <?php echo default_buttons(); ?> <?php echo default_sizes(); ?> btn-block" data-loading-text="<?php _e('loading...', 'gym'); ?>"><?php _e('Submit', 'gym'); ?></button>
  </div>
   <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
</form>