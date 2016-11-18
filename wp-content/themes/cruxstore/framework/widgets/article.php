<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Cruxstore_Posts widget class
 *
 * @since 1.0
 */
class Widget_Cruxstore_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_cruxstore_posts', 'description' => esc_html__( "Show posts of categories.",'cruxstore') );
        parent::__construct('cruxstore_posts', esc_html__('KT: Posts', 'cruxstore'), $widget_ops);
        $this->alt_option_name = 'widget_cruxstore_posts';
    }

    public function widget($args, $instance) {

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        $layout = ( ! empty( $instance['layout'] ) ) ? absint( $instance['layout'] ) : 5;

        if ( ! $number )
            $number = 5;

        if(!$layout){
            $layout = 1;
        }


        $args_article =  array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'order' => $instance['order'],
            'orderby' => $instance['orderby']
        );

        if(is_array($instance['category'])){
            $args_article['category__in'] = $instance['category'];
        }

        $r = new WP_Query( apply_filters( 'widget_posts_args', $args_article ) );

        if ($r->have_posts()) :

            echo $args['before_widget'];

            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            ?>
            <ul class="blog-posts blog-posts-widget layout-<?php echo $layout ?>">
                <?php
                    while ( $r->have_posts() ) : $r->the_post();
                        get_template_part( 'templates/blog/widget/content', $layout );
                    endwhile;
                ?>
            </ul>
            <?php
            echo $args['after_widget'];

            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;
    }

    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['layout'] = (int) $new_instance['layout'];
        $instance['category'] = isset( $new_instance['category'] ) ? $new_instance['category'] :  array();

        if ( in_array( $new_instance['orderby'], array( 'name', 'id', 'date', 'author', 'modified', 'rand', 'comment_count' ) ) ) {
            $instance['orderby'] = $new_instance['orderby'];
        } else {
            $instance['orderby'] = 'date';
        }

        if ( in_array( $new_instance['order'], array( 'DESC', 'ASC' ) ) ) {
            $instance['order'] = $new_instance['order'];
        } else {
            $instance['order'] = 'DESC';
        }

        return $instance;
    }


    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : esc_html__( 'Recent Posts' , 'cruxstore');
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;

        $order = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
        $orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
        $layout = isset( $instance['layout'] ) ? $instance['layout'] : '1';


        $category = isset( $instance['category'] ) ? $instance['category'] : array();

        $categories = get_terms( 'category', array('hide_empty' => false));

        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'cruxstore' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of posts to show:','cruxstore' ); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo esc_attr($number); ?>" class="widefat" /></p>

        <div><label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Categories:','cruxstore'); ?> </label>
            <select class="widefat categories-chosen" id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>[]" multiple="multiple">
                <?php foreach($categories as $item){ ?>
                    <option <?php if (in_array($item->term_id, $category)){ echo 'selected="selected"';} ?> value="<?php echo esc_attr($item->term_id) ?>"><?php echo esc_html($item->name); ?></option>
                <?php } ?>
            </select>
        </div>


        <p><label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'cruxstore'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <option <?php selected( $orderby, 'name' ); ?> value="name"><?php esc_html_e('Name','cruxstore'); ?></option>
                <option <?php selected( $orderby, 'id' ); ?> value="id"><?php esc_html_e('ID','cruxstore'); ?></option>
                <option <?php selected( $orderby, 'date' ); ?> value="date"><?php esc_html_e('Date','cruxstore'); ?></option>
                <option <?php selected( $orderby, 'author' ); ?> value="author"><?php esc_html_e('Author','cruxstore'); ?></option>
                <option <?php selected( $orderby, 'modified' ); ?> value="modified"><?php esc_html_e('Modified','cruxstore'); ?></option>
                <option <?php selected( $orderby, 'rand' ); ?> value="rand"><?php esc_html_e('Rand','cruxstore'); ?></option>
                <option <?php selected( $orderby, 'comment_count' ); ?> value="comment_count"><?php esc_html_e('Comment count','cruxstore'); ?></option>
            </select>
        </p>

        <p><label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:','cruxstore'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <option <?php selected( $order, 'DESC' ); ?> value="DESC"><?php esc_html_e('Desc','cruxstore'); ?></option>
                <option <?php selected( $order, 'ASC' ); ?> value="ASC"><?php esc_html_e('ASC','cruxstore'); ?></option>
            </select>
        </p>

        <p><label for="<?php echo esc_attr($this->get_field_id('layout')); ?>"><?php esc_html_e('Layout:','cruxstore'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <option <?php selected( $layout, '1' ); ?> value="1"><?php esc_html_e('Layout 1','cruxstore'); ?></option>
                <option <?php selected( $layout, '2' ); ?> value="2"><?php esc_html_e('Layout 2','cruxstore'); ?></option>
                <option <?php selected( $layout, '3' ); ?> value="3"><?php esc_html_e('Layout 3','cruxstore'); ?></option>
            </select>
        </p>

        <script type="text/javascript">
            (function($){
                $('document').ready(function() {
                    $('.categories-chosen').chosen();
                });
            })(jQuery);
        </script>
    <?php
    }
}

/**
 * Register CRUXSTORE_Posts widget
 *
 *
 */

register_widget('Widget_Cruxstore_Posts');
