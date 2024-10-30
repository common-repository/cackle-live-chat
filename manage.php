<?php
function cackle_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    if (isset($_POST['cackle_comments_wpnonce'])) {
        if (wp_verify_nonce($_POST['cackle_comments_wpnonce'], plugin_basename(__FILE__))) {
            /**
             * Check each input to update in db
             */
                    update_option('cackle_live_chat_siteId', (int)$_POST['site_id']);
        }
    }

    ?>
<div class="wrap">

    <form method="post">

        <p  style="float: left; clear:both;font-size: 13px; font-weight: bold; line-height: 30px; padding-left: 13px;">
        <ol><li>Register 5-day trial account on <a href='http://ru.cackle.me/pricing?demo=true'>Cackle</a>
            checking with Live Chat widget </li><li>You need to create chat widget on http://cackle.me
            on tab 'Widget'->Live chat</li>Please, find line with 'var mcChat =' in js code and paste below</ol></p>

        <?php    wp_nonce_field(plugin_basename(__FILE__), 'cackle_comments_wpnonce', false, true); ?>
        <?php $siteId = get_option('cackle_live_chat_siteId', '')?>
                <p><?php echo __('Cackle Site ID', 'cackle_comments'); ?>: <input type="text" value="<?php echo $siteId;?>"
                                                                        name="site_id"/>

        </p>

        <p><input type="submit" value="Activate" name="update" class="button-primary button" tabindex="4"/></p>
    </form>
</div>
<?php
}

$show_advanced = (isset($_GET['t']) && $_GET['t'] == 'adv');
?>
<div class="wrap" id="cackle-wrap">
    <ul id="cackle-tabs">

        <li<?php if ($show_advanced) echo ' class="selected"'; ?> id="cackle-tab-advanced"
                                                                  rel="cackle-advanced"><?php echo cackle_live_chat_i('Options'); ?></li>
    </ul>

    <div id="cackle-main" class="cackle-content">


        <!-- Advanced options -->

        <div id="cackle-advanced" class="cackle-content cackle-advanced"
            <?php if (!$show_advanced) echo ' style="display:block;"'; ?>>
            <a style="float: left; margin-bottom: 12px; margin-top:10px;" href="http://cackle.ru" target="_blank"><img
                    alt="cackle logo" src="http://cackle.ru/static/img/logo.png"></a>

            <p style="float: left; font-size: 13px; font-weight: bold; line-height: 30px; padding-left: 13px;"> social
                platform (comments, authorization, chat) that helps your website's audience communicate through social networks.</p>
            <br/>


            <h2 style="padding-left: 0px;clear:both;"><?php echo cackle_live_chat_i('Options'); ?></h2>
            <?php cackle_options();?>


        </div>

    </div>
    </div>