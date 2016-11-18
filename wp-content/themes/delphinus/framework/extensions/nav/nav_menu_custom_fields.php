<?php
/**
 * Define all custom fields in menu
 *
 * @version: 1.0.0
 * @package  Kite/Template
 * @author   KiteThemes
 * @link     http://kitethemes.com
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

add_action( 'walker_nav_menu_custom_fields', 'delphinus_add_custom_fields', 10, 4 );
function delphinus_add_custom_fields( $item_id, $item, $depth, $args ) { ?>
    <div class="clearfix"></div>
    <div class="container-megamenu">
        <p class="field-icon description description-wide clearfix">
            <label for="menu-item-icon-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e( 'Select icon of this item (set empty to hide). Ex: fa fa-home', 'delphinus'); ?><br />
                <input type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-icon" name="menu-item-megamenu-icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->icon ); ?>" />
            </label>
        </p>
        <div class="wrapper-megamenu">
            <p class="field-enable description description-wide">
                <label for="menu-item-enable-<?php echo esc_attr($item_id); ?>">
                    <input type="checkbox" <?php checked($item->enable, 'enabled'); ?> data-id="<?php echo esc_attr($item_id); ?>" id="menu-item-enable-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-enable[<?php echo esc_attr($item_id); ?>]" value="enabled" class="edit-menu-item-enable"/>
                    <b><?php esc_html_e( 'Enable Mega Menu (only for main menu)', 'delphinus'); ?></b>
                </label>
            </p>
            <div id="content-megamenu-<?php echo esc_attr($item_id); ?>" class="megamenu-layout clearfix">
                <div class="megamenu-layout-depth-0">
                    <p class="field-columns description description-wide">
                        <label for="menu-item-columns-<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e( 'Mega Menu number of columns', 'delphinus'); ?><br />
                            <select id="menu-item-columns-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-columns[<?php echo esc_attr($item_id); ?>]" class="widefat edit-menu-item-columns">
                                <?php $colums_count = 4; ?>
                                <option value=""><?php echo esc_html($colums_count) ?></option>
                                <?php for($i=$colums_count-1; $i>1; $i--){ ?>
                                    <option <?php selected($item->columns, $i); ?> value="<?php echo esc_attr($i) ?>"><?php echo esc_html($i); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </p>
                    <p class="field-layout description description-wide">
                        <label for="menu-item-layout-<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e( 'Mega Menu layout', 'delphinus'); ?><br />
                            <select id="menu-item-layout-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-layout[<?php echo esc_attr($item_id); ?>]" class="widefat edit-menu-item-layout">
                                <option <?php selected($item->layout, ''); ?> value=""><?php esc_html_e('Default', 'delphinus'); ?></option>
                                <option <?php selected($item->layout, 'table'); ?> value="table"><?php esc_html_e('Table + Border', 'delphinus'); ?></option>
                            </select>
                        </label>
                    </p>
                    <p class="field-width description description-wide">
                        <label for="menu-item-width-<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e( 'Mega Menu width', 'delphinus'); ?><br />
                            <select id="menu-item-width-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-width[<?php echo esc_attr($item_id); ?>]" class="widefat edit-menu-item-width">
                                <option <?php selected($item->width); ?> value=""><?php esc_html_e('Full Width', 'delphinus'); ?></option>
                                <option <?php selected($item->width, 'half'); ?> value="half"><?php esc_html_e('1/2', 'delphinus'); ?></option>
                                <option <?php selected($item->width, 'three'); ?> value="three"><?php esc_html_e('3/4', 'delphinus'); ?></option>
                                <option <?php selected($item->width, 'four'); ?> value="four"><?php esc_html_e('4/5', 'delphinus'); ?></option>
                                <option <?php selected($item->width, 'five'); ?> value="five"><?php esc_html_e('9/10', 'delphinus'); ?></option>
                            </select>
                        </label>
                    </p>
                    <p class="description description-position description-wide">
                        <label>
                            <?php esc_html_e( 'Mega menu position', 'delphinus'); ?><br />
                            <select id="menu-item-position-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-position[<?php echo esc_attr($item_id); ?>]" class="widefat edit-menu-item-position">
                                <option <?php selected($item->position, ''); ?> value="">Center</option>
                                <option <?php selected($item->position, 'left-menubar'); ?> value="left-menubar"><?php esc_html_e( 'Left edge of menu bar', 'delphinus'); ?></option>
                                <option <?php selected($item->position, 'right-menubar'); ?> value="right-menubar"><?php esc_html_e( 'Right edge of menu bar', 'delphinus'); ?></option>
                                <option <?php selected($item->position, 'left-parent'); ?> value="left-parent"><?php esc_html_e( 'Left Edge of Parent item', 'delphinus'); ?></option>
                                <option <?php selected($item->position, 'right-parent'); ?> value="right-parent"><?php esc_html_e( 'Right Edge of Parent item', 'delphinus'); ?></option>
                            </select>
                        </label>
                    </p>
                </div>
                <div class="megamenu-layout-depth-1">
                    <p class="field-clwidth description description-wide">
                        <label for="menu-item-clwidth-<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e( 'Mega Menu Column Width - Overrides parent colum (in percentage, ex: 30%)', 'delphinus'); ?><br />
                            <input type="text" value="<?php echo esc_attr($item->clwidth); ?>" id="menu-item-clwidth-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-clwidth[<?php echo esc_attr($item_id); ?>]" class="widefat edit-menu-item-clwidth"/>
                        </label>
                    </p>
                    <p class="field-columntitle description description-wide">
                        <label for="menu-item-columntitle-<?php echo esc_attr($item_id); ?>">
                            <input type="checkbox" <?php checked($item->columntitle, 'enabled'); ?> id="menu-item-columntitle-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-columntitle[<?php echo esc_attr($item_id); ?>]" value="enabled"/>
                            <?php esc_html_e('Disable Mega Menu Column Title', 'delphinus'); ?>
                        </label>
                    </p>
                    <p class="field-columnlink description description-wide">
                        <label for="menu-item-columnlink-<?php echo esc_attr($item_id); ?>">
                            <input type="checkbox" <?php checked($item->columnlink, 'enabled'); ?> id="menu-item-columnlink-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-columnlink[<?php echo esc_attr($item_id); ?>]" value="enabled"/>
                            <?php esc_html_e('Disable Mega Menu Column Link', 'delphinus'); ?>
                        </label>
                    </p>
                    <p class="field-endrow description description-wide">
                        <label for="menu-item-endrow-<?php echo esc_attr($item_id); ?>">
                            <input type="checkbox" <?php checked($item->endrow, 'enabled'); ?> id="menu-item-endrow-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-endrow[<?php echo esc_attr($item_id); ?>]" value="enabled" class="edit-menu-item-endrow"/>
                            <?php esc_html_e( 'End Row (Clear the next row and start a new one with next item)', 'delphinus'); ?>
                        </label>
                    </p>
                    <p class="field-widget description description-wide">
                        <label for="menu-item-widget-<?php echo esc_attr($item_id); ?>">
                            <?php esc_html_e('Mega Menu Widget Area', 'delphinus'); ?><br />
                            <?php $sidebars = delphinus_sidebars();?>
                            <select id="menu-item-widget-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu-widget[<?php echo esc_attr($item_id); ?>]" class="widefat edit-menu-item-widget">
                                <option value=""><?php esc_html_e( 'Select Widget Area', 'delphinus'); ?></option>
                                <?php foreach($sidebars as $k=>$v){ ?>
                                    <option <?php selected($item->widget, $k); ?> value="<?php echo esc_attr($k); ?>"><?php echo esc_html($v) ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </p>
                </div>
            </div><!-- #content-megamenu-<?php echo esc_attr($item_id); ?> -->
        </div><!-- .wrapper-megamenu -->
    </div><!-- .container-megamenu -->
<?php }
