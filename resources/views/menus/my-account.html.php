<style media="screen">
  #wpwrap {
    background-color: white;
  }
  #ninja-forms-account,
  #ninja-forms-account *,
  #ninja-forms-account *::before,
  #ninja-forms-account *::after {
    box-sizing: border-box;
  }
  #ninja-forms-account.wrap {
    margin: 0;
    margin-left: -20px; /* Adjust for WP Admin page. */
  }
  #ninja-forms-account header.topbar {
    background-color: #ebedee;
  }
  #ninja-forms-account header .app-title {
    width: 100%;
    max-width: 50rem;
    margin: auto;
        margin-bottom: auto;
    background-image: url(/wp-content/plugins/ninja-forms/assets/img/nf-logo-dashboard.png);
    background-size: 315px 48px;
    background-position: 0 100%;
    background-repeat: no-repeat;
    height: 52px;
  }
  #ninja-forms-account header .app-title strong {
    display: none;
  }
  #ninja-forms-account .content-wrap {
    width: 100%;
    margin: auto;
    padding: 20px;
    max-width: 50rem;
  }
  #ninja-forms-account .account-actions {
    clear: both;
    margin-top: 20px;
  }
  #ninja-forms-account #nfOAuthDisconnect {
    cursor: pointer;
  }
</style>

<div id="ninja-forms-account" class="wrap">

    <header class="topbar">
      <div class="app-title">
        <strong>Ninja Forms Dashboard</strong>
      </div>
    </header>

    <div class="content-wrap">

      <h2>Welcome to the Ninja Forms Add-on Manager!</h2>

      <p>
        The Ninja Forms Add-on Manager aims to eliminate the burden of downloads and license keys associated with the add-on model. Currently you download & upload multiple files and manually copy their license keys.
      </p>

      <p>
        This is the first step for Ninja Forms customers to be able to remotely install add-ons with a single click from My.NinjaForms.com.
        <a target="_blank" href="https://ninjaforms.com/docs/add-on-manager/">Read More</a>
      </p>

      <div class="account-actions">
        <?php if( $oauth[ 'client_id' ] ): ?>

          <a class="button button-primary"
             href="<?php echo add_query_arg( 'site', $site_url, $oauth[ 'site_manager_url' ] ); ?>">
            Go to Site Manager
          </a>

          <a id="nfOAuthDisconnect"  class="button button-secondary" style="float:right;">
            Disconnect
          </a>

        <?php else: ?>
          <a href="<?php echo $connect_url; ?>" class="button button-primary">
            Connect to my.NinjaForms.com
          </a>
        <?php endif; ?>
      </div>

    </div>

</div>

<script type="text/javascript">
    (function( $ ) {
        $( '#nfOAuthDisconnect' ).click(function(){
            jQuery.post(ajaxurl, { 'action': 'oauth_disconnect' }, function() {
                location.reload(true);
            });
        });
    })( jQuery );
</script>
