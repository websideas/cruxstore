<?php
/**
 * KT MegaMenu Walker
 *
 * @author   KiteThemes
 * @package Kite/Template
 * @since: 1.0.0
 * @link	 http://kitethemes.com
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Custom Mega menu walker
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
class KTMegaWalker extends Walker_Nav_Menu{

    /**
     * @var string $megamenu_enable
     */
    private $megamenu_enable = "";

    /**
     * @var string $megamenu_width
     */
    private $megamenu_width = "";

    /**
     * @var string $megamenu_columns
     */
    private $megamenu_columns = "";

    /**
     * @var string $megamenu_position
     */
    private $megamenu_position = "";

    /**
     * @var string $megamenu_layout
     */
    private $megamenu_layout = "";



    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);


        if($this->megamenu_enable == "enabled" && $depth == 0){
            $output .= "\n$indent<ul class=\"cruxstore-megamenu-ul clearfix\">\n";
        } elseif($this->megamenu_enable == "enabled"){
            $output .= "\n$indent<ul class=\"sub-menu-megamenu\">\n";
        }else{
            $output .= "\n$indent<ul class=\"sub-menu-dropdown\">\n";
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }


    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        if( $depth === 0  && $this->megamenu_enable == "enabled" ) {
            $output .= "</div>\n";
        }
        $output .= "</li>\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if( $depth === 0 ) {
            $this->megamenu_enable = get_post_meta( $item->ID, '_menu_item_megamenu_enable', true );
            
            if($this->megamenu_enable){
                $this->megamenu_width = get_post_meta( $item->ID, '_menu_item_megamenu_width', true );
                $this->megamenu_columns = get_post_meta( $item->ID, '_menu_item_megamenu_columns', true );
                $this->megamenu_position = get_post_meta( $item->ID, '_menu_item_megamenu_position', true );
                $this->megamenu_layout = get_post_meta( $item->ID, '_menu_item_megamenu_layout', true );
                
                if(!$this->megamenu_width) $this->megamenu_width = 'full';
                if(!$this->megamenu_columns) $this->megamenu_columns = 4;
                if(!$this->megamenu_position) $this->megamenu_position = 'center';
                if(!$this->megamenu_layout) $this->megamenu_layout = 'default';
            }
        }

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'menu-item-level-'.$depth;
        $classes[] = 'cruxstore-menu-item';


        if( $depth === 0 && $this->megamenu_enable) {
            $classes[] = 'cruxstore-megamenu-item';
            $classes[] = 'cruxstore-megamenu-item-'.$this->megamenu_width;
        }
        $endrow = get_post_meta( $item->ID, '_menu_item_megamenu_endrow', true );
        if($depth == 1 && $endrow){
            $classes[] = 'cruxstore-megamenu-item-endrow';
        }

        /**
         * Filter the CSS class(es) applied to a menu item's <li>.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param array  $classes The CSS classes that are applied to the menu item's <li>.
         * @param object $item    The current menu item.
         * @param array  $args    An array of wp_nav_menu() arguments.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filter the ID applied to a menu item's <li>.
         *
         * @since 3.0.1
         *
         * @see wp_nav_menu()
         *
         * @param string $menu_id The ID that is applied to the menu item's <li>.
         * @param object $item    The current menu item.
         * @param array  $args    An array of wp_nav_menu() arguments.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $style = '';
        $clwidth = get_post_meta( $item->ID, '_menu_item_megamenu_clwidth', true );
        if($depth == 1 && $clwidth){
            $style = 'width: '.$clwidth;
        }



        $output .= $indent . '<li' . $id . $class_names .' style="'.$style.'">';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        $atts['class']   = 'cruxstore-megamenu-link';

        /**
         * Filter the HTML attributes applied to a menu item's <a>.
         *
         * @since 3.6.0
         *
         * @see wp_nav_menu()
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item The current menu item.
         * @param array  $args An array of wp_nav_menu() arguments.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        if(is_array($args)){
            $args = (object) $args;
        }


        $item_output = '';

        $item_output .= $args->before;

        $megamenu_columnlink = ($this->megamenu_enable && $depth == 1) ? get_post_meta( $item->ID, '_menu_item_megamenu_columnlink', true ) : false;

        if(!$megamenu_columnlink || $depth != 1){
            $item_output .= '<a'. $attributes .'>';
        }else{
            $item_output .= '<span class="megamenu-title">';
        }
        if(($this->megamenu_enable && $depth == 1)){
            $image = get_post_meta( $item->ID, '_menu_item_megamenu_image', true );
            if($image){
                $file = cruxstore_get_thumbnail_attachment($image, 'full');
                if($file['src']){
                    $item_output .= sprintf('<img src="%s" alt="%s" title="%s" class="img-responsive"/>', $file['src'], $file['alt'], $file['title']);
                }
            }
        }

        $icon = get_post_meta( $item->ID, '_menu_item_megamenu_icon', true );
        $icon = ($icon) ? '<i class="icon-menu '.$icon.'"></i>' : '';

        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before .$icon . apply_filters( 'the_title', $item->title, $item->ID )  . $args->link_after;

        if(!$megamenu_columnlink || !$depth = 1){
            $item_output .= '</a>';
        }else{
            $item_output .= '</span>';
        }

        $item_output .= $args->after;


        if( $depth === 0 && $this->megamenu_enable == "enabled" ) {
            $colums = ($this->megamenu_columns) ? $this->megamenu_columns : 'auto';
            $position = ($this->megamenu_width != 'full') ? ' megamenu-position-'.$this->megamenu_position : '';
            $layout = ' megamenu-layout-'.$this->megamenu_layout;

            $item_output .= "\n$indent<div class=\"cruxstore-megamenu-wrapper $position $layout megamenu-columns-$colums \">\n";

            if ( $depth == 0 && $item->object == 'category' ) {

                $item_output .= '<div class="megamenu-posts">';

                global $post;
                $menu_posts = get_posts( apply_filters('cruxstore_megamenu_posts', array( 'posts_per_page' => 3, 'category' => $item->object_id )) );

                ob_start();
                foreach( $menu_posts as $post ): ?>
                    <div class="col-lg-4 col-md-4">
                        <?php get_template_part( 'templates/blog/grid/contento', get_post_format()); ?>
                    </div>
                    <?php
                endforeach;
                wp_reset_postdata();

                $item_output .= sprintf('<div class="blog-posts-menu"><div class="row">%s</div></div>', ob_get_clean());

                $item_output .= '</div>';

            }


        }


        $megamenu_columntitle = get_post_meta( $item->ID, '_menu_item_megamenu_columntitle', true );
        if($megamenu_columntitle && $depth ==1 )
            $item_output = '';

        $megamenu_widget = ($this->megamenu_enable && $depth == 1) ? get_post_meta( $item->ID, '_menu_item_megamenu_widget', true ) : false;


        if($megamenu_widget && $depth ==1 ){
            ob_start();
            dynamic_sidebar( $megamenu_widget );
            $item_output .= '<div class="cruxstore-megamenu-widget">'.ob_get_contents().'</div>';
            ob_end_clean();
        }

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes $args->before, the opening <a>,
         * the menu item's title, the closing </a>, and $args->after. Currently, there is
         * no filter for modifying the opening and closing <li> for a menu item.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of wp_nav_menu() arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}