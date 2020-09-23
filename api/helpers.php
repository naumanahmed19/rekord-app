<?php

function rekord_get_post_media($id){
    $media = [];
    $media['thumbnail'] = get_the_post_thumbnail_url($id, 'thumbnail');
    $media['medium'] = get_the_post_thumbnail_url($id, 'medium');
	$media['large'] = get_the_post_thumbnail_url($id, 'large');

	if(!empty( $cover = rekord_get_field('cover',$id))){
		$media['cover'] = $cover['url']   ;
	}
    	
    return $media;
}

function rekord_api_get_posts($post_type, $postsPerPage = -1){

	$paged = 1;
	$postOffset = 0;
	$postsPerPage = !empty($_GET['numberposts'] )? $_GET['numberposts'] :$postsPerPage;
	
	
	if(!empty($_GET['paged'])){
		$paged = $_GET['paged'];
		$postOffset = $paged * $postsPerPage;
	}


	$postOffset = $paged * $postsPerPage;
	$args = array(
		'posts_per_page'  => $postsPerPage,
		//'category_name'   => $btmetanm,
		'offset'          => $postOffset,
		'post_type'       => $post_type
	);


	if(!empty($_GET['q'])){
		$args['s'] =  esc_attr( $_GET['q']);
		$args['posts_per_page'] = -1;
	}

	return  get_posts($args);
}