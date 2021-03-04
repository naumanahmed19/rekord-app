<?php

//TODO

class RekordVideosController{

    public function data($posts){
        $data = [];
        $i = 0;
        if(!empty($posts)){
            foreach($posts as $post) {
                $data[$i]['id'] = $post->ID;
                $data[$i]['title'] = $post->post_title;
                $data[$i]['content'] = $post->post_content;
                $data[$i]['slug'] = $post->post_name;


                if(get_the_post_thumbnail_url($post->ID, 'thumbnail')){
                    $data[$i]['media'] = rekord_get_post_media($post->ID);
                }
                $data[$i]['favorited'] = isFavorited($post->ID);
                
        
                $i++;
            }
        }
        return $data;
    }

}



