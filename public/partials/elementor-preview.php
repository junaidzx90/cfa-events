<?php
$previousEvents = array();
$args2 = array(
    'post_type' => 'events',
    'post_status' => 'publish',
    'numberposts' => 12,
    'meta_key' => '__event_date',
    'orderby' => 'meta_value',
    'meta_type' => 'DATE',
    'order' => 'ASC'
);

$events = get_posts( $args2 );
if($events){
    foreach ($events as $event) {
        $event_id = $event->ID;
        $event_title = $event->post_title;
        $event_date = get_post_meta($event_id, '__event_date', true);
        if($event_date){
            $event_date = date("j F, Y", strtotime($event_date));
        }
        $location = get_post_meta($event_id, '__event_location', true);
        $thumbnail = ((get_the_post_thumbnail_url( $event_id )) ? get_the_post_thumbnail_url( $event_id ) : get_option('cfa_fallback_thumb') );
        $len = ((get_option('excerpt_length')) ? get_option('excerpt_length') : 10);
        $excerpt = wp_trim_words(get_the_excerpt( $event_id ), $len);
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

        $previousEvents[] = $eventArr;
    }
}
?>

<div id="cfa_events">
    <!-- Previos events -->
    <div class="previous_events">
        <div class="years_filter">
            <div class="years_btns">
                <?php
                echo '<span class="yrbtn year cfaActive">All</span>';

                $args = array(
                    'post_type' => 'events',
                    'post_status' => 'publish',
                    'numberposts' => -1
                );
                
                $years = array();
                $events2 = get_posts($args);

                if($events2){
                    foreach($events2 as $event2){
                        $date = get_post_meta($event2->ID, '__event_date', true);
                        $date = date("j F, Y", strtotime($date));

                        $year = explode(', ', $date)[1];
                        $years[$year] = $year;
                    }
                }

                asort($years);

                if(sizeof($years)){
                    foreach($years as $year){
                        echo '<span @click="cfa_year_filter(event, \''.$year.'\')" class="yrbtn year">'.$year.'</span>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="events_wrapper">
            <?php
            if(sizeof($previousEvents) > 0){
                foreach($previousEvents as $event){
                    ?>
                    <!-- Event Card -->
                    <div class="event_card">
                        <div class="event_thumbnail">
                            <img src="<?php echo $event['thumbnail'] ?>" alt="thumbnail">
                        </div>

                        <div class="event_contents">
                            <h4 class="event__title"><?php echo $event['title'] ?></h4>
                            <p class="event__date"><?php echo $event['date'] ?> <?php echo (($event['date'] !== "" && $event['location'] !== "") ? '|': '') ?> <?php echo $event['location'] ?></p>
                            <p class="event_excerpt"><?php echo $event['excerpt'] ?></p>
                        </div>

                        <div class="seemore_btn_box">
                            <a target="_blank" href="<?php echo $event['permalink'] ?>" class="button-seemore cfa_btn">Read more</a>
                        </div>
                    </div>
                    <!-- // Event Card -->
                    <?php
                }
            }else{
                echo '<div class="cfa_alert">No events are found.</div>';
            }
            ?>
        </div>
    </div> <!-- //Previos events -->
</div>