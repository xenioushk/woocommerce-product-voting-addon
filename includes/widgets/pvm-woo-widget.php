<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Custom Widget to Display WooCommerce Products

function get_pvm_woo_icon_data()
{

    $pvm_data = get_option('bwl_pvm_options');

    // Add Custom Icon For Like Button

    if (isset($pvm_data['pvm_like_thumb_icon']) && $pvm_data['pvm_like_thumb_icon'] != "") {

        $pvm_like_thumb_icon = $pvm_data['pvm_like_thumb_icon'];

        $pvm_like_thumb_html = '<i class="fa ' . $pvm_like_thumb_icon . ' icon_like_color"></i>';
    } else {

        $pvm_like_thumb_icon = "fa-thumbs-o-up";

        $pvm_like_thumb_html = '<i class="fa ' . $pvm_like_thumb_icon . ' icon_like_color"></i>';
    }

    // Add Custom Icon For Dislike Button

    if (isset($pvm_data['pvm_dislike_thumb_icon']) && $pvm_data['pvm_dislike_thumb_icon'] != "") {

        $pvm_dislike_thumb_icon = $pvm_data['pvm_dislike_thumb_icon'];

        $pvm_dislike_thumb_html = '<i class="fa ' . $pvm_dislike_thumb_icon . ' icon_dislike_color"></i>';
    } else {

        $pvm_dislike_thumb_icon = "fa-thumbs-o-down";

        $pvm_dislike_thumb_html = '<i class="fa ' . $pvm_dislike_thumb_icon . ' icon_dislike_color"></i>';
    }


    return array(
        'pvm_like_thumb_html' => $pvm_like_thumb_html,
        'pvm_dislike_thumb_html' => $pvm_dislike_thumb_html
    );
}

function pvm_woo_filter_data($interval, $vote_type, $order, $limit)
{

    global $wpdb;

    $post_type = 'product';

    if ($interval == 'custom') {

        // Custom Date Range.
        //            bpvm_change_date_format($custom_date);
        $query = "SELECT postid, SUM(`total_votes`) AS tv FROM `" . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                `vote_date`>='{$vis}' AND `vote_date`<='{$vie}' GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
    } else if ($interval == 'count_all') {

        $vis = '2012-01-01'; // Start Date.
        $vie = date("Y-m-d"); // Current Date.
        // Custom Date Range.
        //            bpvm_change_date_format($custom_date);
        $query = "SELECT postid, SUM(`total_votes`) AS tv FROM `" . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                `vote_date`>='{$vis}' AND `vote_date`<='{$vie}' GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
    } else if ($interval == '6_month') {

        // 6 Month interval from current date.

        $query = "SELECT postid, SUM(`total_votes`) AS tv FROM `" . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 6 month AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
    } else if ($interval == '1_month') {

        // 1 Month interval from current date.

        $query = "SELECT postid, SUM(`total_votes`) AS tv FROM `" . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 1 month AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
    } else if ($interval == '1_day') {

        // 1 day interval from current date.

        $query = "SELECT postid, SUM(`total_votes`) AS tv FROM `" . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 1 day AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
    } else {

        // // 1 Week  interval from current date. ( Default )

        $query = "SELECT postid, SUM(`total_votes`) AS tv FROM `" . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 1 week AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
    }

    $query_results = $wpdb->get_results($query);


    return $query_results;
}

