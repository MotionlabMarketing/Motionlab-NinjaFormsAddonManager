<?php

namespace NinjaFormsAddonManager\WordPress\Admin;

/** Ninja Forms Pro */
// $example_menu = (new Admin\Menu_Example)->setup();
// $submenu_keys = (new Admin\Submenu_Keys)->setup()->attach_to( $example_menu );
// $submenu_webhooks = (new Admin\Submenu_Webhooks)->setup()->attach_to( $example_menu );

/** Test */
// $test_menu = (new WordPress\Admin\Menu)->setup();
// $test_menu->attach_to( $example_menu );
// $test_menu->page_title( __( 'Test', 'ninja-forms-pro' ) );
// $test_menu->function(function(){
//     echo 'HERE';
// });

class Menu
{
    protected $parent_slug,
              $page_title,
              $menu_title,
              $capability,
              $menu_slug,
              $callback, // $function
              $icon_url,
              $position,
              $update_count = 0;

    public function setup( $priority = 10 ) {
        add_action( 'admin_menu', function(){
            if( $this->parent_slug ){
                add_submenu_page( $this->parent_slug(), $this->page_title(), $this->menu_title(), $this->capability(), $this->menu_slug(), $this->callback() );
            } else {
                add_menu_page( $this->page_title(), $this->menu_title(), $this->capability(), $this->menu_slug(), $this->callback(), $this->icon_url(), $this->position() );
            }
        }, $priority );
        return $this;
    }

    public function display() {
        //
    }

    /*
     * Menu/Submenu Relationship Methods
     */

    public function attach( Menu $submenu ) {
        $submenu->set_parent_slug( $this->menu_slug() );
    }

    public function attach_to( Menu $parent_menu ) {
        $this->set_parent_slug( $parent_menu->menu_slug() );
    }

    public function set_parent_slug( $parent_slug ) {
        $this->parent_slug = $parent_slug;
    }

    /*
     * Getter Methods
     */

    public function parent_slug() {
        return $this->parent_slug;
    }

    public function page_title( $page_title = '' ) {
        if( $page_title ) $this->page_title = $page_title;
        return $this->page_title;
    }

    public function menu_title( $raw = false ) {
        $menu_title = $this->menu_title ?: $this->page_title;
        if( ! $raw && $this->update_count ) {
            return $menu_title . " <span class='update-plugins count-{$this->update_count}'><span class='update-count'>{$this->update_count}</span></span>";
        }
        return $menu_title;
    }

    /**
     * To filter a menu's capability, overwrite the capability() method
     *  and add the filter in the extended class.
     */
    public function capability() {
        return $this->capability ?: 'manage_options';
    }

    /**
     * To promote a submenu to replace the parent menu display,
     *  overwrite the menu_slug() to return the parent_slug().
     */
    public function menu_slug() {
        $menu_slug = $this->menu_slug ?: sanitize_key( $this->menu_title( true ) );
        return (( $this->parent_slug() ) ?: '' ) . $menu_slug;
    }

    public function callback( $callback = null ) {
        if( $callback && is_callable( $callback ) ) $this->callback = $callback;
        return $this->callback ?: [ $this, 'display' ];
    }

    public function icon_url() {
        return $this->icon_url ?: '';
    }

    public function position() {
        return $this->position ?: null;
    }
}
