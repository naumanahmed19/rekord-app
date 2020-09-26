<?php
class RekordExploreController{


    public function get(){
       
            $data = [];
            $i = 0;


            $albums = new RekordAlbumsController();
            $tracks = new RekordTracksController();
            $artists = new RekordArtistsController();


            $sections =  rekord_get_field('r_explore_screen', 'option');

            foreach($sections as $section){
                $postPerPage = $section['r_number_of_post'];


                $data[$i]['title'] = $section['r_title'];
                $data[$i]['type'] = $section['r_post_type'];
                if(!empty($section['style'])){
                    $data[$i]['style'] =$section['style'];
                }
              


                $args = array(
                    'posts_per_page'  => $postPerPage,
                    //'category_name'   => $btmetanm,
                    //'offset'          => $postOffset,
                    'post_type'       =>  $section['r_post_type']
                );
                $posts = get_posts($args);
                
                if($section['r_post_type'] == 'album'){
                
                    $data[$i]['albums'] =  $albums->data($posts);
                }
                
                if($section['r_post_type'] == 'track'){
                    $data[$i]['tracks'] =$tracks->data($posts);
                }

                if($section['r_post_type'] == 'artist'){
                    $data[$i]['artists'] =$artists->data($posts);
                }


                //$data['sections'][$i] = $section['r_post_type'];	

                $i++;
            }


            return [
                'data' => $data,
                'status' => 200
            ];
    }
    
        
}


