<?php

function listdo_listing_review_enable($post_id = null) {
    if ( empty($post_id) ) {
        $post_id = get_the_ID();
    }
    if ( ! comments_open($post_id) || !listdo_get_config('listing_review_enable', true) ) {
        return false;
    }
    return true;
}

function listdo_listing_review_rating_enable() {
    $return = false;
    if ( listdo_get_config('listing_review_enable', true) && listdo_get_config('listing_review_enable_rating', true) ) {
        $return = true;
    }
    return apply_filters('listdo_listing_review_rating_enable', $return);
}

function listdo_listing_review_rating_field($default_val = array()) {
    global $post;

    $html = '';
    ob_start();
    
    $rating_mode = listdo_get_config('listing_review_mode', 10);
    $categories = listdo_listing_review_categories();
    if ( listdo_listing_review_rating_enable() && $categories ) {
        ?>
        <div class="rating-wrapper row comment-form-rating">
        <?php
        foreach ($categories as $category) {
            $value = isset($default_val[$category['key']]) ? $default_val[$category['key']] : $rating_mode;
            ?>
            <div class="rating-inner col-xs-6 col-sm-6 col-md-4">
                <div class="comment-form-rating">
                    <span class="subtitle"><?php echo esc_html($category['title']); ?></span>
                    <ul class="review-stars">
                        <?php
                            if ( $rating_mode == 5 ) {
                                $datas = array('terrible', 'poor', 'average', 'very_good', 'excellent');
                            } else {
                                $datas = array('terrible', 'terrible', 'poor', 'poor', 'average', 'average', 'very_good', 'very_good', 'excellent', 'excellent');
                            }
                            $i = 1;
                            foreach ($datas as $title) {
                                ?>
                                <li data-key="<?php echo esc_attr($title); ?>"><span class="fas fa-star <?php echo esc_attr($i <= $value ? 'active' : ''); ?>"></span></li>
                                <?php
                                $i++;
                            }
                        ?>
                    </ul>
                    <span class="review-label hidden"></span>
                    <input type="hidden" value="<?php echo esc_attr($value); ?>" name="rating[<?php echo esc_html($category['key']); ?>]" class="rating">
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <?php
    }

    $html = ob_get_clean();
    return $html;
}

function listdo_listing_review_rating_field_display() {
    global $post;
    if ( $post->post_type == 'job_listing' ) {
        echo trim(listdo_listing_review_rating_field());
    }
}

function listdo_listing_review_displayUploadField() {
    global $post;
    if ( $post->post_type == 'job_listing' ) {
        echo trim(Listdo_Attachments::displayUploadField());
    }
}


function listdo_listing_review_categories() {
    $category_titles = listdo_get_config('listing_review_category_title');
    $category_keys = listdo_get_config('listing_review_category_key');
    $return = array();
    
    if ( !empty($category_titles) && is_array($category_titles) && !empty($category_keys) && is_array($category_keys) ) {
        foreach ($category_titles as $key => $value) {
            if ( $value && !empty($category_keys[$key]) ) {
                $return[$category_keys[$key]] = array( 'title' => $value, 'key' => $category_keys[$key] );
            }
        }
    }
    return $return;
}

// comment template
function listdo_listing_comments_template_loader($template) {
    if ( get_post_type() !== 'job_listing' ) {
        return $template;
    }
    return get_template_directory() . '/job_manager/single/parts/reviews.php';
}
add_filter( 'comments_template', 'listdo_listing_comments_template_loader' );

// comment list
function listdo_listing_comments( $comment, $args, $depth ) {
    if ( is_file(get_template_directory().'/job_manager/single/parts/review.php') ) {
        require get_template_directory().'/job_manager/single/parts/review.php';
    }
}
function listdo_my_review( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    set_query_var( 'comment', $comment );
    set_query_var( 'args', $args );
    set_query_var( 'depth', $depth );
    get_template_part( 'job_manager/my-review' );
}
// add comment meta
function listdo_add_custom_comment_field( $comment_id, $comment_approved, $commentdata ) {
    $post_id = $commentdata['comment_post_ID'];
    $post = get_post($post_id);
    if ( $post->post_type == 'job_listing' && isset($_POST['rating']) ) {
        update_comment_meta( $comment_id, '_apus_rating', $_POST['rating'] );
        $total = 0;
        foreach ($_POST['rating'] as $key => $value) {
            $total += intval($value);
        }
        $avg = round($total/count($_POST['rating']),2);
        update_comment_meta( $comment_id, '_apus_rating_avg', $avg );


        $average_rating = listdo_get_total_rating( $post_id );
        update_post_meta( $post_id, '_average_rating', $average_rating );

        $avg_ratings = listdo_get_total_ratings($post_id);
        update_post_meta( $post_id, '_average_ratings', $avg_ratings );
        
    }
}
add_action( 'comment_post', 'listdo_add_custom_comment_field', 10, 3 );


function listdo_add_custom_comment_average_rating($comment) {
    $post_id = $comment->comment_post_ID;
    $post = get_post($post_id);
    if ( $post->post_type == 'job_listing' ) {
        $average_rating = listdo_get_total_rating( $post_id );
        update_post_meta( $post_id, '_average_rating', $average_rating );

        $avg_ratings = listdo_get_total_ratings($post_id);
        update_post_meta( $post_id, '_average_ratings', $avg_ratings );
    }
}
add_action( 'comment_unapproved_to_approved', 'listdo_add_custom_comment_average_rating', 10 );
add_action( 'comment_approved_to_unapproved', 'listdo_add_custom_comment_average_rating', 10 );
add_action( 'comment_approved_to_trash', 'listdo_add_custom_comment_average_rating', 10 );
add_action( 'comment_trash_to_approved', 'listdo_add_custom_comment_average_rating', 10 );
add_action( 'comment_approved_to_spam', 'listdo_add_custom_comment_average_rating', 10 );
add_action( 'comment_spam_to_approved', 'listdo_add_custom_comment_average_rating', 10 );

function listdo_get_review_comments( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'status' => 'approve',
        'post_id' => '',
        'user_id' => '',
        'post_type' => 'job_listing',
        'number' => 0
    ));
    $cargs = array(
        'status' => $args['status'],
        'post_type' => $args['post_type'],
        'number' => $args['number'],
        'meta_query' => array(
            array(
               'key' => '_apus_rating',
               'value' => 0,
               'compare' => '>',
            )
        )
    );
    if ( !empty($args['post_id']) ) {
        $cargs['post_id'] = $args['post_id'];
    }
    if ( !empty($args['user_id']) ) {
        $cargs['user_id'] = $args['user_id'];
    }

    $comments = get_comments( $cargs );
    
    return $comments;
}

