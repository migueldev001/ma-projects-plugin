<?php 
/**
 * Basic setup functions for the plugin
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 

function mapp_activate_plugin() {

    //Check if any project post exists
    $query = new WP_Query(array(
        'post_type' => 'project',
        'posts_per_page' => 1,
    ));

    //If not, insert 30 posts
    if (!$query->have_posts()) {
        //Fetch posts from the free API jsonplaceholder.typicode.com
        $response = wp_remote_get('https://jsonplaceholder.typicode.com/posts');
        $posts = json_decode(wp_remote_retrieve_body($response));

        $i = 0;
        foreach ($posts as $post) {
            $post_data = array(
                'post_title' => $post->title,
                'post_content' => $post->body,
                'post_status' => 'publish',
                'post_type' => 'project',
            );

            wp_insert_post($post_data);

            $i++;

            if ($i == 30) {
                break;
            }
        }
    }

}

/**
 * Load plugin text domain
 */
function mapp_load_plugin_textdomain() {
    load_plugin_textdomain( 'ma-projects-plugin', false, '/ma-projects-plugin/languages/' );
}
add_action( 'plugins_loaded', 'mapp_load_plugin_textdomain' );