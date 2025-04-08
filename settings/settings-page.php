<?php

if(!defined('ABSPATH')){
    die('Access denied');
}

add_action('admin_menu', 'initialise_settings_page');
add_action('rest_api_init', 'create_rest_endpoint');

function initialise_settings_page(){
     add_menu_page('API Settings Page', 'API Settings', 'manage_options', 'api-settings', 'api_settings_page_view', 'dashicons-excerpt-view');
}

function create_rest_endpoint(){
    register_rest_route('v1/api-posts', 'create', array(
        'methods' => 'POST',
        'callback' => 'create_api_post',
        'permission_callback' => '__return_true'
    ));
}

function create_api_post(WP_REST_Request $request){
    $body = json_decode($request->get_body(), true);

    $postdata = [
        'post_title' => $body['title'],
        'post_content' => $body['body'],
        'post_type' => 'api-data',
        'post_status' => 'publish'
  ];

    $post_id = wp_insert_post($postdata);

    if(gettype($post_id) == "integer") {

        return new WP_Rest_Response('Post created successfully', 200);

    } else {

        return new WP_Rest_Response('Could not create post', 500);
    }

}


function api_settings_page_view(){
    global $current_user;
    wp_get_current_user();

    echo "
        <h1>". __('Hello ') . $current_user->display_name . "</h1>
        <p>Here you can fetch API data and create custom posts out of it</p>
        <hr>
        <button id='fetch-api'>Fetch API Data</button>
        <div id='results-container' style='display: none'>
            <h2>Results</h2>
            <ul id='results' style='margin: 2rem'>
            </ul>
        </div>
    ";

 ?>
    <script>
        const handlePost = async (id) => {
            console.log(id);
            
            const res = await fetch("<?php echo get_rest_url(null, 'v1/api-posts/create');?>", {
                method: "POST",
                body: JSON.stringify({ 
                    title: document.getElementById(`post-title-${id}`).innerHTML,
                    body: document.getElementById(`post-body-${id}`).innerHTML,
                 }),
                headers: {
                    "Content-Type": "application/json",
                },
            },)
            console.log(res);

        }

        jQuery(document).ready(($) => {

            $("#fetch-api").on("click", () => {
                $.ajax({
                type: "GET",
                url: "https://jsonplaceholder.typicode.com/posts",
                success: (res) => {
                    $("#results-container").show()

                    const list = res.map((value) => ({ value, sort: Math.random() }))
                    .sort((a, b) => a.sort - b.sort)
                    .splice(0, 9)
                    .map(({value}) => (
                        `<li style="display: flex; width: 100%; justify-content: space-between; ">
                        
                        <div style="display: flex; flex-direction: column; min-width: 12rem; max-width: 12rem; margin-right: 0.5rem">
                            <p style="font-weight: bold">Title </p>
                            <p id="post-title-${value.id}">${value.title}</p>
                        </div>
                        <div style="display: flex; flex-direction: column; width: 100%;">
                            <p style="font-weight: bold">Body </p>
                            <p id='post-body-${value.id}'>${value.body}</p>
                        </div>
                        <div style="display: flex; align-items: center; min-width: 4rem; max-width: 4rem; justify-content: flex-end">
                            <button id="import" onclick="handlePost(${value.id})">Import</button> 
                        </div>
                        
                        </li>
                        <hr>`
                    ));

                    $("#results").html(list).fadeIn();
                },
                });
            });
        });

    </script>
 <?php
}