<?php
/**
 * Plugin Name: Rekord API
 * Plugin URI: 
 * Description: API for Rekord WordPress Theme
 * Version: 1.3
 * Author: xvelopers
 * Author URI: http://xvelopers.com
 */
include ( __DIR__ . '/acf.php');
include ( __DIR__ . '/helpers.php');

include ( __DIR__ . '/RekordArtistsController.php');
include ( __DIR__ . '/RekordTracksController.php');
include ( __DIR__ . '/RekordAlbumsController.php');
include ( __DIR__ . '/RekordPodcastsController.php');
include ( __DIR__ . '/RekordVideosController.php');
include ( __DIR__ . '/RekordEventsController.php');
include ( __DIR__ . '/RekordExploreController.php');
include ( __DIR__ . '/RekordUserController.php');
include ( __DIR__ . '/TestController.php');
include ( __DIR__ . '/RekordPostsController.php');


function rekord_api_get_explore($post_type){
    $response = new RekordExploreController();
    return  $response->get();
}


function rekord_api_get_albums() {
    
    $ctrl = new RekordAlbumsController();
	$posts = rekord_api_get('album');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}

function rekord_api_get_tracks() {
    $ctrl = new RekordTracksController();
	$posts = rekord_api_get('track');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}

function rekord_api_get_artists() {
    $ctrl = new RekordArtistsController();
	$posts = rekord_api_get('artist');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}


function rekord_api_get_podcasts() {
	$ctrl = new RekordPodcastsController();
	$posts = rekord_api_get('podcast');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}

function rekord_api_get_videos() {
	$ctrl = new RekordVideosController();
	$posts = rekord_api_get('video');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}

function rekord_api_get_events() {
	$ctrl = new RekordEventsController();
	$posts = rekord_api_get('event');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}

function rekord_api_get_posts() {
	$ctrl = new RekordPostsController();
	$posts = rekord_api_get('post');
	$data =  $ctrl->data($posts);
	return [
		'data' => $data,
		'status' => 200
	];
}




function rekord_api_get_taxonomy(){
	$type = !empty($_GET['type']) ? $_GET['type'] : '';
	$terms = get_terms($type); 

	return $terms;
}



// function wl_post( $slug ) {
// 	$args = [
// 		'name' => $slug['slug'],
// 		'post_type' => 'post'
// 	];

// 	$post = get_posts($args);


// 	$data['id'] = $post[0]->ID;
// 	$data['title'] = $post[0]->post_title;
// 	$data['content'] = $post[0]->post_content;
// 	$data['slug'] = $post[0]->post_name;
// 	$data['media']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
// 	$data['media']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
// 	$data['media']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large');

// 	return $data;
// }

function addToFav(){

	

	$post_id = 1750;
	$favorite = new \Favorites\Entities\Favorite\Favorite();

	
	global $current_user;

		$_POST['logged_in'] = $current_user->ID;

		add_action('favorites_before_favorite', $post_id, 'active', get_current_blog_id() ,$current_user);
		
	// Trigger an update with the desired `post_id`
	$favorite->update( $post_id, 'active', get_current_blog_id() );
}

add_action('rest_api_init', function() {

	
	$routes = ['explore','posts','albums','artists','tracks','podcasts','videos','events','taxonomy'];
	foreach($routes as $route){
		register_rest_route('wl/v1', $route, [
			'methods' => 'GET',
			'callback' => 'rekord_api_get_'.$route,
		]);
	}


	register_rest_route( 'wl/v1', 'fav', array(
		'methods' => 'GET',
		'callback' => 'addToFav',
	) );


	// register_rest_route( 'wl/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', array(
	// 	'methods' => 'GET',
	// 	'callback' => 'wl_post',
	// ) );


	register_rest_route( 'wl/v1', 'user/update', array(
		'methods' => 'POST',
		'callback' => function (){
			return get_currentuserinfo();
			$userController = new RekordUserController();
			return $userController->update($_REQUEST);
		},
		// 'permission_callback' => function($request){	  
		// 	return is_user_logged_in();
		//   }
		
	) );

}, 100, 2);


 // $response = new WP_REST_Response($data, 200);
 add_action( 'simple_jwt_login_login_hook', function($user){

	return $user;

}, 10, 2);


add_action( 'simple_jwt_login_jwt_payload_auth', function($payload, $request){

	$userController = new RekordUserController();
	global $current_user;
	
	$payload['user'] =	$userController->get($payload['id']);
	
	return $payload;

}, 10, 2);