function listdo_get_total_reviews( $post_id ) {
    $args = array( 'post_id' => $post_id );
    $comments = listdo_get_review_comments($args);

    if (empty($comments)) {
        return 0;
    }
    
    return count($comments);
}

function listdo_get_total_rating( $post_id ) {
    $args = array( 'post_id' => $post_id );
    $comments = listdo_get_review_comments($args);
    if (empty($comments)) {
        return 0;
    }
    $total_review = 0;
    foreach ($comments as $comment) {
        $rating = get_comment_meta( $comment->comment_ID, '_apus_rating_avg', true );
        if ($rating) {
            $total_review += $rating;
        }
    }

    return round($total_review/count($comments),2);
}

function listdo_get_total_ratings( $post_id ) {
    $args = array( 'post_id' => $post_id );
    $comments = listdo_get_review_comments($args);
    if (empty($comments)) {
        return;
    }
    $reviews = array();
    foreach ($comments as $comment) {
        $ratings = get_comment_meta( $comment->comment_ID, '_apus_rating', true );

        if ( !empty($ratings) && is_array($ratings) ) {
            foreach ($ratings as $category => $value) {
                if ( isset($reviews[$category]) ) {
                    $reviews[$category] = $reviews[$category] + $value;
                } else {
                    $reviews[$category] = $value;
                }
            }
        }
    }
    if ( !empty($reviews) ) {
        foreach ($reviews as $category => $total) {
            $reviews[$category] = round($total/count($comments),2);
        }
    }
    
    return $reviews;
}

