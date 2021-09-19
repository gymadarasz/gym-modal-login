<?php
// Creating the widget
class gym_widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'modal',
            __('Modal Login', 'gym'),
            array( 'description' => __('Modal Login is a WordPress plugin that is powered by bootstrap and ajax for better login, registration or lost password.', 'gym'))
        );
    }

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if (! empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo do_shortcode('[GyM_Modal_Login]');
        echo $args['after_widget'];
    }
            
    public function form($instance)
    {
        if (isset($instance[ 'title' ])) {
            $title = $instance[ 'title' ];
        } else {
            $title = __('Login', 'gym');
        } ?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<?php
    }
    
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

function gym_load_widget()
{
    register_widget('gym_widget');
}
add_action('widgets_init', 'gym_load_widget');
?>