<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

class CruxStore_Shortcodes
{

    public function __construct() {


        add_shortcode('cruxstore_dropcaps', array($this, 'cruxstore_dropcaps'));
        add_shortcode('cruxstore_tooltip', array($this, 'cruxstore_tooltip'));
        add_shortcode('cruxstore_highlight', array($this, 'cruxstore_highlight'));

    }

    public function cruxstore_dropcaps( $atts, $content )
    {
        //normal, round, circle, square
        $atts = shortcode_atts( array(
            'size' => 'md',
            'background' => '',
            'text' => '#ed8b5c',
            'shapes' => 'normal'
        ), $atts );

        extract( $atts );


        if(!$content){
            return;
        }

        $elementClass = array(
            'class' => 'cruxstore_dropcap',
            'size' => 'dropcap-'.$size,
            'shapes' => 'dropcap-'.$shapes,
        );


        $style_title = '';


        if($background){
            $styles[] = 'background: '.$background;
        }

        if($text){
            $styles[] = 'color: '.$text;
        }

        if ( ! empty( $styles ) ) {
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<span class="'.$elementClass.'" '.$style_title.'>'.$content.'</span>';
    }


    function cruxstore_tooltip( $atts ){
        $atts = shortcode_atts( array(
            'tooltip_text' => esc_html__('Tooltip Text', 'cruxstore'),
            'text' => esc_html__('Text', 'cruxstore'),
            'href' => '#',
        ), $atts );
        extract( $atts );

        $elementClass = array(
            'class' => 'cruxstore_tooltip',
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return sprintf('<a href="%s" data-toggle="tooltip" title="%s" class="%s">%s</a>', esc_url($href), esc_attr($tooltip_text), $elementClass, $text);
    }


    function cruxstore_highlight( $atts ){
        $atts = shortcode_atts( array(
            'background' => '',
            'text_color' => 'white',
            'text' => esc_html__('Text', 'cruxstore')
        ), $atts );
        extract( $atts );

        $elementClass = array(
            'class' => 'cruxstore_highlight'
        );

        $style_title = '';


        if($background){
            $styles[] = 'background: '.$background;
        }

        if($text_color){
            $styles[] = 'color: '.$text_color;
        }

        if ( ! empty( $styles ) ) {
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<span class="'.$elementClass.'" '.$style_title.'>'.$text.'</span>';
    }

}

$cruxstore_shortcodes = new CruxStore_Shortcodes();