function listdo_get_total_rating_by_user( $user_id ) {
    $args = array( 'user_id' => $user_id );
    $comments = listdo_get_review_comments($args);

    if (empty($comments)) {
        return 0;
    }
    $total_review = 0;
    foreach ($comments as $comment) {
        $rating = get_comment_meta( $comment->comment_ID, '_apus_rating_avg', true );
        if ($rating) {
            $total_review += $rating;
        }
    }
    return $total_review/count($comments);
}


function listdo_get_detail_ratings( $post_id ) {
    global $wpdb;
    $comment_ratings = $wpdb->get_results( $wpdb->prepare(
        "
            SELECT cm2.meta_value AS rating, COUNT(*) AS quantity FROM $wpdb->posts AS p
            INNER JOIN $wpdb->comments AS c ON (p.ID = c.comment_post_ID AND c.comment_approved=1)
            INNER JOIN $wpdb->commentmeta AS cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
            WHERE p.ID=%d
            GROUP BY cm2.meta_value",
            '_apus_rating',
            $post_id
        ), OBJECT_K
    );
    return $comment_ratings;
}

function listdo_print_review( $rate, $type = '', $nb = 0 ) {
    $rating_mode = listdo_get_config('listing_review_mode', 10);
    $rating_mode_width = $rating_mode == 10 ? $rate * 10 : $rate * 20;
    ?>
    <div class="review-stars-rated-wrapper">
        <div class="review-stars-rated">
            <ul class="review-stars">
                <?php for ($i=0; $i < $rating_mode; $i++) { ?>
                    <li><span class="ti-star"></span></li>
                <?php } ?>
            </ul>
            
            <ul class="review-stars filled"  style="<?php echo esc_attr( 'width: ' . $rating_mode_width . '%' ) ?>" >
                <?php for ($i=0; $i < $rating_mode; $i++) { ?>
                    <li><span class="ti-star"></span></li>
                <?php } ?>
            </ul>
        </div>
        <?php if ($type == 'list') { ?>
            <span class="nb-review"><?php echo sprintf(_n('(%d Review)', '(%d Reviews)', $nb, 'listdo'), $nb); ?></span>
        <?php } ?>
    </div>
    <?php
}





// comment like
function listdo_check_comment_like_user($comment_id) {
    $meta_key = '_apus_like';
    $return = false;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_user_meta( $user_id, $meta_key, true );
        
        if ( !empty($data) && is_array($data) && !empty($data[$comment_id]) ) {
            $return = true;
        }
    } else {
        $data = isset($_COOKIE[$meta_key]) ? $_COOKIE[$meta_key] : '';
        if ( !empty($data) ) {
            $data = explode(',', $data);
            if ( in_array($comment_id, $data) ) {
                $return = true;
            }
        }
    }
    return $return;
}

function listdo_comment_like_user($comment_id) {
    $meta_key = '_apus_like';
    $return = true;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_user_meta( $user_id, $meta_key, true );
        
        if ( !empty($data) && is_array($data) && !empty($data[$comment_id]) ) {
            unset($data[$comment_id]);
            $return = false;
        } else {
            $data[$comment_id] = $comment_id;
        }
        update_user_meta( $user_id, $meta_key, $data );
    } else {
        $data = isset($_COOKIE[$meta_key]) ? $_COOKIE[$meta_key] : '';

        if ( !empty($data) ) {
            $data = explode(',', $data);
            
            if ( in_array($comment_id, $data) ) {
                $fdata = array_flip($data);
                unset($data[$fdata[$comment_id]]);
                $return = false;
            } else {
                $data[] = $comment_id;
            }
        } else {
            $data = array($comment_id);
        }

        setcookie( $meta_key, implode(',', $data), time() + 30*24*60*60, '/' );
        $_COOKIE[$meta_key] = implode(',', $data);
    }
    return $return;
}

