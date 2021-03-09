<?php



class RekordPostsController{

    public function data($posts){
        $data = [];
        $i = 0;
        if(!empty($posts)){
            foreach($posts as $post) {

              
                $data[$i]['id'] = $post->ID;
                $data[$i]['title'] = $post->post_title;
                $data[$i]['content'] = $post->post_content;
                $data[$i]['slug'] = $post->post_name;
                $data[$i]['date'] = $post->post_date;
                $data[$i]['media'] = rekord_get_post_media($post->ID);
                $data[$i]['author'] = $this->getAuthor($post);

                $data[$i]['commentCount'] = $post->comment_count;
                $data[$i]['favorited'] = isFavorited($post->ID);
                
                $data[$i]['comments'] = $this->getComments($post);
                
        
                $i++;
            }
        }
        return $data;
    }


    public function getAuthor($post){

        $data = [];
        $data['displayName'] = get_the_author_meta('display_name', $post->post_author);
        $data['firstName'] = get_the_author_meta('first_name', $post->post_author);
        $data['lastName'] = get_the_author_meta('last_name', $post->post_author);
        $data['avatar'] = get_Avatar_url($post->post_author);
        return $data;
    }
    public function getComments($post){
        $data = [];

        $comments =  get_comments(array('post_id' => $post->ID ));
        foreach($comments as $comment){
              $data['id'] = json_decode($comment->comment_ID);  
              
              $data['date'] = $comment->comment_date;  
              $data['approved'] = json_decode($comment->comment_approved);  
              $data['content'] = $comment->comment_content; 
              $data['author']['displayName'] = $comment->comment_author;  
              $data['author']['avatar'] = get_Avatar_url($comment->user_id);
        }
       
        return $data;
    }


}



