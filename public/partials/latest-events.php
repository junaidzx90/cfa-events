<?php
$latestEvents = array();
$args2 = array(
    'post_type' => 'events',
    'post_status' => 'publish',
    'numberposts' => 3,
    'orderby' => 'date',
    'order' => 'DESC'
);

$events = get_posts( $args2 );
if($events){
    foreach ($events as $event) {
        $event_id = $event->ID;
        $event_title = $event->post_title;
        $event_date = get_post_meta($event_id, '__event_date', true);
        $event_date = date("j F, Y", strtotime($event_date));
        $location = get_post_meta($event_id, '__event_location', true);
        $thumbnail = ((get_the_post_thumbnail_url( $event_id )) ? get_the_post_thumbnail_url( $event_id ) : get_option('cfa_fallback_thumb') );
        $excerpt = wp_trim_words($event->post_content, 30);
        $permalink = get_the_permalink( $event->ID );

        $eventArr = array(
            'event_id' => $event_id,
            'title' => $event_title,
            'thumbnail' => $thumbnail,
            'date' => $event_date,
            'location' => $location,
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
                        <p class="event__date"><?php echo $event['date'] ?> | <?php echo $event['location'] ?></p>
                        <p class="event_excerpt"><?php echo $event['excerpt'] ?></p>
                    </div>

                    <div class="seemore_btn_box">
                        <a target="_blank" href="<?php echo $event['permalink'] ?>" class="button-seemore cfa_btn">Read more</a>
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
    echo '<div class="cfa_alert">No events are found.</div>';
}