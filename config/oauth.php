<?php

/**
 * The base URL to the hosted Ninja Forms customer portal.
 */
if( defined( 'NF_SERVER_URL' ) ){
    $server_url = NF_SERVER_URL;
} else {
    $server_url = 'http://my.ninjaforms.com';
}

$client_secret = get_option( 'ninja_forms_oauth_client_secret' );
if( ! $client_secret ){
    $client_secret = \NinjaFormsAddonManager\Keygen::generate_key();
    update_option( 'ninja_forms_oauth_client_secret', $client_secret );
}

return [
    'client_id' => get_option( 'ninja_forms_oauth_client_id' ),
    'client_secret' => $client_secret,
    'client_redirect' => add_query_arg( 'page', 'ninja-forms', admin_url() ),
    'client_site_url' => site_url(),
    'server_url' => $server_url,
    'site_manager_url' => trailingslashit( $server_url ),
    'connect_url' => trailingslashit( $server_url ) . 'oauth/connect',
    'disconnect_url' => trailingslashit( $server_url ) . 'oauth/disconnect',
];
