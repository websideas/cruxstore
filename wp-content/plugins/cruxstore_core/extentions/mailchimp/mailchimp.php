<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}



/**
 * Include Mailchim API.
 *
 */
if(!class_exists('MCAPI')){
    require_once ( CRUX_EXT_DIR . 'mailchimp/MCAPI.class.php' );
}


/**
 * Include Widget.
 *
 */
require_once ( CRUX_EXT_DIR . 'mailchimp/widget.php' );


/**
 * Include Mailchim Settings.
 *
 */
if(is_admin()){
    require_once ( CRUX_EXT_DIR . 'mailchimp/mailchimp_settings.php' );
}

class KT_MailChimp
{
    private $options;

    public function __construct() {

        $this->options = get_option( 'kt_mailchimp_option' );

        // Add ajax for frontend
        add_action( 'wp_ajax_frontend_mailchimp', array( $this, 'frontend_mailchimp_callback') );
        add_action( 'wp_ajax_nopriv_frontend_mailchimp', array( $this, 'frontend_mailchimp_callback') );

        // Add scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));

        if ( !$this->options['api_key'] ) {
            add_action( 'admin_notices', array( $this, 'admin_notice' ));
        }

        // Add shortcode mailchimp
        add_shortcode('kt_mailchimp', array($this, 'mailchimp_handler'));

    }

    public function admin_notice()
    {
        ?>
        <div class="updated">
            <p><?php
                printf(
                    __('Please enter Mail Chimp API Key in <a href="%s">here</a>', 'cruxstore_core' ),
                    admin_url( 'options-general.php?page=kt-mailchimp-settings')
                );
                ?></p>
        </div>
        <?php
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style( 'kt_mailchimp', CRUX_CORE_CSS . 'styles.css', array());
        wp_enqueue_script( 'kt_mailchimp', CRUX_CORE_JS . 'functions.js', array( 'jquery' ), null, true );
        wp_localize_script( 'kt_mailchimp', 'ajax_mailchimp', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ));
    }


    public function mailchimp_handler( $atts )
    {

        $atts = shortcode_atts( array(
            'title' => '',
            'list' => '',
            'opt_in' => '',
            'disable_names' => '',
            'text_before' => '',
            'layout' => '1',
            'el_class' => '',
            'placeholder' => esc_html__('Enter your email', 'cruxstore_core'),
            'css' => '',
        ), $atts );

        extract( $atts );

        if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {


            $this->uniqeID  = 'mailchimp-wrapper-'.uniqid();
            $this->atts = $atts;

            $elementClass = array(
                'base' => 'kt-mailchimp-wrapper ',
                'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
                'mailchimp-outer-'.$layout,
                'extra' => $el_class,
            );

            $output = '';

            $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
            $output .= '<div class="'.esc_attr($elementClass).'" id="'.esc_attr($this->uniqeID).'">';

            $output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'kt-mailchimp-heading' ) );

            if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {

                if(!$this->options['messages_successfully']){
                    $this->options['messages_successfully'] = __('Thank you, your sign-up request was successful! Please check your email inbox to confirm.', 'cruxstore_core');
                }

                $name = '';

                $output .= ($text_before) ? '<div class="mailchimp-before">'.$text_before.'</div>' : '';
                $button_class = ($layout == 2) ? 'btn-light' : 'btn-default';

                $email = '<input name="email" class="mailchimp-email" type="email" placeholder="'.$placeholder.' *"/>';
                $button = '<button data-loading="'.esc_attr(__('Loading', 'cruxstore_core')).'" data-text="'.esc_attr(__('Subscribe', 'cruxstore_core')).'"  class="btn mailchimp-submit '.$button_class.'" type="submit">'.__('Subscribe', 'cruxstore_core').'</button>';

                if($disable_names != 'yes'){
                    $name .= '<div class="mailchimp-input-fname"><input name="firstname" class="mailchimp-firstname" type="text" placeholder="'.__('First Name', 'cruxstore_core').'"/></div>';
                    $name .= '<div class="mailchimp-input-lname"><input name="lastname" class="mailchimp-lastname" type="text" placeholder="'.__('Last Name', 'cruxstore_core').'"/></div>';
                }

                if($layout == 2){
                    $text_mailchimp = '%1$s <div class="mailchimp-input-email">%2$s</div> <div class="mailchimp-input-button">%3$s</div>';
                }elseif($layout == 1 || $layout == 3 || $layout == 8 || $layout == 9){
                    $text_mailchimp = '%1$s <div class="mailchimp-email-button"><div class="mailchimp-input-email">%2$s</div> <div class="mailchimp-input-button">%3$s</div></div>';
                }else{
                    $text_mailchimp = '%1$s <div class="input-group">%2$s <div class="input-group-btn">%3$s</div></div>';
                }


                $output .= '<form class="mailchimp-form clearfix mailchimp-layout-'.esc_attr($layout).'" action="#" method="post">';
                $output .= sprintf( $text_mailchimp, $name, $email, $button );
                $output .= '<input type="hidden" name="action" value="signup"/>';
                $output .= '<input type="hidden" name="list_id" value="'.esc_attr($list).'"/>';
                $output .= '<input type="hidden" name="opt_in" value="'.esc_attr($opt_in).'"/>';
                $output .= '<div class="mailchimp-success">'.$this->options['messages_successfully'].'</div>';
                $output .= '<div class="mailchimp-error"></div>';
                $output .= '</form>';




            }else{
                $output .= sprintf(
                    "Please enter your mailchimp API key in <a href='%s'>here</a>",
                    admin_url( 'options-general.php?page=kt-mailchimp-settings')
                );
            }

            $output .= '</div><!-- .mailchimp-wrapper -->';

            return $output;
        }




    }


    /**
     * Mailchimp callback AJAX request
     *
     * @since 1.0
     * @return json
     */

    function frontend_mailchimp_callback() {
        

        $output = array( 'error'=> 1, 'msg' => '');
        $error = '';
        $merge_vars = array();

        $email = strip_tags($_POST['email']);
        $firstname = isset($_POST['firstname']) ? strip_tags($_POST['firstname']) : '';
        $lastname = isset($_POST['lastname']) ? strip_tags($_POST['lastname']) : '';

        if(strlen(trim($lastname)) <= 0) {
            $lastname = '';
        }

        $merge_vars['FNAME'] = $firstname;
        $merge_vars['LNAME'] = $lastname;

        if (!$email) {
            $error = __('Email address is required field.', 'cruxstore_core');
        }elseif(!is_email($email)){
            $error = __('Email address seems invalid.', 'cruxstore_core');
        }

        if($error){
            $output['msg'] = $error;
        }else{
            if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
                $mcapi = new MCAPI($this->options['api_key']);
                $opt_in = in_array($_POST['opt_in'], array('1', 'true', 'y', 'yes', 'on'));

                $mcapi->listSubscribe($_POST['list_id'], $email, $merge_vars, 'html', $opt_in);
                $output['mcapi'] = $mcapi;

                if($mcapi->errorCode) {
                    $output['msg'] = $mcapi->errorMessage;
                }else{
                    $output['error'] = 0;
                }
            }
        }


        echo json_encode($output);
        die();
    }



}



$kt_mailchimp = new KT_MailChimp();


