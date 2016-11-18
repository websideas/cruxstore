<?php
/**
 * Handles taxonomies in admin
 *
 * @class    CruxStore_Admin_Attributes
 * @version  2.3.10
 * @package  WooCommerce/Admin
 * @category Class
 * @author   WooThemes
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WC_Admin_Taxonomies class.
 */
class CruxStore_Admin_Attributes {

    /**
     * Constructor.
     */
    public function __construct() {
        // Category/term ordering
        add_action( 'create_term', array( $this, 'create_term' ), 5, 3 );
        add_action( 'delete_term', array( $this, 'delete_term' ), 5 );

        add_action( 'admin_enqueue_scripts',array($this, 'wp_enqueue_color_picker') );

        $taxonomy_names = wc_get_attribute_taxonomy_names();
        if(count($taxonomy_names)){
            foreach($taxonomy_names as $taxonomy){
                // Add form
                add_action( $taxonomy.'_add_form_fields', array( $this, 'add_attribute_fields' ) );
                add_action( $taxonomy.'_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10 );

                // Add columns
                add_filter( 'manage_edit-'.$taxonomy.'_columns', array( $this, 'attribute_columns' ) );
                add_filter( 'manage_'.$taxonomy.'_custom_column', array( $this, 'attribute_column' ), 10, 3 );

            }

            add_action( 'created_term', array( $this, 'save_attribute_fields' ), 10, 3 );
            add_action( 'edit_term', array( $this, 'save_attribute_fields' ), 10, 3 );
        }

        // Maintain hierarchy of terms
        add_filter( 'wp_terms_checklist_args', array( $this, 'disable_checked_ontop' ) );
    }

    public function wp_enqueue_color_picker( $hook_suffix ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
    }

    /**
     * Order term when created (put in position 0).
     *
     * @param mixed $term_id
     * @param mixed $tt_id
     * @param string $taxonomy
     */
    public function create_term( $term_id, $tt_id = '', $taxonomy = '' ) {
        if ( 'attribute' != $taxonomy && ! taxonomy_is_product_attribute( $taxonomy ) ) {
            return;
        }

        $meta_name = taxonomy_is_product_attribute( $taxonomy ) ? 'order_' . esc_attr( $taxonomy ) : 'order';

        update_woocommerce_term_meta( $term_id, $meta_name, 0 );
    }

    /**
     * When a term is deleted, delete its meta.
     *
     * @param mixed $term_id
     */
    public function delete_term( $term_id ) {
        global $wpdb;

        $term_id = absint( $term_id );

        if ( $term_id && get_option( 'db_version' ) < 34370 ) {
            $wpdb->delete( $wpdb->woocommerce_termmeta, array( 'woocommerce_term_id' => $term_id ), array( '%d' ) );
        }
    }

    /**
     * Category thumbnail fields.
     */
    public function add_attribute_fields() {
        ?>
        <div class="form-field term-display-type-wrap">
            <label for="display_type"><?php _e( 'Display type', 'woocommerce' ); ?></label>
            <select id="display_type" name="display_type" class="postform">
                <option value=""><?php _e( 'Default', 'woocommerce' ); ?></option>
                <option value="color"><?php _e( 'Color', 'woocommerce' ); ?></option>
                <option value="image"><?php _e( 'Image', 'woocommerce' ); ?></option>
                <option value="text"><?php _e( 'Text', 'woocommerce' ); ?></option>

            </select>
        </div>
        <div class="form-field term-display-color">
            <label><?php _e('Color:', 'woocommerce'); ?></label>
            <div >
                <input type='text' class="text_for_swatches" name="term_color" value="">
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('.text_for_swatches').wpColorPicker();
                });
            </script>
        </div>
        <div class="form-field term-thumbnail-wrap">
            <label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label>
            <div id="attribute_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
            <div style="line-height: 60px;">
                <input type="hidden" id="attribute_thumbnail_id" name="attribute_thumbnail_id" />
                <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
                <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
            </div>
            <script type="text/javascript">

                // Only show the "remove image" button when needed
                if ( ! jQuery( '#attribute_thumbnail_id' ).val() ) {
                    jQuery( '.remove_image_button' ).hide();
                }

                // Uploading files
                var file_frame;

                jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.downloadable_file = wp.media({
                        title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
                        button: {
                            text: '<?php _e( "Use image", "woocommerce" ); ?>'
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        var attachment = file_frame.state().get( 'selection' ).first().toJSON();

                        jQuery( '#attribute_thumbnail_id' ).val( attachment.id );
                        jQuery( '#attribute_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
                        jQuery( '.remove_image_button' ).show();
                    });

                    // Finally, open the modal.
                    file_frame.open();
                });

                jQuery( document ).on( 'click', '.remove_image_button', function() {
                    jQuery( '#attribute_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                    jQuery( '#attribute_thumbnail_id' ).val( '' );
                    jQuery( '.remove_image_button' ).hide();
                    return false;
                });

                jQuery( document ).ajaxComplete( function( event, request, options ) {
                    if ( request && 4 === request.readyState && 200 === request.status
                        && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

                        var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
                        if ( ! res || res.errors ) {
                            return;
                        }
                        // Clear Thumbnail fields on submit
                        jQuery( '#attribute_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                        jQuery( '#attribute_thumbnail_id' ).val( '' );

                        jQuery( '.remove_image_button' ).hide();
                        // Clear Display type field on submit
                        jQuery( '#display_type' ).val( '' );
                        jQuery( '.term-display-color .text_for_swatches' ).val( '' );
                        jQuery( '.term-display-color .wp-color-result' ).css('background', 'none');

                        return;
                    }
                } );

            </script>
            <div class="clear"></div>
        </div>
    <?php
    }

    /**
     * Edit category thumbnail field.
     *
     * @param mixed $term Term (category) being edited
     */
    public function edit_attribute_fields( $term ) {

        $display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );
        $term_color = get_woocommerce_term_meta( $term->term_id, 'term_color', true );
        $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );

