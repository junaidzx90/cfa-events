<?php
$current_tab = 'general';

if(isset($_GET['post_type']) && $_GET['post_type'] === 'events' && isset($_GET['page']) && $_GET['page'] === 'cfa-setting' && isset($_GET['tab'])){
    $tab = sanitize_text_field( $_GET['tab'] );

    switch ($tab) {
        case 'general':
            $current_tab = 'general';
            break;
        case 'styles':
            $current_tab = 'styles';
            break;
        default:
            $current_tab = 'general';
            break;
    }
}
?>
<div class="cfa_setting_page">
    <div class="settings__tab">
        <a href="?post_type=events&page=cfa-setting&tab=general" class="tablinks <?php echo (($current_tab === 'general') ? 'active': '') ?>">General</a>
        <a href="?post_type=events&page=cfa-setting&tab=styles" class="tablinks <?php echo (($current_tab === 'styles') ? 'active': '') ?>">Styles</a>
    </div>

    <div id="general-settings" class="tabcontent <?php echo (($current_tab === 'general') ? 'active': '') ?>">
        <div class="cfa-settings">
            <form style="width: 75%;" method="post" action="options.php">
                <?php
                settings_fields( 'cfa_general_opt_section' );
                do_settings_sections('cfa_general_opt_page');
                echo get_submit_button( 'Save Changes', 'secondary', 'save-cfa-setting' );
                ?>
            </form>
        </div>
    </div>
    
    <div id="styles-settings" class="tabcontent <?php echo (($current_tab === 'styles') ? 'active': '') ?>">
        <div class="cfa-settings">
            <form style="width: 75%;" method="post" action="options.php">
                <?php
                settings_fields( 'cfa_styles_opt_section' );
                do_settings_sections('cfa_styles_opt_page');
                echo get_submit_button( 'Save Changes', 'secondary', 'save-cfa-setting' );
                ?>
            </form>
        </div>
    </div>
</div>