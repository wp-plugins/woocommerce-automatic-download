<?php
/*
  Plugin Name: WooCommerce Automatic Download
  Plugin URI: http://scriptbaker.com
  Description: Automatically starts the downloading of downloadable products on checkout success page.
  Version: 1.0
  Author: Tahir Yasin
  Author URI: http://scriptbaker.com
  Text Domain: automatic-download

  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

add_filter( 'woocommerce_get_settings_products', 'add_automatic_download_setting', 10, 2 );

/*
* Adding enable/disable Automatic Download option in WooCommerce product settings page
*/
function add_automatic_download_setting( $settings ) {
    $updated_settings = array();
    foreach ( $settings as $section ) {
        // at the bottom of the Downloadable Products section
        if ( isset( $section['id'] ) && 'digital_download_options' == $section['id'] &&
            isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
            $updated_settings[] = array(
                'name'     => __( 'Automatic Download', 'automatic-download' ),
                'desc_tip' => __( 'Enable this option if you want the downloadable products to start downloading automatically on checkout success.', 'text-domain' ),
                'id'       => 'woocommerce_enable_auto_download',
                'type'     => 'checkbox',
                'desc'     => __( 'Enable automatic download', 'automatic-download' ),

            );
        }
        $updated_settings[] = $section;
    }
    return $updated_settings;
}

add_action('wp_footer', 'add_automatic_download_script');

/**
* Adding script to website's footer that will iniate automatic downloads
*/
function add_automatic_download_script(){

$order_number_start = get_option( 'woocommerce_enable_auto_download' );
if($order_number_start == 'yes'):?>
    <script>
        (function(){
            (function($){
                var i = 0;
                $( "small > a" ).each(function( index ) {
                    var src = jQuery(this).attr('href');
                    $('<iframe id="d-'+i+'" style="display:none;">').appendTo('body');
                    $('#d-'+i).attr('src', src);
                    i++;
                });
            })(jQuery);
        })();
    </script>
<?php endif;
}

/**
* Enable Automatic Download option on plugin activation
*/
function enable_automatic_download_option() {
    update_option( 'woocommerce_enable_auto_download', 'yes' );
}
register_activation_hook( __FILE__, 'enable_automatic_download_option' );

/**
* Database cleanup on plugin deletion
*/
function remove_automatic_download_option() {
    delete_option( 'woocommerce_enable_auto_download' );
}
register_uninstall_hook( __FILE__, 'remove_automatic_download_option' );
?>