function listdo_comment_like() {
    check_ajax_referer( 'listdo-ajax-nonce', 'security' );

    $comment_id = !empty($_POST['comment_id']) ? $_POST['comment_id'] : 0;
    $count_key = '_apus_like';
    $count = 0;
    if ( !empty($comment_id) ) {
        $user_like = listdo_comment_like_user($comment_id);
        $tract = -1;
        if ( $user_like ) {
            $tract = 1;
        }
        $count = intval(get_comment_meta( $comment_id, $count_key, true ));
        if ( empty($count) ) {
            $count = 0 + $tract;
            delete_comment_meta($comment_id, $count_key);
            add_comment_meta($comment_id, $count_key, 1);
        } else {
            $count = $count + $tract;
            $count = sanitize_text_field($count);
            update_comment_meta($comment_id, $count_key, $count);
        }
    }
    $return = array();
    $count = intval( get_comment_meta( $comment_id, '_apus_like', true ) );
    if ( $tract == -1) {
        $return = array(
            'icon' => '<i class="flaticon-like"></i> ',
            'dtitle' => esc_html__('Like', 'listdo').' '.$count,
        );
    } else {
        $return = array(
            'icon' => '<i class="flaticon-like"></i> ',
            'dtitle' => esc_html__('Liked', 'listdo').' '.$count,
        );
    }
    echo json_encode($return);
    die();
}
add_action( 'wp_ajax_listdo_comment_like', 'listdo_comment_like' );
add_action( 'wp_ajax_nopriv_listdo_comment_like', 'listdo_comment_like' );


// comment dislike
function listdo_check_comment_dislike_user($comment_id) {
    $meta_key = '_apus_dislike';
    $return = false;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_user_meta( $user_id, $meta_key, true );
        
        if ( !empty($data) && is_array($data) && !empty($data[$comment_id]) ) {
            $return = true;
        }
    } else {
        $data = isset($_COOKIE[$meta_key]) ? $_COOKIE[$meta_key] : '';
        if ( !empty($data) ) {
            $data = explode(',', $data);
            if ( in_array($comment_id, $data) ) {
                $return = true;
            }
        }
    }
    return $return;
}

function listdo_comment_dislike_user($comment_id) {
    $meta_key = '_apus_dislike';
    $return = true;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_user_meta( $user_id, $meta_key, true );
        
        if ( !empty($data) && is_array($data) && !empty($data[$comment_id]) ) {
            unset($data[$comment_id]);
            $return = false;
        } else {
            $data[$comment_id] = $comment_id;
        }
        update_user_meta( $user_id, $meta_key, $data );
    } else {
        $data = isset($_COOKIE[$meta_key]) ? $_COOKIE[$meta_key] : '';

        if ( !empty($data) ) {
            $data = explode(',', $data);
            
            if ( in_array($comment_id, $data) ) {
                $fdata = array_flip($data);
                unset($data[$fdata[$comment_id]]);
                $return = false;
            } else {
                $data[] = $comment_id;
            }
        } else {
            $data = array($comment_id);
        }

        setcookie( $meta_key, implode(',', $data), time() + 30*24*60*60, '/' );
        $_COOKIE[$meta_key] = implode(',', $data);
    }
    return $return;
}

function listdo_comment_dislike() {
    check_ajax_referer( 'listdo-ajax-nonce', 'security' );
    $comment_id = !empty($_POST['comment_id']) ? $_POST['comment_id'] : 0;
    $count_key = '_apus_dislike';
    $count = 0;
    if ( !empty($comment_id) ) {
        $user_dislike = listdo_comment_dislike_user($comment_id);
        $tract = -1;
        if ( $user_dislike ) {
            $tract = 1;
        }
        $count = intval(get_comment_meta( $comment_id, $count_key, true ));
        if ( empty($count) ) {
            $count = 0 + $tract;
            delete_comment_meta($comment_id, $count_key);
            add_comment_meta($comment_id, $count_key, 1);
        } else {
            $count = $count + $tract;
            $count = sanitize_text_field($count);
            update_comment_meta($comment_id, $count_key, $count);
        }
    }
    $return = array();
    $count = intval( get_comment_meta( $comment_id, '_apus_dislike', true ) );
    if ( $tract == -1) {
        $return = array(
            'icon' => '<i class="flaticon-disliken"></i>',
            'dtitle' => esc_html__('Dislike', 'listdo').' '.$count,
        );
    } else {
        $return = array(
            'icon' => '<i class="flaticon-dislike"></i>',
            'dtitle' => esc_html__('Disliked', 'listdo').' '.$count,
        );
    }
    echo json_encode($return);
    die();
}
add_action( 'wp_ajax_listdo_comment_dislike', 'listdo_comment_dislike' );
add_action( 'wp_ajax_nopriv_listdo_comment_dislike', 'listdo_comment_dislike' );


