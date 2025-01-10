<?php 

/**
 * Plugin Name: ShortCode Plugin
 * Description: This is our second plugin which gives idea about shortcode basics
 * Author: Arnaud Flament
 * Version: 1.0
 * Author URI: 
 * Plugin URI: https://github.com/aflamentTM/plugin_exercice
 */
//  Basique shortcode
add_shortcode("message","sp_show_static_message");
function sp_show_static_message() {
    return "<p style='color:red;font-size:36px;font-weight:bold'>Hello I am a shortcode message</p>";
}
// Shortcode with params
add_shortcode("student", "sp_handle_student_data");
function sp_handle_student_data($attributes) {
    $attributes =  shortcode_atts(array( 
        "name" => "Default Student",
        "email" => "Default Email"
    ), $attributes, "student");
    return "<h3>Student Data: Name - {$attributes['name']}, Email - {$attributes['email']}</h3>";
}
// shortcode with DB operations
add_shortcode("list-posts", "sp_handle_list_posts_wp_query_class");

function sp_handle_list_posts() {
    global $wpdb;
    $table_prefix = $wpdb->prefix; // wp_
    $table_name =$table_prefix . "posts"; // wp_posts
    // Get post whose post_type =  post and post_status = publish

   $posts =  $wpdb->get_results(
        "SELECT post_title from {$table_name} WHERE post_type = 'post' AND post_status = 'publish' ");
        // error_log(print_r($posts, true));
        if (count($posts) > 0) {
            $outputHtml = "<ul>";
            foreach($posts as $post) {
                // error_log($post->post_title);
                $outputHtml .= '<li>'. $post->post_title .'</li>';
            }
            $outputHtml .= "</ul>";

            return $outputHtml;
        };
        return  "pas de posts";
}
function sp_handle_list_posts_wp_query_class($attributes) {
    $attributes = shortcode_atts(array( 
        "number" => 5
    ), $attributes, "list-posts");

    $query = new WP_Query(array( 
        "posts_per_page" => $attributes['number'],
        "post_status" => "publish"
    ));

    if($query->have_posts()) {
        $outputHtml = '<ul>';
        while($query->have_posts()) {
            $query->the_post();
            $outputHtml .= '<li class="my_class"><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
        }
        $outputHtml .= '</li>';
        return $outputHtml;
    }
    return "Pas d'article trouvé !";
}