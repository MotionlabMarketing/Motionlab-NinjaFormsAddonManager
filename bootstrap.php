<?php

// Library
require_once( plugin_dir_path( __FILE__ ) . 'lib/keygen.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/webhooks/router.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/webhooks/response.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/webhooks/controller.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/wordpress/plugin.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/wordpress/admin/menu.php' );

// Project includes
require_once( plugin_dir_path( __FILE__ ) . 'includes/plugin.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/submenu-account.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/webhooks/webhook-example.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/webhooks/webhook-sync.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/webhooks/webhook-install.php' );
