<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.example.com/unknown
 * @since      1.0.0
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/admin/partials/registrants-table
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="registrants_list">
    <?php
    global $wpdb;
    $event_id = $post->ID;
    $registrants = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cfa_registrants WHERE event_id = $event_id");
    ?>

    <div class="registrants_head">
        <a href="?post=<?php echo $event_id ?>&action=edit&cfaction=export" class="button-secondary">Export</a>
        <p class="registrans_counts"><strong>Registrants:</strong> <?php echo sizeof($registrants) ?></p>
    </div>

    <div class="registrantsTable">
        <table>
            <thead>
                <tr>
                    <th>*</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($registrants){
                    $i = 1;
                    foreach($registrants as $registrant){
                        ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $registrant->name ?></td>
                            <td><?php echo $registrant->email ?></td>
                            <td><?php echo $registrant->phone ?></td>
                            <td><?php echo $registrant->company ?></td>
                            <td><?php echo date("F j, Y", strtotime($registrant->created)) ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }else{
                    echo '<tr><td>No registrants found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>