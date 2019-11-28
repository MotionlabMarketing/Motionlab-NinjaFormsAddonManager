<?php

namespace NinjaFormsAddonManager\Admin;

use NinjaFormsAddonManager\Plugin;

class Submenu_Account extends \NinjaFormsAddonManager\WordPress\Admin\Menu
{
    public function __construct() {
        $this->page_title = __( 'My Account', 'ninja-forms-addon-manager' );
    }

    public function display() {
        $oauth = Plugin::config( 'oauth' );

        /** Build the `connect_url` with client specific data. */
        $connect_url = add_query_arg([
            'client_secret' => $oauth[ 'client_secret' ],
            'client_redirect' => urlencode( $oauth[ 'client_redirect' ] ),
            'client_site_url' => urlencode( $oauth[ 'client_site_url' ] ),
        ], $oauth[ 'connect_url' ] );

        $site_url = str_replace( array( 'http://', 'https://' ), '', site_url() );

        $example_url = $this->get_webhook_url( json_encode( array(
            'message' => 'This is a test.'
        )) );

        echo Plugin::view( 'menus/my-account.html.php', compact( 'oauth', 'connect_url', 'site_url', 'example_url' ) );
    }

    private function get_webhook_url( $payload ) {
        return add_query_arg([
            'nf_webhook' => 'example',
            'nf_webhook_payload' => urlencode( $payload ),
            'nf_webhook_hash' => sha1( $payload . Plugin::config( 'oauth', 'client_id' ) . Plugin::config( 'oauth', 'client_secret' )  )
        ], site_url() );
    }

}
