<div id="job-manager-job-dashboard myoffers">
    <div class="job-manager-jobs clearfix">
        <?php if (!$myoffers) : ?>
            <div class="text-warning">
                <?php esc_html_e('You do not have any Offer.', 'listdo'); ?>
            </div>
        <?php else : ?>
            <div class="box-list">
                <h3 class="title"><i class="flaticon-list"></i><?php esc_html_e('My Offers', 'listdo'); ?></h3>
                <div class="clearfix">
                    <?php foreach ($myoffers as $myoffer) {
                        $job = get_post($myoffer->listing_id)
                    ?>
                        <div class="my-listing-item-wrapper job_listing" style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #f1f3f7;">
                            <div class="row flex-middle-sm">
                                <div class="col-md-6 col-sm-9 col-xs-12">
                                    <div class="flex-middle">
                                        <?php
                                        if (has_post_thumbnail($job->ID)) {
                                        ?>
                                            <div class="listing-image" style="padding-right: 0; margin-right:15px">
                                                <div class="listing-image-inner">
                                                    <?php
                                                    $linkable = false;
                                                    if ($job->post_status == 'publish') {
                                                        $linkable = true;
                                                    }
                                                    listdo_display_listing_cover_image('listdo-image-mylisting', $linkable, $job);
                                                    ?>

                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="listing-content">
                                            <?php listdo_display_listing_review($job); ?>
                                            <h3 class="listing-title">
                                                <?php if ($job->post_status == 'publish') : ?>
                                                    <a href="<?php echo get_permalink($job->ID); ?>"><?php echo trim($job->post_title); ?></a>
                                                <?php else : ?>
                                                    <?php echo trim($job->post_title); ?>
                                                <?php endif; ?>
                                            </h3>
                                            <?php listdo_listing_tagline($job); ?>
                                            <div class="meta-listing">
                                                <?php listdo_display_listing_first_category($job); ?>
                                                <?php listdo_display_listing_location($job); ?>
                                                <?php listdo_display_listing_phone($job); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12 ali-right">
                                    <div class="right-inner">
                                        <?php if ($myoffer->price > 0) {
                                            echo '<h4>Offer Price: $ ' . $myoffer->price . '</h4>';
                                        } else {
                                            $price_from = get_post_meta($job->ID, '_job_price_from', true);
                                            $price_to = get_post_meta($job->ID, '_job_price_to', true);
                                            echo '<h4>Price: $ ' . $price_from . ' - $'. $price_to . '</h4>';
                                        }
                                        ?>
                                        <h4>Status: <?= $myoffer->status; ?></h4><br>
                                        <div class="listing-status">
                                            <?php if ($myoffer->author_id == get_current_user_id() && $myoffer->status == 'Pending') { ?>
                                                <button onclick="meetingStatus(this, <?= $myoffer->id; ?>, 'Accept')" class="btn btn-sm bg-success">Accept</button>
                                                <button onclick="meetingStatus(this, <?= $myoffer->id; ?>, 'Decline')" class="btn btn-sm btn-danger">Decline</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php //get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); 
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>