<?php
/**
 * Plugin name: Boardio Shortcode
 * Plugin URI: https://github.com/Seravo/wp-boardio-widget
 * Description: Creates a shortcode for the Boardio javascript widget
 * Version: 1.0
 * Author: Seravo Oy
 * Author: http://seravo.fi
 * License: GPLv3
 */

class Boardio_Shortcode {

  public static $instance;
    
  public static function init() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new Boardio_Shortcode();
    }
    return self::$instance;
  }

  private function __construct() {
    add_shortcode( 'boardio', array( 'Boardio_Shortcode', 'shortcode_handler' ) );
  }

  /**
   * The shortcode handler
   */
  public static function shortcode_handler( $atts ) {
    $atts = shortcode_atts( array(
      'partner' => 0,
      'widget' => 0,
    ), $atts, 'boardio' );

    return self::$instance->render(
      array(
        'partnerID' => $atts['partner'],
        'widgetID' => $atts['widget'],
      )
    );
  }

  /**
   * Renders the actual widget
   */
  public function render($options) {
    ob_start();
  ?>
  <div id="boardioWidget"></div>
  <script>// <![CDATA[
    var boardioPartnerID = <?php echo intval($options['partnerID']); ?>;
    var boardioWidgetID = 'boardioWidget';
    (function (window, document) {
      var loader = function () {

        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
        script.src = "//api.boardio.com/widget/embed/embed.min.js";
        tag.parentNode.insertBefore(script, tag);

      };
      window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
  // ]]></script>
  <?php
    return ob_get_clean();
  }

}

// Init the plugin
$boardio = Boardio_Shortcode::init();

