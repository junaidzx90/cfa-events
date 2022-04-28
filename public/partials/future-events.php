<?php
$latestEvents = array();
$args = array(
    'post_type' => 'events',
    'post_status' => 'publish',
    'numberposts' => -1,
    'meta_key' => '__event_date',
    'orderby' => 'meta_value',
    'meta_type' => 'DATE',
    'order'     => 'asc',
    'meta_query' => array(
        array(
            'key' => '__event_date',
            'value' => date("Y-m-d"),
            'compare' => '>='
        )
    )
);

$events = array();
$futureEvents = get_posts( $args );

if($futureEvents){
    foreach($futureEvents as $e){
        $end_time = get_post_meta($e->ID, '__event_end_time', true);
        if($end_time){
            $end_time = date("h:ia", strtotime($end_time));
            $todayWithTime = strtotime(date("Y-m-d h:ia"));
            $eventDate = get_post_meta($e->ID, '__event_date', true);
            $eventDate = strtotime($eventDate." ".$end_time);

            if($eventDate > $todayWithTime){
                $events[] = $e;
            }
        }
    }
}

if($events){
    foreach ($events as $event) {
        $event_id = $event->ID;
        $event_title = $event->post_title;
        $event_date = get_post_meta($event_id, '__event_date', true);
        if($event_date){
            $event_date = date("j F, Y", strtotime($event_date));
        }
        $venue_info = get_post_meta($event_id, "__event_venue_info", true);;
        $thumbnail = ((get_the_post_thumbnail_url( $event_id )) ? get_the_post_thumbnail_url( $event_id ) : get_option('cfa_fallback_thumb') );
        $len = ((get_option('excerpt_length')) ? get_option('excerpt_length') : 10);
        $excerpt = wp_trim_words(get_the_excerpt( $event_id ), $len);
        $permalink = get_the_permalink( $event->ID );

        $eventArr = array(
            'event_id' => $event_id,
            'title' => $event_title,
            'thumbnail' => $thumbnail,
            'date' => $event_date,
            'venue' => $venue_info,
            'excerpt' => $excerpt,
            'permalink' => $permalink
        );

        $latestEvents[] = $eventArr;
    }
}

if(sizeof($latestEvents)){
    ?>
    <!-- Latest Events -->
    <div class="latest_events_wrap">
        <div class="latest_events">
            <div class="events_wrapper">
            <?php
            foreach($latestEvents as $event){
                ?>
                <!-- Event Card -->
                <div class="event_card">
                    <div class="event_thumbnail">
                        <img src="<?php echo $event['thumbnail'] ?>" alt="thumbnail">
                    </div>

                    <div class="event_contents">
                        <h4 class="event__title"><?php echo $event['title'] ?></h4>
                        <p class="event__date"><?php echo $event['date'] ?> <?php echo (($event['date'] !== "" && $event['venue'] !== "") ? '|': '') ?> <?php echo $event['venue'] ?></p>
                        <p class="event_excerpt"><?php echo $event['excerpt'] ?></p>
                    </div>

                    <div class="seemore_btn_box">
                        <a href="<?php echo $event['permalink'] ?>" class="button-seemore cfa_btn">Read More</a>
                    </div>
                </div>
                <!-- // Event Card -->
                <?php
            }
            ?>
            </div>
        </div> <!-- // Latest Events -->
    </div>
    <?php
}else{
    ?>
    <div class="noupcommingevents_found">
        <div class="cfa_alert dangerAlert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#4d4d4d" width="20px" height="20px" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
            <metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
            <g><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"><path d="M4530.1,5007.9C3375.4,4889,2341.7,4400,1530.4,3588.6C751.6,2809.9,287.5,1860.5,124.4,723.1c-32.6-226.3-32.6-982,0-1208.3c163-1137.4,629.1-2086.8,1405.9-2865.5c778.7-776.8,1730-1244.8,2865.5-1405.9c226.3-32.6,982-32.6,1208.3,0c581.2,82.5,1060.7,228.2,1553.6,471.8c535.1,264.7,918.7,540.9,1327.3,953.2c404.7,408.5,657.9,767.2,918.7,1292.7c243.6,492.9,389.4,972.4,471.8,1553.6c32.6,226.3,32.6,982,0,1208.3c-161.1,1135.5-629.1,2086.8-1405.9,2865.5c-759.5,759.5-1687.8,1223.7-2779.2,1392.5C5448.8,5017.5,4775.6,5034.8,4530.1,5007.9z M5364.4,3782.4c742.3-76.7,1434.7-370.2,2004.3-847.8c1147-960.9,1595.8-2539.4,1129.7-3966.4c-90.1-272.4-301.1-696.2-464.2-930.2c-521.7-749.9-1264-1265.9-2134.7-1486.4C4200.2-3876.2,2445.2-3053.4,1683.8-1473c-629.1,1302.3-433.5,2846.3,500.6,3960.7C2959.3,3414.1,4169.5,3905.1,5364.4,3782.4z"/><path d="M4808.2,2543.3c-191.8-71-329.9-211-393.2-402.8c-26.8-82.5-28.8-218.7-24.9-1139.3l5.7-1045.3l48-95.9c59.5-122.8,174.5-237.8,297.3-297.3l95.9-47.9h1083.7h1083.7l95.9,47.9C7223-377.8,7338-262.7,7397.5-140c67.1,138.1,67.1,379.8,0,517.9C7338,500.7,7223,615.7,7100.2,675.2l-95.9,47.9l-694.3,5.7l-694.3,5.8l-5.8,694.3l-5.8,694.3l-47.9,95.9c-59.5,122.7-176.5,239.8-295.4,297.3C5138.1,2574,4931,2587.4,4808.2,2543.3z"/></g></g>
            </svg>
            <div class="alert-contents">
                <p>There are no upcoming events at the moment.</p>
                <p>Please check back later for updates.</p>
            </div>
        </div>
    </div>
    <?php
}