// comment love
function listdo_check_comment_love_user($comment_id) {
    $meta_key = '_apus_love';
    $return = false;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_user_meta( $user_id, $meta_key, true );
        
        if ( !empty($data) && is_array($data) && !empty($data[$comment_id]) ) {
            $return = true;
        }
    } else {
        $data = isset($_COOKIE[$meta_key]) ? $_COOKIE[$meta_key] : '';
        if ( !empty($data) ) {
            $data = explode(',', $data);
            if ( in_array($comment_id, $data) ) {
                $return = true;
            }
        }
    }
    return $return;
}

function listdo_comment_love_user($comment_id) {
    $meta_key = '_apus_love';
    $return = true;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_user_meta( $user_id, $meta_key, true );
        
        if ( !empty($data) && is_array($data) && !empty($data[$comment_id]) ) {
            unset($data[$comment_id]);
            $return = false;
        } else {
            $data[$comment_id] = $comment_id;
        }
        update_user_meta( $user_id, $meta_key, $data );
    } else {
        $data = isset($_COOKIE[$meta_key]) ? $_COOKIE[$meta_key] : '';

        if ( !empty($data) ) {
            $data = explode(',', $data);
            
            if ( in_array($comment_id, $data) ) {
                $fdata = array_flip($data);
                unset($data[$fdata[$comment_id]]);
                $return = false;
            } else {
                $data[] = $comment_id;
            }
        } else {
            $data = array($comment_id);
        }

        setcookie( $meta_key, implode(',', $data), time() + 30*24*60*60, '/' );
        $_COOKIE[$meta_key] = implode(',', $data);
    }
    return $return;
}

function listdo_comment_love() {
    check_ajax_referer( 'listdo-ajax-nonce', 'security' );
    $comment_id = !empty($_POST['comment_id']) ? $_POST['comment_id'] : 0;
    $count_key = '_apus_love';
    $count = 0;
    if ( !empty($comment_id) ) {
        $user_love = listdo_comment_love_user($comment_id);
        $tract = -1;
        if ( $user_love ) {
            $tract = 1;
        }
        $count = intval(get_comment_meta( $comment_id, $count_key, true ));
        if ( empty($count) ) {
            $count = 0 + $tract;
            delete_comment_meta($comment_id, $count_key);
            add_comment_meta($comment_id, $count_key, 1);
        } else {
            $count = $count + $tract;
            $count = sanitize_text_field($count);
            update_comment_meta($comment_id, $count_key, $count);
        }
    }
    $return = array();
    $count = intval( get_comment_meta( $comment_id, '_apus_love', true ) );
    if ( $tract == -1) {
        $return = array(
            'icon' => '<i class="flaticon-heart"></i>',
            'dtitle' => esc_html__('Love', 'listdo').' '.$count,
        );
    } else {
        $return = array(
            'icon' => '<i class="flaticon-heart"></i>',
            'dtitle' => esc_html__('Loved', 'listdo').' '.$count,
        );
    }
    echo json_encode($return);
    die();
}
add_action( 'wp_ajax_listdo_comment_love', 'listdo_comment_love' );
add_action( 'wp_ajax_nopriv_listdo_comment_love', 'listdo_comment_love' );


