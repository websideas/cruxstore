<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CRUXSTORE_Posts widget class
 *
 * @since 1.0
 */
class Widget_CruxStore_Posts_Carousel extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_cruxstore_posts_carousel', 'description' => esc_html__( "Show posts of categories.",'cruxstore') );
        parent::__construct('cruxstore_posts_carousel', esc_html__('KT: Posts Carousel', 'cruxstore'), $widget_ops);
        $this->alt_option_name = 'widget_cruxstore_posts_carousel';

    }

    public function widget($args, $instance) {

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }


        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;

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

            ob_start();
            $carousel_html ='';

            ?>
            <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                <div <?php post_class(); ?>>
                    <?php cruxstore_post_thumbnail_image( 'cruxstore_square', 'img-responsive', false ); ?>
                    <div class="post-carousel-content">
                        <?php cruxstore_post_meta_categories(); ?>
                        <h4><?php get_the_title() ? the_title() : the_ID(); ?></h4>
                    </div>

                    <a href="<?php the_permalink(); ?>" class="post-carousel-link"></a>
                </div>
            <?php endwhile; ?>
            <?php
            wp_reset_postdata();

            $carousel_html .= ob_get_clean();
            if($carousel_html){
                $atts = array(
                    'desktop' => 1,
                    'tablet' => 1,
                    'mobile' => 1,
                    'navigation_always_on' => false,
                    'navigation_position' => 'center',
                    'carousel_skin' => 'white',
                    'gutters' => false
                );
                $carousel_ouput = cruxstore_render_carousel(apply_filters( 'cruxstore_render_args', $atts), '', 'cruxstore-owl-carousel');

                echo str_replace('%carousel_html%', $carousel_html, $carousel_ouput);

            }


            echo $args['after_widget'];



        endif;

    }

    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];

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
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;

        $order = isset( $instance['order'] ) ? $instance['order'] : 'DESC';
        $orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';

        $category = isset( $instance['category'] ) ? $instance['category'] : array();

        $categories = get_terms( 'category', array('hide_empty' => false));

        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:','cruxstore' ); ?></label>
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
 * Register CruxStore_Posts_Carousel widget
 *
 *
 */

register_widget('Widget_CruxStore_Posts_Carousel');