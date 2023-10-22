<?php
/**
 * Operations of the plugin are included here. 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//Register custom post type "project"
function custom_post_type_project() {
    $labels = array(
        'name' => 'Projects',
        'singular_name' => 'Project',
        'menu_name' => 'Projects',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    );

    register_post_type('project', $args);
}
add_action('init', 'custom_post_type_project');

function mapp_load_projects($page, $search_term) {

    $query = new WP_Query(array(
        'post_type' => 'project',
        'posts_per_page' => 6,
        'paged' => $page,
        's' => $search_term
    ));

    ob_start();

    if ($query->have_posts()) {         
        ?>            
        <div class="row gx-5">
            <?php 
            while ($query->have_posts()) {
                $query->the_post();
            ?>
                <div class="col-lg-4">
                    <div class="mapp-project shadow mb-5">
                        <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                            <h2 class="mb-4"><?php echo get_the_title(); ?></h2>
                        </a>
                        <div class="project-excerpt mb-4"><?php echo get_the_excerpt(); ?></div>
                        <div class="project-link">
                            <a href="<?php echo get_the_permalink(); ?>" class="btn btn-primary" title="<?php echo get_the_title(); ?>">
                                <?php echo __('Read more', 'ma-projects-plugin'); ?>
                            </a>
                        </div>
                    </div>
                </div><!-- .col -->
            <?php
            }
            ?>
        </div><!-- .row -->

        <div class="mapp-pagination text-center">
            <?php if($page > 1) { ?>
            <a id="mapp-previous-page" href="#" class="btn btn-outline">
                <img class="mapp-btn-loader" src="<?php echo MAPP_PLUGIN_URL. 'assets/img/preloader.svg' ?>" alt="Loading...">
                <?php echo __('Previous', 'ma-projects-plugin'); ?>
            </a>
            <?php } ?>
            
            <?php if($query->max_num_pages > $page) { ?>
            <a id="mapp-next-page" href="#" class="btn btn-outline">
                <?php echo __('Next', 'ma-projects-plugin'); ?>
                <img class="mapp-btn-loader" src="<?php echo MAPP_PLUGIN_URL. 'assets/img/preloader.svg' ?>" alt="Loading...">
            </a>
            <?php } ?>
        </div><!-- .mapp-pagination -->
        <?php
    }
    else {
        echo '<div class="alert alert-warning">'.__('No projects found.', 'ma-projects-plugin').'</div>';
    }

    wp_reset_postdata();
    
    $output = ob_get_contents();    
    ob_end_clean();

    echo $output;
}

function mapp_load_projects_ajax() {
    check_ajax_referer('mapp_nonce', 'mapp_nonce');
    $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
    $search_term = $_REQUEST['search_term'] ? $_REQUEST['search_term'] : '';

    mapp_load_projects($page, $search_term);
}

add_action('wp_ajax_mapp_load_projects', 'mapp_load_projects_ajax');
add_action('wp_ajax_nopriv_mapp_load_projects', 'mapp_load_projects_ajax');

//Shortcode to display projects
function mapp_posts($atts, $content = null) {
    $atts = shortcode_atts(array(
        
    ), $atts);

    wp_enqueue_style('mapp-projects-style', MAPP_PLUGIN_URL . 'assets/css/mapp-projects.css');
    wp_enqueue_script('mapp-projects-script', MAPP_PLUGIN_URL . 'assets/js/mapp-projects.js', array('jquery'), '1.0', true);

    wp_localize_script('mapp-projects-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'mapp_nonce' => wp_create_nonce('mapp_nonce')
    ));

    ob_start();
    ?>

    <div class="mapp-projects-search mb-5">
        <form id="mapp-search-form">
            <div class="mapp-form-group">
                <input type="text" name="search_term" class="form-control" value="" placeholder="<?php echo __('Search projects', 'ma-projects-plugin'); ?>">
                <a href="#" class="mapp-form-group-icon mapp-search-submit" title="Search">
                    <img class="mapp-btn-loader mapp-search-loader" src="<?php echo MAPP_PLUGIN_URL. 'assets/img/preloader.svg' ?>" alt="Loading...">
                    <img class="mapp-icon-search" src="<?php echo MAPP_PLUGIN_URL. 'assets/img/search-icon.svg' ?>" alt="Search">
                </a>
            </div>
            <input type="submit" class="d-none" value="Search">
        </form>
    </div><!-- .mapp-projects-search -->

    <div class="mapp-projects-container">
        <div class="mapp-projects">
            <?php mapp_load_projects(1, ''); ?>
        </div><!-- .mapp-projects -->
    </div><!-- .mapp-projects-container -->

    <?php
    $output = ob_get_contents();
    ob_end_clean();

    wp_reset_postdata();

    return $output;
}
add_shortcode('mapp_posts', 'mapp_posts');
