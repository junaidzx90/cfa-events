<?php get_header(); ?>

<?php
$event_location = get_post_meta(get_post()->ID, '__event_location', true);
$date = get_post_meta(get_post()->ID, '__event_date', true);
if($date){
    $date = date("F j, Y", strtotime($date));
}

$star_time = get_post_meta(get_post()->ID, '__event_start_time', true);
if($star_time){
    $star_time = date("g:ia", strtotime($star_time));
}

$end_time = get_post_meta(get_post()->ID, '__event_end_time', true);
if($end_time){
    $end_time = date("g:ia", strtotime($end_time));
}

$location_url = get_post_meta(get_post()->ID, '__event_location_url', true);
$venue_info = get_post_meta(get_post()->ID, "__event_venue_info", true);;
?>

<div id="event_page">
    <?php
    // Alert if event is passed
    if(!get_active_event(get_post()->ID)){
        echo '<div class="cfa_alert dangerAlert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#4d4d4d" width="20px" height="20px" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
            <metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
            <g><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"><path d="M4530.1,5007.9C3375.4,4889,2341.7,4400,1530.4,3588.6C751.6,2809.9,287.5,1860.5,124.4,723.1c-32.6-226.3-32.6-982,0-1208.3c163-1137.4,629.1-2086.8,1405.9-2865.5c778.7-776.8,1730-1244.8,2865.5-1405.9c226.3-32.6,982-32.6,1208.3,0c581.2,82.5,1060.7,228.2,1553.6,471.8c535.1,264.7,918.7,540.9,1327.3,953.2c404.7,408.5,657.9,767.2,918.7,1292.7c243.6,492.9,389.4,972.4,471.8,1553.6c32.6,226.3,32.6,982,0,1208.3c-161.1,1135.5-629.1,2086.8-1405.9,2865.5c-759.5,759.5-1687.8,1223.7-2779.2,1392.5C5448.8,5017.5,4775.6,5034.8,4530.1,5007.9z M5364.4,3782.4c742.3-76.7,1434.7-370.2,2004.3-847.8c1147-960.9,1595.8-2539.4,1129.7-3966.4c-90.1-272.4-301.1-696.2-464.2-930.2c-521.7-749.9-1264-1265.9-2134.7-1486.4C4200.2-3876.2,2445.2-3053.4,1683.8-1473c-629.1,1302.3-433.5,2846.3,500.6,3960.7C2959.3,3414.1,4169.5,3905.1,5364.4,3782.4z"/><path d="M4808.2,2543.3c-191.8-71-329.9-211-393.2-402.8c-26.8-82.5-28.8-218.7-24.9-1139.3l5.7-1045.3l48-95.9c59.5-122.8,174.5-237.8,297.3-297.3l95.9-47.9h1083.7h1083.7l95.9,47.9C7223-377.8,7338-262.7,7397.5-140c67.1,138.1,67.1,379.8,0,517.9C7338,500.7,7223,615.7,7100.2,675.2l-95.9,47.9l-694.3,5.7l-694.3,5.8l-5.8,694.3l-5.8,694.3l-47.9,95.9c-59.5,122.7-176.5,239.8-295.4,297.3C5138.1,2574,4931,2587.4,4808.2,2543.3z"/></g></g>
            </svg>
            <p>This event has passed.</p>
        </div>';
    }
    ?>

    <h1 class="head1"><?php echo the_title(); ?></h1>

    <p class="event__date"><?php echo $date.(($venue_info) ? ' | ' : '').$venue_info.(($event_location) ? ' | ' : '').$event_location ?></p>

    <div class="event_thumbnail">
        <?php echo ((get_the_post_thumbnail_url()) ? the_post_thumbnail( 'large' ) : '<img src="'.get_option('cfa_fallback_thumb').'" alt="thumbnail">') ?>
    </div>

    <div class="event_contents">
        <?php echo the_content(  ) ?>
    </div>

    <?php
    // If event date is active
    if(get_active_event(get_post()->ID)){
        ?>
        <div class="information_box">
            <?php
            if(!empty($location_url)){
                ?>
                <p class="location"><strong>Venue: </strong><?php echo $venue_info ?> <?php echo ((!empty($venue_info) && !empty($event_location)) ? '|': '') ?> <?php echo $event_location ?> <a target="_blank" href="<?php echo (($location_url) ? $location_url : '#') ?>">View map</a></p>
                <?php
            }
            ?>
            <p><strong>Date:</strong> <?php echo $date ?></p>
            <p><strong>Time:</strong> <?php echo $star_time." - ".$end_time?></p>

            <div class="calendars">
                <span class="calendar_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
                    <g><g><path d="M728.7,532.7h-65.3v130.7h65.3V532.7z M598,532.7h-65.3v130.7H598V532.7z M728.7,336.7h-65.3v130.7h65.3V336.7z M859.3,532.7H794v130.7h65.3V532.7z M598,728.7h-65.3v130.7H598V728.7z M794,10h-65.3v130.7H794V10z M271.3,10H206v130.7h65.3V10z M859.3,336.7H794v130.7h65.3V336.7z M598,336.7h-65.3v130.7H598V336.7z M336.7,728.7h-65.3v130.7h65.3V728.7z M206,532.7h-65.3v130.7H206V532.7z M336.7,532.7h-65.3v130.7h65.3V532.7z M859.3,75.3V206h-196V75.3H336.7V206h-196V75.3H10V990h980V75.3H859.3z M924.7,924.7H75.3V271.3h849.3V924.7z M206,728.7h-65.3v130.7H206V728.7z M467.3,336.7H402v130.7h65.3V336.7z M467.3,728.7H402v130.7h65.3V728.7z M336.7,336.7h-65.3v130.7h65.3V336.7z M467.3,532.7H402v130.7h65.3V532.7z M728.7,728.7h-65.3v130.7h65.3V728.7z"/></g></g>
                    </svg>
                </span>
                <a href="?cfaction=cfa_event_calendar&event=<?php echo get_post()->ID ?>">add to calendar</a>
            </div>
        </div>

        <div class="event_registration_form">
            <div v-if="isForm">
                <h3 class="head3"><strong>Register</strong> Here</h3>
                <form action="" method="post">
                    <div class="reg_inputs">
                        <input type="hidden" name="event_id" value="<?php echo get_post()->ID ?>">
                        <input :disabled="isDisabled" type="text" placeholder="Your name" v-model="registrant_name" name="event_registrant_name">
                        <input :disabled="isDisabled" type="email" placeholder="Your email" v-model="registrant_email" name="event_registrant_email">
                        <input :disabled="isDisabled" type="text" placeholder="Your phone number" v-model="registrant_phone" name="event_registrant_phone">
                        <input :disabled="isDisabled" name="company_organization" placeholder="Company / Organization" v-model="registrant_company" type="text">

                        <div class="blankspacer"></div>
                        <div class="joinbutton">
                            <input :disabled="isDisabled" type="submit" value="Join" @click="register_form_submit(event)" name="event_join" class="event-join-btn">
                        </div>
                    </div>
                </form>
            </div>
            <div class="submission-alert" v-if="isForm === false" v-html="submittedAlert"></div>
        </div>
        <?php
    }
    ?>
</div>
<?php get_footer(); ?>