        if ( $thumbnail_id ) {
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
        } else {
            $image = wc_placeholder_img_src();
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Display type', 'woocommerce' ); ?></label></th>
            <td>
                <select id="display_type" name="display_type" class="postform">
                    <option value="" <?php selected( '', $display_type ); ?>><?php _e( 'Default', 'woocommerce' ); ?></option>
                    <option value="color" <?php selected( 'color', $display_type ); ?>><?php _e( 'Color', 'woocommerce' ); ?></option>
                    <option value="image" <?php selected( 'image', $display_type ); ?>><?php _e( 'Image', 'woocommerce' ); ?></option>
                    <option value="text" <?php selected( 'text', $display_type ); ?>><?php _e( 'Text', 'woocommerce' ); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Color', 'woocommerce' ); ?></label></th>
            <td>
                <input type='text' class="text_for_swatches" name="term_color" value="<?php echo esc_attr($term_color); ?>"/>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery('.text_for_swatches').wpColorPicker();
                    });
                </script>
            </td>
        </tr>



        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label></th>
            <td>
                <div id="attribute_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="attribute_thumbnail_id" name="attribute_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
                    <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
                    <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
                </div>
                <script type="text/javascript">

                    // Only show the "remove image" button when needed
                    if ( '0' === jQuery( '#attribute_thumbnail_id' ).val() ) {
                        jQuery( '.remove_image_button' ).hide();
                    }

                    // Uploading files
                    var file_frame;

                    jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if ( file_frame ) {
                            file_frame.open();
                            return;
                        }

                        // Create the media frame.
                        file_frame = wp.media.frames.downloadable_file = wp.media({
                            title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
                            button: {
                                text: '<?php _e( "Use image", "woocommerce" ); ?>'
                            },
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        file_frame.on( 'select', function() {
                            var attachment = file_frame.state().get( 'selection' ).first().toJSON();

                            jQuery( '#attribute_thumbnail_id' ).val( attachment.id );
                            jQuery( '#attribute_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
                            jQuery( '.remove_image_button' ).show();
                        });

                        // Finally, open the modal.
                        file_frame.open();
                    });

                    jQuery( document ).on( 'click', '.remove_image_button', function() {
                        jQuery( '#attribute_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                        jQuery( '#attribute_thumbnail_id' ).val( '' );
                        jQuery( '.remove_image_button' ).hide();
                        return false;
                    });

                </script>
                <div class="clear"></div>
            </td>
        </tr>
    <?php
    }

    /**
     * save_attribute_fields function.
     *
     * @param mixed $term_id Term ID being saved
     * @param mixed $tt_id
     * @param string $taxonomy
     */
    public function save_attribute_fields( $term_id, $tt_id = '', $taxonomy = '' ) {

        $taxonomy_names = wc_get_attribute_taxonomy_names();

        if ( isset( $_POST['display_type'] ) && in_array($taxonomy, $taxonomy_names) ) {
            update_woocommerce_term_meta( $term_id, 'display_type', esc_attr( $_POST['display_type'] ) );
        }
        if ( isset( $_POST['term_color'] ) && in_array($taxonomy, $taxonomy_names) ) {
            update_woocommerce_term_meta( $term_id, 'term_color', esc_attr( $_POST['term_color'] ) );
        }
        if ( isset( $_POST['attribute_thumbnail_id'] ) && in_array($taxonomy, $taxonomy_names) ) {
            update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['attribute_thumbnail_id'] ) );
        }
    }

    /**
     * Thumbnail column added to category admin.
     *
     * @param mixed $columns
     * @return array
     */
    public function attribute_columns( $columns ) {
        $new_columns = array();

        if ( isset( $columns['cb'] ) ) {
            $new_columns['cb'] = $columns['cb'];
            unset( $columns['cb'] );
        }

        $new_columns['thumb'] = __( 'Image', 'woocommerce' );

        return array_merge( $new_columns, $columns );
    }

    /**
     * Thumbnail column value added to category admin.
     *
     * @param string $columns
     * @param string $column
     * @param int $id
     * @return array
     */
    public function attribute_column( $columns, $column, $id ) {

        if ( 'thumb' == $column ) {

            $display_type = get_woocommerce_term_meta( $id, 'display_type', true );
            $term_color = get_woocommerce_term_meta( $id, 'term_color', true );


            if($display_type == 'color' && $term_color){
                $columns .= sprintf('<span class="term_color" style="background: %s;">&nbsp;</span>', $term_color);
            }elseif($display_type == 'text'){
                $taxonomy = get_term($id, $_GET['taxonomy']);
                $columns .= sprintf('<span class="term_text">%s</span>', $taxonomy->name);
            }else{
                $thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );

                if ( $thumbnail_id ) {
                    $image = wp_get_attachment_thumb_url( $thumbnail_id );
                } else {
                    $image = wc_placeholder_img_src();
                }

                // Prevent esc_url from breaking spaces in urls for image embeds
                // Ref: https://core.trac.wordpress.org/ticket/23605
                $image = str_replace( ' ', '%20', $image );

                $columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'woocommerce' ) . '" class="wp-post-image" height="48" width="48" />';

            }

        }

        return $columns;
    }

    /**
     * Maintain term hierarchy when editing a product.
     *
     * @param  array $args
     * @return array
     */
    public function disable_checked_ontop( $args ) {
        if ( ! empty( $args['taxonomy'] ) && 'attribute' === $args['taxonomy'] ) {
            $args['checked_ontop'] = false;
        }
        return $args;
    }
}

new CruxStore_Admin_Attributes();
