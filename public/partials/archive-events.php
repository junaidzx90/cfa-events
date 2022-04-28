<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.example.com/unknown
 * @since      1.0.0
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="cfa_events">
    <!-- Previos events -->
    <div class="previous_events">
        <div class="years_filter">
            <div class="years_btns">
                <?php
                echo '<span @click="cfa_year_filter(event, \'all\')" class="yrbtn year cfaActive">All</span>';

                $args = array(
                    'post_type' => 'events',
                    'post_status' => 'publish',
                    'numberposts' => -1
                );
                
                $years = array();
                $events = get_posts($args);

                if($events){
                    foreach($events as $event){
                        $date = get_post_meta($event->ID, '__event_date', true);
                        if($date){
                            $date = date("j F, Y", strtotime($date));

                            $year = explode(', ', $date)[1];
                            $years[$year] = $year;
                        }
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
            <!-- Event Card -->
            <div v-for="event in previousEvents" :key="event.id" class="event_card">
                <div class="event_thumbnail">
                    <img :src="event.thumbnail" alt="thumbnail">
                </div>

                <div class="event_contents">
                    <h4 class="event__title">{{event.title}}</h4>
                    <p class="event__date">{{event.date}} <span v-show="event.venue.length > 0 && event.date.length > 0">|</span> {{event.venue}}</p>
                    <p class="event_excerpt" v-html="event.excerpt"></p>
                </div>

                <div class="seemore_btn_box">
                    <a :href="event.permalink" class="button-seemore cfa_btn">Read More</a>
                </div>
            </div>
            <!-- // Event Card -->
            <div v-if="previousEvents.length === 0" class="cfa_alert">No events are found.</div>
        </div>
        
        <div v-if="max_pages > currentPage" class="loadmore_box">
            <button @click="loadmore_events()" class="loadmore_events">Load More</button>
        </div>
    </div> <!-- //Previos events -->

    <div v-if="isDisabled" class="cfaLoader">
        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
            <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
            s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
            c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
            <path fill="<?php echo ((get_option('cfa_selected_color')) ? get_option('cfa_selected_color') : '#8FD9F9') ?>" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
            C22.32,8.481,24.301,9.057,26.013,10.047z">
            <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.9s" repeatCount="indefinite"></animateTransform>
            </path>
        </svg>
    </div>
</div>