function listdo_paginate_links( $args = array() ) {
    global $wp_rewrite;

    $defaults = array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'total' => 1,
        'current' => 1,
        'add_fragment' => '#comments',
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
        'type'      => 'list',
        'end_size'  => 3,
        'mid_size'  => 3
    );
    if ( $wp_rewrite->using_permalinks() )
        $defaults['base'] = user_trailingslashit(trailingslashit(get_permalink()) . $wp_rewrite->comments_pagination_base . '-%#%', 'commentpaged');

    $args = wp_parse_args( $args, $defaults );
    ?>
    <nav class="manager-pagination apus-pagination">
        <?php echo paginate_links( $args ); ?>
    </nav>
    <?php
}

function listdo_check_is_review_owner($comment) {
    if (current_user_can('edit_users')) {
        return true;
    } elseif( current_user_can( 'edit_page', $comment->comment_post_ID) || current_user_can( 'edit_post', $comment->comment_post_ID)) {
        return true;
    } elseif( current_user_can( 'moderate_comments' ) ) {
        return true;
    } elseif ( get_current_user_id() == $comment->user_id ) {
        return true;
    }
    return false;
}

add_action( 'wp_ajax_listdo_comment_edit', 'listdo_comment_edit' );
add_action( 'wp_ajax_nopriv_listdo_comment_edit', 'listdo_comment_edit' );
function listdo_comment_edit() {
    check_ajax_referer( 'listdo-ajax-nonce', 'security' );

    get_template_part('job_manager/single/parts/edit-review-form');

    exit();
}

add_action( 'wp_ajax_listdo_process_comment_edit', 'listdo_process_comment_edit' );
add_action( 'wp_ajax_nopriv_listdo_process_comment_edit', 'listdo_process_comment_edit' );
function listdo_process_comment_edit() {

    check_ajax_referer( 'edit-review', 'edit-review-nonce' );

    if ( !is_user_logged_in() ) {
        $return = array( 'status' => false, 'msg' => esc_html__('Please login to edit this review.', 'listdo') );
        echo wp_json_encode($return);
        exit;
    }
    if ( empty($_POST['comment']) ) {
        $return = array( 'status' => false, 'msg' => esc_html__('Please enter review comment.', 'listdo') );
        echo wp_json_encode($return);
        exit;
    }
    $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : '';
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
    if ( !$comment_id || !$post_id ) {
        $return = array( 'status' => false, 'msg' => esc_html__('Review or Post is not exists.', 'listdo') );
        echo wp_json_encode($return);
        exit;
    }

    $comment = get_comment($comment_id, ARRAY_A);
    if ( empty($comment['comment_post_ID']) || $comment['comment_post_ID'] != $post_id ) {
        $return = array( 'status' => false, 'msg' => esc_html__('Review is not exists.', 'listdo') );
        echo wp_json_encode($return);
        exit;
    }

    $comment_args = array(
        'comment_content' => wp_kses( $_POST['comment'], '<b><strong><i><em><code><span>' ),
        'comment_ID' => (int)$comment_id
    );

    $result = wp_update_comment($comment_args);
    

        listdo_add_custom_comment_field( $comment_id, true, $comment );

        $comment_data = get_comment($comment_id, ARRAY_A);
        $comment_data['comment_text'] = get_comment_text($comment_id);
        $comment_data['rating_ouput'] = '';
        $rating = get_comment_meta( $comment_id, '_apus_rating_avg', true );
        if ( $rating > 0 ) {
            $rating_mode = listdo_get_config('listing_review_mode', 10);
            ob_start();
            listdo_display_listing_review_html($rating, $rating_mode);
            $review_html = ob_get_clean();
            $comment_data['rating_ouput'] = '<div class="star-rating " title="'.sprintf( esc_attr__( 'Rated %d out of %d', 'listdo' ), $rating, $rating_mode ).'">'.$review_html.'</div>';
        }

        $return = array( 'status' => true, 'msg' => esc_html__('Update review successful.', 'listdo'), 'comment_data' => $comment_data );
        echo wp_json_encode($return);
        exit;
    
}