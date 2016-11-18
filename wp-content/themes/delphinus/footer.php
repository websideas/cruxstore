<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 */
?>
                <?php do_action( 'delphinus_content_bottom' ); ?>
            </div><!-- #content -->
        </div><!-- #wrapper-content -->

        <?php if(delphinus_option('footer', true)){ ?>
            <?php 
                do_action( 'delphinus_before_footer' );
                $footer_fullwidth = delphinus_option('footer_fullwidth', true);
            ?>
            <div id="footer" class="site-footer">
                <?php
                if(!$footer_fullwidth){
                    echo '<div class="container">';
                } 
                ?>
                
                <?php if($footer_top = delphinus_footer_top()){ ?>
                    <footer id="footer-top">
                        <?php 
                            if($footer_fullwidth){
                                echo '<div class="container">';
                            }
                            dynamic_sidebar('footer-top'); 
                            if($footer_fullwidth){
                                echo '</div>';
                            }
                        ?>
                    </footer><!-- #footer-top -->
                <?php } ?>

                <?php
                if($footer_widgets = delphinus_footer_widgets()){
                    $widgets_layout = delphinus_option('footer_widgets_layout', 'featured');
                    $layout = ($widgets_layout == 'featured') ? 'widgets-featured' : 'widgets';
                    get_template_part( 'templates/footers/footer', $layout);
                }

                if(delphinus_option('footer_bottom', false)){
                    get_template_part( 'templates/footers/footer', 'bottom');
                }

                ?>

                <?php if(delphinus_option('footer_copyright', true)){ ?>
                    <?php $copyright_layout = delphinus_option('footer_copyright_layout', 'centered') ?>
                    <footer id="footer-copyright" class="footer-copy-<?php echo esc_attr($copyright_layout); ?>">
                        <?php
                            if($footer_fullwidth){
                                echo '<div class="container">';
                            }
                            get_template_part( 'templates/footers/footer',  $copyright_layout); 
                            if($footer_fullwidth){
                                echo '</div>';
                            }
                        ?>
                    </footer><!-- #footer-copyright -->
                <?php } ?>
                <?php
                if(!$footer_fullwidth){
                    echo '</div';
                } 
                ?>
            </div><!-- #footer -->

            <?php do_action( 'delphinus_after_footer' ); ?>
        <?php } ?>
    </div><!-- #page -->
</div><!-- #page_outter -->


<?php wp_footer(); ?>
<!-- W3TC-include-js-head -->

</body>
</html>
