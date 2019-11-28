<?php

namespace NinjaFormsAddonManager;

final class Plugin extends WordPress\Plugin
{
    public function setup( $version, $file ) {

        $this->version = $version;
        $this->url = plugin_dir_url( $file );
        $this->dir = plugin_dir_path( $file );

        /** Actions */
        add_action( 'activated_plugin', [ $this, 'activation' ] );
        add_action( 'plugins_loaded', [ $this, 'maybe_route_wehbook' ] );
        add_action( 'admin_init', [ $this, 'setup_license' ] );
        add_action( 'admin_init', [ $this, 'maybe_update_client_id' ]);
        add_action( 'init', [ $this, 'setup_admin_menus' ] );
        add_action( 'admin_notices', [ $this, 'admin_notice' ]);
        add_action( 'admin_enqueue_scripts', [ $this, 'register_dashboard_assets' ] );

        /** Filters */
        add_filter( 'http_request_args', [ $this, 'maybe_whitelist_request' ], 10, 2 );

        /** AJAX Actions */
        add_action( 'wp_ajax_oauth_disconnect', [ $this, 'disconnect_oauth' ] );
    }

    public function activation( $plugin ) {
      if( $plugin !== plugin_basename( $this->dir( 'ninja-forms-addon-manager.php' ) ) )  return;
      exit( wp_redirect( add_query_arg( 'page', 'ninja-formsmyaccount', admin_url( 'admin.php' ) ) ) );
    }

    /**
     * Setup Admin Menu and Submenu Pages.
     */
    public function setup_admin_menus() {

        // Only setup menus in the admin.
        if( ! is_admin() ) return;

        // Register the submenu and attach to the Ninja Forms menu.
        $submenu_account = (new Admin\Submenu_Account)->setup( 15 )->set_parent_slug( 'ninja-forms' );
    }

    public function maybe_route_wehbook() {
        if( ! isset( $_REQUEST[ 'nf_webhook' ] ) )  return;
        $controller = new Webhooks\Router( $_REQUEST[ 'nf_webhook' ], $this->config( 'webhooks', 'controllers' ) );
        $controller->init( $_REQUEST[ 'nf_webhook_payload' ], $_REQUEST[ 'nf_webhook_hash' ], $this->config( 'oauth', 'client_id' ), $this->config( 'oauth', 'client_secret' ) );
    }

    public function setup_license() {
      if ( ! class_exists( 'NF_Extension_Updater' ) ) return;
      new \NF_Extension_Updater( 'Add-on Manager', $this->version, 'Saturday Drive', $this->dir( 'ninja-forms-addon-manager.php' ), 'add-on-manager' );
    }

    public function maybe_update_client_id() {
        if( isset( $_GET[ 'page' ] ) && 'ninja-forms' == $_GET[ 'page' ] ) {

          /**
           * Update the Add-on Manager license key for automatic updates.
           */
          if( isset( $_GET[ 'license' ] ) ){
            $license = sanitize_text_field( $_GET[ 'license' ] );
            Ninja_Forms()->update_setting( 'add-on-manager_license', $license );
            Ninja_Forms()->update_setting( 'add-on-manager_license_error', '' ); // Manually clear errors.
            Ninja_Forms()->update_setting( 'add-on-manager_license_status', 'valid' ); // Manually set status.
          }

            // TODO: Register the client_id query var.
            if ( isset( $_GET[ 'client_id' ] ) ) {
                $client_id = sanitize_text_field( $_GET[ 'client_id' ] );
                update_site_option( 'ninja_forms_oauth_client_id', $client_id );

                $site_url = Plugin::config( 'oauth', 'client_site_url' );
                $site_manager_url = add_query_arg( 'site', urlencode($site_url), Plugin::config( 'oauth', 'site_manager_url' ) );
                wp_redirect( $site_manager_url );
                exit();
            }
        }
    }

    public function admin_notice() {
        if( isset( $_GET['form_id'] ) ) return;
        if( ! isset( $_GET['page'] ) || 'ninja-forms' != $_GET['page'] ) return;

        /** Display the "Connected" flash message. */
        if( isset( $_GET[ 'connected' ] ) ){
            wp_enqueue_script( 'ninja_forms_addon_manager_dashboard_script' );
            wp_enqueue_style( 'ninja_forms_addon_manager_dashboard_style' );
            echo $this->view( 'oauth/success.html.php' );
            return;
        }

        $oauth = $this->config( 'oauth' );

        /** If the `client_id` is already set, then already connected. */
        if( $oauth[ 'client_id' ] ) return;

        /** Build the `connect_url` with client specific data. */
        $connect_url = add_query_arg([
            'client_secret' => $oauth[ 'client_secret' ],
            'client_redirect' => urlencode( $oauth[ 'client_redirect' ] ),
            'client_site_url' => urlencode( $oauth[ 'client_site_url' ] ),
        ], $oauth[ 'connect_url' ] );

        /** Display the OAuth notice for connecting the client site. */
        wp_enqueue_script( 'ninja_forms_addon_manager_dashboard_script' );
        wp_enqueue_style( 'ninja_forms_addon_manager_dashboard_style' );
        echo $this->view( 'oauth/notice.html.php', compact( 'connect_url' ) );
    }

    public function register_dashboard_assets() {
      wp_register_script( 'ninja_forms_addon_manager_dashboard_script', $this->url( 'public/js/dashboard.js' ), [ 'jquery' ] );
      wp_register_style( 'ninja_forms_addon_manager_dashboard_style', $this->url( 'public/css/dashboard.css' ) );
    }

    public function maybe_whitelist_request( $args, $url ) {
      if( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $args['sslverify'] = false; // Local development
        $args['reject_unsafe_urls'] = false;
      }
      return $args;
    }

    /**
     * Whitelist request to NinjaForms.com
     * @link https://core.trac.wordpress.org/ticket/24646
     */
    public function disconnect_oauth() {
        $oauth = $this->config( 'oauth' );
        wp_remote_request( $oauth[ 'disconnect_url' ], [
            'method' => 'DELETE',
            'body' => [
                'client_id' => $oauth[ 'client_id' ],
                'client_secret' => $oauth[ 'client_secret' ]
            ],
            'sslverify' => false
        ]);
        delete_option( 'ninja_forms_oauth_client_id' );
        delete_option( 'ninja_forms_oauth_client_secret' );
    }
}