function bpvm_get_woo_product_data()
{

    $interval = $_POST['interval'];
    $vote_type = $_POST['vote_type']; // $vote_type == $bwl_pvm_order_type
    $order = $_POST['order'];
    $limit = $_POST['limit'];


    $query_results = pvm_woo_filter_data($interval, $vote_type, $order, $limit);

    if (count($query_results) > 0) :

        $pvm_woo_icon_data = get_pvm_woo_icon_data();

        $pvm_like_thumb_html = $pvm_woo_icon_data['pvm_like_thumb_html'];
        $pvm_dislike_thumb_html = $pvm_woo_icon_data['pvm_dislike_thumb_html'];

        $pvm_voted_post_string = '';

        foreach ($query_results as $bpvm_filter_post_info) {

            $bpvm_post_id = $bpvm_filter_post_info->postid;

            $bpvm_post_total_votes = $bpvm_filter_post_info->tv;

            $bpvm_product_title = get_the_title($bpvm_post_id);

            $bpvm_product_link = get_permalink($bpvm_post_id);

            $post_thumb = '';

            if (has_post_thumbnail($bpvm_post_id) && $thumb == 1) {

                //                    $post_thumb = get_the_post_thumbnail($bpvm_post_id, 'pvm-post-thumb');

            }

            $bwl_pvm_like_vote_string = "";

            $bwl_pvm_dislike_vote_string = "";

            if ($vote_type == '2') {

                $bwl_pvm_dislike_vote_string = $pvm_dislike_thumb_html . ' ' . $bpvm_post_total_votes . ' &nbsp; ';

                $pvm_voted_post_string .= "<li><a href='" . $bpvm_product_link . "'>" . $post_thumb . $bpvm_product_title . '</a><br />' . $bwl_pvm_dislike_vote_string . $bwl_pvm_like_vote_string . "</li>";
            } else {

                $bwl_pvm_like_vote_string = $pvm_like_thumb_html . ' ' . $bpvm_post_total_votes . ' &nbsp; ';

                $pvm_voted_post_string .= "<li><a href='" . $bpvm_product_link . "'>" . $post_thumb . $bpvm_product_title . '</a><br />' . $bwl_pvm_like_vote_string . $bwl_pvm_dislike_vote_string . "</li>";
            }
        }

        $pvm_voted_post_string .= '';

    else :

        $pvm_voted_post_string = "<p>" . __("Sorry, No Product Found!", 'bpvm_wpva') . "</p>";

    endif;

    wp_reset_query();

    echo $pvm_voted_post_string;

    die();
}

add_action('wp_ajax_bpvm_get_woo_product_data', 'bpvm_get_woo_product_data');

add_action('wp_ajax_nopriv_bpvm_get_woo_product_data', 'bpvm_get_woo_product_data');


// Start Widget Code

class Pvm_Woo_Widget extends WP_Widget
{

    public function __construct()
    {

        parent::__construct(
            'pvm_woo_widget',
            __('WooCommerce Voting Widget', 'bpvm_wpva'),
            array(
                'classname'     =>  'Pvm_Woo_Widget',
                'description'    =>   __('Display Top Up/Down Voted Products In sidebar area', 'bpvm_wpva')
            )
        );
    }

