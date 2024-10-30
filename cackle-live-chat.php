<?php
/*
Plugin Name: Cackle Live Chat
Plugin URI: http://cackle.me/
Description: Cackle Live Chat
Version: 1.0.1
Author: Cackle
Author URI: http://cackle.me/
License: GPL2
*/
define('CACKLE_LIVE_CHAT_PLUGIN_URL', WP_CONTENT_URL . '/plugins/' . cackle_live_chat_plugin_basename(__FILE__));
function cackle_live_chat_plugin_basename($file) {
    $file = dirname($file);
    $file = str_replace('\\', '/', $file);
    $file = preg_replace('|/+|', '/', $file);
    $file = preg_replace('|^.*/' . PLUGINDIR . '/|', '', $file);
    if (strstr($file, '/') === false) {
        return $file;
    }
    $pieces = explode('/', $file);
    return !empty($pieces[count($pieces) - 1]) ? $pieces[count($pieces) - 1] : $pieces[count($pieces) - 2];
}

function cackle_live_chat_manage() {
    include_once (dirname(__FILE__) . '/manage.php');
}
function cackle_live_chat_i($text, $params = null) {
    if (!is_array($params)) {
        $params = func_get_args();
        $params = array_slice($params, 1);
    }
    return vsprintf(__($text, 'cackle'), $params);
}

class CackleChat {
    function CackleChat() {
        add_action('admin_menu', array($this, 'CackleChatSettings'), 10);
        add_action('admin_head', array($this, 'cackle_live_chat_admin_head'));
        add_action('wp_footer', array($this,'cackle_live_chat_panel'));
        add_shortcode( 'cackle_live_chat_disable', array($this,'cackle_live_chat_disable'));
    }

    function CackleChatSettings() {
        add_submenu_page('plugins.php', 'Cackle Live Chat', 'Cackle Live Chat', 'manage_options', 'cackle-live-chat', 'cackle_live_chat_manage');
    }

    function cackle_live_chat_disable( $atts ){
        if(!is_front_page()){
            remove_action( 'wp_footer', array( $this, 'cackle_live_chat_panel' ) );
        }

        return false;
    }

    function cackle_live_chat_panel($id='') {
        $siteId=get_option('cackle_live_chat_siteId');
        $text = <<<HTML
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Chat', id: '$siteId'});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>

HTML;
        echo $text;
    }

    function cackle_live_chat_admin_head() {
        if (isset ($_GET ['page']) && $_GET ['page'] == 'cackle-live-chat') {
            ?>

        <link rel='stylesheet'
              href='<?php echo CACKLE_LIVE_CHAT_PLUGIN_URL; ?>/manage.css'
              type='text/css'/>
        <script type="text/javascript">
            jQuery(function ($) {
                $('#cackle-tabs li').click(function () {
                    $('#cackle-tabs li.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $('.cackle-main, .cackle-advanced').hide();
                    $('.' + $(this).attr('rel')).show();
                });
                if (location.href.indexOf('#adv') != -1) {
                    $('#cackle-tab-advanced').click();
                }
                <?php if (isset($_POST['site_api_key'])) { ?>
                    $('#cackle-tab-advanced').click()
                    <?php }?>

            });
        </script>
        <?php
        }
    }

}

function cackle_live_chat_init() {
    $cackle_live_chat = new CackleChat();
}

add_action('init', 'cackle_live_chat_init');