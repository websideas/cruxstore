<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * DELPHINUS_FadeIn widget class
 *
 * @since 1.0
 */
class Widget_DELPHINUS_FadeIn extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_delphinus_fadein', 'description' => esc_html__( "FadeIn text when hover.",'delphinus') );
        $control_ops = array( 'width' => 400, 'height' => 350 );
        parent::__construct('delphinus_fadein', esc_html__('KT: Text FadeIn', 'delphinus'), $widget_ops, $control_ops);
        $this->alt_option_name = 'widget_delphinus_fadein';

    }

    public function widget($args, $instance) {

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        $widget_text = ! empty( $instance['text'] ) ? $instance['text'] : '';

        /**
         * Filter the content of the Text widget.
         *
         * @since 2.3.0
         * @since 4.4.0 Added the `$this` parameter.
         *
         * @param string         $widget_text The widget content.
         * @param array          $instance    Array of settings for the current widget.
         * @param WP_Widget_Text $this        Current Text widget instance.
         */
        $text = apply_filters( 'widget_text', $widget_text, $instance, $this );

        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
        <div class="fadeinwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
        <?php
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        if ( current_user_can( 'unfiltered_html' ) ) {
            $instance['text'] = $new_instance['text'];
        } else {
            $instance['text'] = wp_kses_post( $new_instance['text'] );
        }
        $instance['filter'] = ! empty( $new_instance['filter'] );
        return $instance;
    }


    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
        $filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
        $title = sanitize_text_field( $instance['title'] );
        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'delphinus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e( 'Content:' , 'delphinus'); ?></label>
            <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>

        <p><input id="<?php echo esc_attr($this->get_field_id('filter')); ?>" name="<?php echo esc_attr($this->get_field_name('filter')); ?>" type="checkbox"<?php checked( $filter ); ?> />&nbsp;
            <label for="<?php echo esc_attr($this->get_field_id('filter')); ?>"><?php esc_html_e('Automatically add paragraphs', 'delphinus'); ?></label></p>
        <?php
    }
}

/**
 * Register DELPHINUS_FadeIn widget
 *
 *
 */

register_widget('Widget_DELPHINUS_FadeIn');