    public function form($instance)
    {

        $defaults = array(
            'title'                              =>  __('Top Liked Products', 'bpvm_wpva'),
            'bwl_pvm_filter_type'    =>  '1_week',
            'bwl_pvm_display_front_filter'    =>  'on',
            'bwl_pvm_order_type'     => 'liked',
            'bwl_pvm_no_of_post'    =>  '5'
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        extract($instance);

?>


<p>
  <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title', 'bpvm_wpva'); ?></label>
  <input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>"
    name="<?php echo $this->get_field_name('title') ?>" value="<?php echo esc_attr($title) ?>" />
</p>

<!-- Product Filter Type -->

<p>
  <label
    for="<?php echo $this->get_field_id('bwl_pvm_filter_type'); ?>"><?php _e('Filter Type:', 'bpvm_wpva') ?></label>
  <select id="<?php echo $this->get_field_id('bwl_pvm_filter_type'); ?>"
    name="<?php echo $this->get_field_name('bwl_pvm_filter_type'); ?>" class="widefat" style="width:100%;">
    <option value="1_day" <?php if ($instance['bwl_pvm_filter_type'] == '1_day') echo 'selected="selected"'; ?>>
      <?php _e('Last 1 Day', 'bpvm_wpva'); ?></option>
    <option value="1_week" <?php if ($instance['bwl_pvm_filter_type'] == '1_week') echo 'selected="selected"'; ?>>
      <?php _e('Last 1 Week', 'bpvm_wpva'); ?> ( Default )</option>
    <option value="1_month" <?php if ($instance['bwl_pvm_filter_type'] == '1_month') echo 'selected="selected"'; ?>>
      <?php _e('Last 1 Month', 'bpvm_wpva'); ?></option>
    <option value="6_month" <?php if ($instance['bwl_pvm_filter_type'] == '6_month') echo 'selected="selected"'; ?>>
      <?php _e('Last 6 Month', 'bpvm_wpva'); ?></option>
    <option value="count_all" <?php if ($instance['bwl_pvm_filter_type'] == 'count_all') echo 'selected="selected"'; ?>>
      <?php _e('Count All', 'bpvm_wpva'); ?></option>
  </select>
</p>

<!-- Display Front End Filter  -->
<p>
  <label
    for="<?php echo $this->get_field_id('bwl_pvm_display_front_filter'); ?>"><?php _e('Display Frontend Voting Filter', 'bpvm_wpva'); ?>:
  </label>
  <input id="<?php echo $this->get_field_id('bwl_pvm_display_front_filter'); ?>"
    name="<?php echo $this->get_field_name('bwl_pvm_display_front_filter'); ?>" type="checkbox"
    <?php checked($bwl_pvm_display_front_filter, 'on'); ?> />
</p>

<!-- Order Type -->

<p>
  <label for="<?php echo $this->get_field_id('bwl_pvm_order_type'); ?>"><?php _e('Order Type:', 'bpvm_wpva') ?></label>
  <select id="<?php echo $this->get_field_id('bwl_pvm_order_type'); ?>"
    name="<?php echo $this->get_field_name('bwl_pvm_order_type'); ?>" class="widefat" style="width:100%;">
    <option value="liked" <?php if ($instance['bwl_pvm_order_type'] == 'liked') echo 'selected="selected"'; ?>>
      <?php _e('Liked', 'bpvm_wpva'); ?></option>
    <option value="disliked" <?php if ($instance['bwl_pvm_order_type'] == 'disliked') echo 'selected="selected"'; ?>>
      <?php _e('Disliked', 'bpvm_wpva'); ?></option>
  </select>
</p>

<!-- Display No of Posts  -->
<p>
  <label for="<?php echo $this->get_field_id('bwl_pvm_no_of_post') ?>"><?php _e('No Of Posts', 'bpvm_wpva'); ?></label>
  <input type="text" class="widefat" id="<?php echo $this->get_field_id('bwl_pvm_no_of_post') ?>"
    name="<?php echo $this->get_field_name('bwl_pvm_no_of_post') ?>"
    value="<?php echo esc_attr($bwl_pvm_no_of_post) ?>" />
</p>

<?php

    }

    public function update($new_instance, $old_instance)
    {

        $instance                                    = $old_instance;

        $instance['title']                           = strip_tags(stripslashes($new_instance['title']));

        $instance['bwl_pvm_filter_type']  =  strip_tags(stripslashes($new_instance['bwl_pvm_filter_type']));

        $instance['bwl_pvm_display_front_filter']  =  strip_tags(stripslashes($new_instance['bwl_pvm_display_front_filter']));

        $instance['bwl_pvm_order_type']  =  strip_tags(stripslashes($new_instance['bwl_pvm_order_type']));

        $instance['bwl_pvm_no_of_post']  =  strip_tags(stripslashes($new_instance['bwl_pvm_no_of_post']));

        return $instance;
    }

    public function widget($args, $instance)
    {

        extract($args);

        $title                      = apply_filters('widget-title', $instance['title']);

        $bwl_pvm_post_type = 'product';

        $bwl_pvm_filter_type = $instance['bwl_pvm_filter_type'];

        $bwl_pvm_display_front_filter = isset($instance['bwl_pvm_display_front_filter']) ?  $instance['bwl_pvm_display_front_filter'] : "on";

        $bwl_pvm_order_type = $instance['bwl_pvm_order_type'];

        $bwl_pvm_no_of_post = $instance['bwl_pvm_no_of_post'];

        echo $before_widget;

        if ($title) :

            echo $before_title . $title . $after_title;

        endif;

        //Start.

        global $wpdb;

        // @Vote type keys: 1=like, 2=dislike.
        // @Introduced in version : 1.1.0

        if ($bwl_pvm_order_type == "disliked") {

            $vote_type = 2;
        } else {

            $vote_type = 1;
        }

        // @Interval Ranges Script
        // @Introduced in version: 1.1.0
        $interval = $bwl_pvm_filter_type;
        $post_type = $bwl_pvm_post_type;
        //        $vote_type = $bwl_pvm_order_type;
        $order = 'DESC';
        $limit = $bwl_pvm_no_of_post;

        $query_results = pvm_woo_filter_data($interval, $vote_type, $order, $limit);

        //        echo $wpdb->last_query;
        $thumb = 0;

        $woo_pvm_widget_id = wp_rand();



        if (count($query_results) > 0) :

            $front_end_filter_html = "";

            if ($bwl_pvm_display_front_filter == "on") {

                $front_end_filter_html .= '<p class="pvm-front-end-filter-container"><select name="pvm_front_end_filter" class="pvm_front_end_filter" data-filter_id="' . $woo_pvm_widget_id . '" data-vote_type="' . $vote_type . '" data-order="' . $order . '" data-limit="' . $limit . '">
                    <option value="1_week">' . __('Result Filter', 'bpvm_wpva') . '</option>
                    <option value="1_day">' . __('Last 1 Day', 'bpvm_wpva') . '</option>
                    <option value="1_week">' . __('Last 1 Week', 'bpvm_wpva') . '</option>                          
                    <option value="1_month">' . __('Last 1 Month', 'bpvm_wpva') . '</option>                         
                    <option value="6_month">' . __('Last 6 Month', 'bpvm_wpva') . '</option>           
                    <option value="count_all">' . __('Count All', 'bpvm_wpva') . '</option>
                </select></p>';
            }
            // Get Icon data Infomration.

            $pvm_woo_icon_data = get_pvm_woo_icon_data();

            $pvm_like_thumb_html = $pvm_woo_icon_data['pvm_like_thumb_html'];
            $pvm_dislike_thumb_html = $pvm_woo_icon_data['pvm_dislike_thumb_html'];

            $pvm_voted_post_string = $front_end_filter_html . '<ul class="bpvm-widget" id="rw_' . $woo_pvm_widget_id . '">';

            foreach ($query_results as $bpvm_filter_post_info) {

                $bpvm_post_id = $bpvm_filter_post_info->postid;

                $bpvm_post_total_votes = $bpvm_filter_post_info->tv;

                $bpvm_product_title = get_the_title($bpvm_post_id);

                $bpvm_product_link = get_permalink($bpvm_post_id);

                $post_thumb = '';

                if ($thumb == 1) {

                    $post_thumb = get_the_post_thumbnail($bpvm_post_id, 'pvm-post-thumb');

                    if (empty($post_thumb)) {

                        $post_thumb = '<img src="' . get_site_url() . 'wp-content/plugins/woocommerce/assets/images/placeholder.png" alt="" />';
                    }
                }

                $bwl_pvm_like_vote_string = "";

                $bwl_pvm_dislike_vote_string = "";

                if ($bwl_pvm_order_type == 'disliked') {

                    $bwl_pvm_dislike_vote_string = $pvm_dislike_thumb_html . ' ' . $bpvm_post_total_votes . ' &nbsp; ';

                    $pvm_voted_post_string .= "<li><a href='" . $bpvm_product_link . "'>" . $post_thumb . $bpvm_product_title . '</a><br />' . $bwl_pvm_dislike_vote_string . $bwl_pvm_like_vote_string . "</li>";
                } else {

                    $bwl_pvm_like_vote_string = $pvm_like_thumb_html . ' ' . $bpvm_post_total_votes . ' &nbsp; ';

                    $pvm_voted_post_string .= "<li><a href='" . $bpvm_product_link . "'>" . $post_thumb . $bpvm_product_title . '</a><br />' . $bwl_pvm_like_vote_string . $bwl_pvm_dislike_vote_string . "</li>";
                }
            }

            $pvm_voted_post_string .= '</ul>';

        else :

            $pvm_voted_post_string = "<p>" . __("Sorry, No Product Found!", 'bpvm_wpva') . "</p>";

        endif;

        wp_reset_query();

        // End.
        echo $pvm_voted_post_string;

        echo $after_widget;
    }
}


/*------------------------------  Widget Initialization ---------------------------------*/

function pvm_woo_widget_init()
{

    register_widget('Pvm_Woo_Widget');
}

add_action('widgets_init', 'pvm_woo_widget_init');