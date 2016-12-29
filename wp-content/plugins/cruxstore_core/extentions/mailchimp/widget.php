<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Widget_KT_Mailchimp widget class
 *
 * @since 1.0
 */
class Widget_KT_Mailchimp extends WP_Widget {

    var $options;

    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_mailchimp', 'description' => __( "Subscribe to mailing list.", 'cruxstore_core') );
        parent::__construct('kt_mailchimp', __('KT: Mailchimp', 'cruxstore_core'), $widget_ops);
        $this->alt_option_name = 'widget_kt_mailchimp';

        $this->options = get_option( 'kt_mailchimp_option' );

    }


    public function widget($args, $instance) {
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        if($instance['list']){
            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
                echo do_shortcode('[kt_mailchimp text_before="'.$instance['text_before'].'" placeholder="'.$instance['placeholder'].'" layout="'.$instance['layout'].'" list="'.$instance['list'].'" opt_in="'.$instance['opt_in'].'" disable_names="'.$instance['disable_names'].'"]');

            }else{
                echo '<p>'.__("Please enter your mailchimp API key in setting page", 'cruxstore_core').'</p>';
            }

            echo $args['after_widget'];
        }
    }


    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['placeholder'] = strip_tags($new_instance['placeholder']);
        $instance['list'] = strip_tags($new_instance['list']);

        if ( current_user_can('unfiltered_html') ){
            $instance['text_before'] =  $new_instance['text_before'];
        }else{
            $instance['text_before'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text_before']) ) );
        }

        $instance['opt_in'] = isset( $new_instance['opt_in'] ) ? 'yes' : '';
        $instance['disable_names'] = isset( $new_instance['disable_names'] ) ? 'yes' : '';
        $instance['layout'] = (int) $new_instance['layout'];

        return $instance;
    }

    public function form( $instance ) {

        $defaults = array(
            'title' => __( 'Newsletter' , 'cruxstore_core'),
            'text_before' => '',
            'list' => '',
            'opt_in' => '',
            'disable_names' => '',
            'layout' => 1,
            'placeholder' => esc_html__('Enter your email', 'cruxstore_core')
        );

        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = strip_tags($instance['title']);
        $placeholder = strip_tags($instance['placeholder']);

        $lists_arr = array('' => __('Select option', 'cruxstore_core'));



        if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
            $mcapi = new MCAPI($this->options['api_key']);
            $lists = $mcapi->lists();
            if($lists['data']){
                foreach ($lists['data'] as $item) {
                    $lists_arr[$item['id']] = $item['name'];
                }
            }
        }
        else{ ?>
            <p class="description"><?php printf("Please enter your mailchimp API key in <a href='%s'>here</a>", admin_url( 'options-general.php?page=kt-mailchimp-settings')); ?></p>
        <?php } ?>

        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text_before' ); ?>"><?php _e( 'Text before:' ); ?></label>
            <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text_before'); ?>" name="<?php echo $this->get_field_name('text_before'); ?>"><?php echo $instance['text_before'] ?></textarea></p>

        <p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('Email Lists:','cruxstore_core'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>">
                <?php foreach($lists_arr as $key => $val){ ?>
                <option <?php selected( $instance['list'], $key ); ?> value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['opt_in'], 'yes' ); ?> id="<?php echo $this->get_field_id( 'opt_in' ); ?>" name="<?php echo $this->get_field_name( 'opt_in' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'opt_in' ); ?>"><?php _e( 'Double Opt In:', 'cruxstore_core' ); ?></label>
        <br><small><?php _e('Require that users confirm their subscriptions?', 'cruxstore_core') ?></small></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['disable_names'], 'yes' ); ?> id="<?php echo $this->get_field_id( 'disable_names' ); ?>" name="<?php echo $this->get_field_name( 'disable_names' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'disable_names' ); ?>"><?php _e( 'Disable Names:', 'cruxstore_core' ); ?></label>
            <br><small><?php _e('Disable the First and Last Name fields?', 'cruxstore_core') ?></small></p>

        <p><label for="<?php echo $this->get_field_id( 'placeholder' ); ?>"><?php _e( 'Placeholder:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" type="text" value="<?php echo $placeholder; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:','cruxstore_core'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option <?php selected( $instance['layout'], '1' ); ?> value="1"><?php _e('Layout 1','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '2' ); ?> value="2"><?php _e('Layout 2','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '3' ); ?> value="3"><?php _e('Layout 3','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '4' ); ?> value="4"><?php _e('Layout 4','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '5' ); ?> value="5"><?php _e('Layout 5','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '6' ); ?> value="6"><?php _e('Layout 6','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '7' ); ?> value="7"><?php _e('Layout 7','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '8' ); ?> value="8"><?php _e('Layout 8','cruxstore_core'); ?></option>
                <option <?php selected( $instance['layout'], '9' ); ?> value="9"><?php _e('Layout 9','cruxstore_core'); ?></option>
            </select>
        </p>
    <?php
    }
}

/**
 * Register CRUXSTORE_Facebook widget
 *
 *
 */

// register Foo_Widget widget
function register_KT_Mailchimp_widget() {
    register_widget( 'Widget_KT_Mailchimp' );
}
add_action( 'widgets_init', 'register_KT_Mailchimp_widget' );

