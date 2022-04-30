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
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
            <metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
            <g><path d="M500,10C229.4,10,10,229.4,10,500s219.4,490,490,490c270.6,0,490-219.4,490-490C990,229.4,770.6,10,500,10z M581.7,799.4c0,26.7-40.3,27.3-40.3,27.3h-80.9c0,0-40.4-2.6-40.4-27.3V444.8c0-26.4,40.4-27.3,40.4-27.3h80.9c0,0,40.3,1.8,40.3,27.3V799.4z M581.7,294.1c0,0-0.3,40.2-40.3,40.2c-70,0-80.9,0-80.9,0s-40.4-1.4-40.4-40.2c0-67.1,0-80.5,0-80.5s-1.7-40.2,40.4-40.2c66.8,0,80.9,0,80.9,0s40.3,0.9,40.3,40.2C581.7,280,581.7,294.1,581.7,294.1z"/></g>
            </svg>
            <div class="alert-contents">
                <p>There are no upcoming events at the moment.</p>
                <p>Please check back later for updates.</p>
            </div>
        </div>
    </div>
    <?php
}