<?php defined('ABSPATH') || exit;

class jk_wp_post_likes
{

    public static function the_like_button($id, $user_id)
    {

        $is_user_logged_in = is_user_logged_in();

        $classes = array();

        if ($is_user_logged_in):

            $active = false;

            $likes = get_option('likes_user_' . $user_id);

            if (empty($likes)):

                $likes = array();

            endif;

            if (in_array($id, $likes)):

                $active = true;

            endif;

            if ($active):

                array_push($classes, 'like-active');

            endif;

        else:

            array_push($classes, 'login-modal-trigger');

            array_push($classes, 'not-authorize');

        endif;

        ?>

        <!-- Bookmark button -->
        <div class="like-button <?php echo esc_attr(implode(' ', $classes)); ?>"
             data-post-id="<?php echo esc_attr($id); ?>">

            <!-- Bookmark icon -->
            <i class="far fa-heart"></i>

        </div>

        <?php
    }

    public function ajax_handler()
    {

        $post_id = $_POST['post_id'];

        $user_id = $_POST['user_id'];

        $active = $_POST['active'];

        $likes = get_option('likes_user_' . $user_id);

        if (empty($likes)):

            $likes = array();

        endif;

        if (!in_array($post_id, $likes) && empty($active)):

            array_push($likes, $post_id);

            update_option('likes_user_' . $user_id, $likes, false);

        endif;

        if ($active === 'active'):

            if (($key = array_search($post_id, $likes)) !== false) :

                unset($likes[$key]);

            endif;

            update_option('likes_user_' . $user_id, $likes, false);

        endif;

        print_r($likes);

        die();

    }

    public function ajax_init()
    {

        add_action('wp_ajax_nopriv_jk_likes', [$this, 'ajax_handler']);

        add_action('wp_ajax_jk_likes', [$this, 'ajax_handler']);

    }

}
