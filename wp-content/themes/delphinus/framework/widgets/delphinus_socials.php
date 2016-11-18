<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT Socials widget class
 *
 * @since 1.0
 */
class WP_Widget_DELPHINUS_Socials extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_delphinus_socials', 'description' => esc_html__( 'Socials for widget.', 'delphinus' ) );
		parent::__construct('delphinus_socials', esc_html__('KT: Socials', 'delphinus' ), $widget_ops);
	}

	public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        
        $value = isset( $instance['value'] ) ? $instance['value'] : '';
        $style = isset( $instance['style'] ) ? $instance['style'] : 'dark';
        $size = isset( $instance['size'] ) ? $instance['size'] : 'standard';
        $tooltip = isset( $instance['tooltip'] ) ? $instance['tooltip'] : '';
        $background_style = isset( $instance['background_style'] ) ? $instance['background_style'] : 'empty';
        $align = isset( $instance['align'] ) ? $instance['align'] : '';


        $space_between_item    = isset( $instance['space_between_item'] ) ? absint( $instance['space_between_item'] ) : 3;
        $custom_color    = isset( $instance['custom_color'] ) ? $instance['custom_color'] : '#999999';
        
		echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }   
            echo do_shortcode('[socials social="'.$value.'" align="'.$align.'" tooltip="'.$tooltip.'" space_between_item="'.$space_between_item.'" size="'.$size.'" style="'.$style.'" custom_color="'.$custom_color.'" background_style="'.$background_style.'"]');
            
		echo $args['after_widget'];
	}
	

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        
        $instance['value'] = $new_instance['value'];
        if ( in_array( $new_instance['style'], array( 'dark', 'light', 'color', 'custom' ) ) ) {
            $instance['style'] = $new_instance['style'];
        } else {
            $instance['style'] = 'dark';
        }
        if ( in_array( $new_instance['background_style'], array( 'empty', 'rounded', 'boxed', 'rounded-less', 'diamond-square', 'rounded-outline', 'boxed-outline', 'rounded-less-outline', 'diamond-square-outline' ) ) ) {
            $instance['background_style'] = $new_instance['background_style'];
        } else {
            $instance['background_style'] = 'empty';
        }
        if ( in_array( $new_instance['size'], array( 'standard', 'small' ) ) ) {
            $instance['size'] = $new_instance['size'];
        } else {
            $instance['size'] = '';
        }
        if ( in_array( $new_instance['tooltip'], array( '', 'top', 'right', 'bottom', 'left' ) ) ) {
            $instance['tooltip'] = $new_instance['tooltip'];
        } else {
            $instance['tooltip'] = '';
        }
        if ( in_array( $new_instance['align'], array( '', 'center', 'right', 'left' ) ) ) {
            $instance['align'] = $new_instance['align'];
        } else {
            $instance['align'] = '';
        }
        $instance['space_between_item'] = (int) $new_instance['space_between_item'];
        $instance['custom_color'] = $new_instance['custom_color'];
        
		return $instance;
	}

	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__('Socials', 'delphinus'), 'target' => '_self', 'value' => '', 'style' => 'dark', 'background_style' => '', 'size' => 'standard', 'tooltip' => '', 'align' => '', 'space_between_item' => 3, 'custom_color' => '#999999' ) );
        $title = strip_tags($instance['title']);
        
        $value = isset( $instance['value'] ) ? $instance['value'] : '';
        $style = isset( $instance['style'] ) ? $instance['style'] : 'dark';
        $background_style = isset( $instance['background_style'] ) ? $instance['background_style'] : '';
        $size = isset( $instance['size'] ) ? $instance['size'] : 'standard';
        $tooltip = isset( $instance['tooltip'] ) ? $instance['tooltip'] : '';
        $align = isset( $instance['align'] ) ? $instance['align'] : '';
        $space_between_item    = isset( $instance['space_between_item'] ) ? absint( $instance['space_between_item'] ) : 3;
        $custom_color    = isset( $instance['custom_color'] ) ? $instance['custom_color'] : '#999999';
	?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'delphinus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
        <?php
            $socials = array(
                'facebook' => 'fa fa-facebook',
                'twitter' => 'fa fa-twitter',
                'pinterest' => 'fa fa-pinterest-p',
                'dribbble' => 'fa fa-dribbble',
                'vimeo' => 'fa fa-vimeo-square',
                'tumblr' => 'fa fa-tumblr',
                'skype' => 'fa fa-skype',
                'linkedin' => 'fa fa-linkedin',
                'googleplus' => 'fa fa-google-plus',
                'youtube' => 'fa fa-youtube-play',
                'instagram' => 'fa fa-instagram'
            );
            
            $arr_val = ($value) ? explode(',', $value) : array();
        ?>
    
        <div class="delphinus-socials-options">
            <ul class="delphinus-socials-lists clearfix">
                <?php foreach($socials as $key => $social){ ?>
                    <?php $class = (in_array($key, $arr_val)) ? 'selected' : ''; ?>
                    <li data-type="<?php echo esc_attr($key); ?>" class="<?php echo esc_attr($class); ?>"><i class="<?php echo esc_attr($social); ?>"></i><span></span></li>
                <?php } ?>
            </ul><!-- .delphinus-socials-lists -->
            <ul class="delphinus-socials-profiles clearfix">
            <?php
                if(count($arr_val)){
                    foreach($arr_val as $item){ ?>
                        <li data-type="<?php echo esc_attr($item); ?>"><i class="<?php echo esc_attr($socials[$item]); ?>"></i><span></span></li>
                    <?php }
                }
            ?>
            </ul><!-- .delphinus-socials-profiles -->
            <input id="<?php echo esc_attr($this->get_field_id( 'value' )); ?>" type="hidden" class="wpb_vc_param_value delphinus-socials-value" name="<?php echo esc_attr($this->get_field_name( 'value' )); ?>" value="<?php echo esc_attr($value); ?>" />
        </div><!-- .delphinus-socials-options -->
        <small><?php esc_attr_e( 'Empty for select all, Drop and sortable social','delphinus' ); ?></small>
        <?php wp_enqueue_script( 'socials_js', DELPHINUS_FW_JS.'delphinus_socials.js', array('jquery'), DELPHINUS_FW_VER, true); ?>
        
        <p><label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Style:', 'delphinus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option <?php selected( $style, 'dark' ); ?> value="dark"><?php esc_attr_e('Dark','delphinus'); ?></option>
                <option <?php selected( $style, 'light' ); ?> value="light"><?php esc_attr_e('Light','delphinus'); ?></option>
                <option <?php selected( $style, 'color' ); ?> value="color"><?php esc_attr_e('Color','delphinus'); ?></option>
                <option <?php selected( $style, 'custom' ); ?> value="custom"><?php esc_attr_e('Custom Color','delphinus'); ?></option>
            </select>
        </p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'custom_color' )); ?>"><?php esc_attr_e( 'Custom Color:', 'delphinus' ); ?></label><br/>
            <input class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'custom_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'custom_color' )); ?>" type="text" value="<?php echo esc_attr($custom_color); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id('background_style')); ?>"><?php esc_attr_e('Background Style:', 'delphinus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('background_style')); ?>" name="<?php echo esc_attr($this->get_field_name('background_style')); ?>">
                <option <?php selected( $background_style, 'empty' ); ?> value=""><?php esc_attr_e('None','delphinus'); ?></option>
                <option <?php selected( $background_style, 'rounded' ); ?> value="rounded"><?php esc_attr_e('Circle','delphinus'); ?></option>
                <option <?php selected( $background_style, 'boxed' ); ?> value="boxed"><?php esc_attr_e('Square','delphinus'); ?></option>
                <option <?php selected( $background_style, 'rounded-less' ); ?> value="rounded-less"><?php esc_attr_e('Rounded','delphinus'); ?></option>
                <option <?php selected( $background_style, 'diamond-square' ); ?> value="diamond-square"><?php esc_attr_e('Diamond Square','delphinus'); ?></option>
                <option <?php selected( $background_style, 'rounded-outline' ); ?> value="rounded-outline"><?php esc_attr_e('Outline Circle','delphinus'); ?></option>
                <option <?php selected( $background_style, 'boxed-outline' ); ?> value="boxed-outline"><?php esc_html_e('Outline Square','delphinus'); ?></option>
                <option <?php selected( $background_style, 'rounded-less-outline' ); ?> value="rounded-less-outline"><?php esc_attr_e('Outline Rounded','delphinus'); ?></option>
                <option <?php selected( $background_style, 'diamond-square-outline' ); ?> value="diamond-square-outline"><?php esc_attr_e('Outline Diamond Square','delphinus'); ?></option>
            </select>
            <small><?php esc_html_e('Select background shape and style for social.','delphinus'); ?></small>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id('size')); ?>"><?php esc_html_e('Size:', 'delphinus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('size')); ?>" name="<?php echo esc_attr($this->get_field_name('size')); ?>">
                <option <?php selected( $size, 'standard' ); ?> value="standard"><?php esc_html_e('Standard','delphinus'); ?></option>
                <option <?php selected( $size, 'small' ); ?> value="small"><?php esc_html_e('Small','delphinus'); ?></option>
            </select>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id('tooltip')); ?>"><?php esc_html_e('Tooltip:', 'delphinus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('tooltip')); ?>" name="<?php echo esc_attr($this->get_field_name('tooltip')); ?>">
                <option <?php selected( $tooltip, '' ); ?> value=""><?php esc_html_e('None','delphinus'); ?></option>
                <option <?php selected( $tooltip, 'top' ); ?> value="top"><?php esc_html_e('Top','delphinus'); ?></option>
                <option <?php selected( $tooltip, 'right' ); ?> value="right"><?php esc_html_e('Right','delphinus'); ?></option>
                <option <?php selected( $tooltip, 'bottom' ); ?> value="bottom"><?php esc_html_e('Bottom','delphinus'); ?></option>
                <option <?php selected( $tooltip, 'left' ); ?> value="left"><?php esc_html_e('Left','delphinus'); ?></option>
            </select>
            <small><?php esc_html_e('Select the tooltip position','delphinus'); ?></small>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id('align')); ?>"><?php esc_html_e('Align:', 'delphinus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('align')); ?>" name="<?php echo esc_attr($this->get_field_name('align')); ?>">
                <option <?php selected( $align, '' ); ?> value=""><?php esc_html_e('None','delphinus'); ?></option>
                <option <?php selected( $align, 'center' ); ?> value="center"><?php esc_html_e('Center','delphinus'); ?></option>
                <option <?php selected( $align, 'left' ); ?> value="left"><?php esc_html_e('Left','delphinus'); ?></option>
                <option <?php selected( $align, 'right' ); ?> value="right"><?php esc_html_e('Right','delphinus'); ?></option>
            </select>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'space_between_item' )); ?>"><?php esc_html_e( 'Space Between item:', 'delphinus' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'space_between_item' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'space_between_item' )); ?>" type="text" value="<?php echo esc_attr($space_between_item); ?>" /></p>
        <script type="text/javascript">
            (function($){
                $('document').ready(function() {
                    $( ".delphinus-socials-profiles" ).sortable({
                        placeholder: "ui-socials-highlight",
                        update: function( event, ui ) {
                            var $parrent_ui = ui.item.closest('.delphinus-socials-options'),
                                $profiles_ui = $parrent_ui.find('.delphinus-socials-profiles'),
                                $value_ui = $parrent_ui.find('.delphinus-socials-value');

                            $profiles_val_ui = [];
                            $profiles_ui.find('li').each(function(){
                                $profiles_val_ui.push($(this).data('type'));
                            });
                            $value_ui.val($profiles_val_ui.join());
                        }
                    });
                });
            })(jQuery);
        </script>
<?php
	}

}


register_widget( 'WP_Widget_DELPHINUS_Socials' );