<?php
if(!class_exists('MMCFRCommon'))
{
    class MMCFRCommon
        {
            
            protected $no_of_stars_override = 5;
            public static $settings_options;
            
            function __construct(){
                
               self::$settings_options = array(
                                'mmcfr-show-avg-rating'=>false,
                                'mmcfr-custom-css'=>'',
                                'mmcfr-remove-settings'=> true
               
                );
            }
          
            protected function renderRatingHtml($style,$render_data)
            {
              
                $output = '';
                switch($style)
                {
                     case 'bars-1to10':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="bars-1to10" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                     break;
                     case 'bars-movie':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="bars-movie" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                          break;
                     case 'bars-square':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="bars-square" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                          break;
                     case 'bars-pill':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="bars-pill" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                          break;
                     case 'bars-boxed':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="bars-boxed" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                          break;
                     case 'fontawesome-stars':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="fontawesome-stars" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                          break;
                    case 'fontawesome-stars-o':
                          $output .= '<select ';
                          $output .=($render_data['disabled'])?' data-current-rating="'.$render_data['current_rating'].'" ':'';
                          $output .='class="fontawesome-stars-o" name="rating['.$render_data['id'].']" autocomplete="off">';
                          $output .= $this->renderLoopOptHtml($render_data['stars']);
                          $output .=	'</select>';
                           
                          return $output;
                          break;
                }
              
              
         }
         
        protected function renderLoopOptHtml($count)
        {
              
              $output = '';
              $output .= '<option value="">-</option>';
              for( $i=1; $i <= $count; $i++ )
              {
                 $output .=  '<option value="'.$i.'">'.$i.'</option>';
              }
              return $output;
              
        }
         
        protected function enqueueStyles(){
            
            $url = home_url();

            if ( is_ssl() ) {
                $url = home_url( '/', 'https' );
            }
                
            if(is_admin()){
                $screen = get_current_screen();
           }
           
            if(!is_admin() || (isset($screen) && 'mmcfr-ratings' == $screen->id  || $screen->id == 'edit-comments' || $screen->id == 'comment') || (isset($_GET['page']) && 'mmcfr-options' == $_GET['page'])){
                wp_enqueue_style( 'mmcfr-font-awesome', MMCFR_CSS_DIR_URL.'font-awesome.min.css' );
                wp_enqueue_style( 'mmcfr-barrating-bars-1to10', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/bars-1to10.css' );
                wp_enqueue_style( 'mmcfr-barrating-bars-movie', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/bars-movie.css' );
                wp_enqueue_style( 'mmcfr-barrating-bars-square', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/bars-square.css' );
                wp_enqueue_style( 'mmcfr-barrating-bars-pill', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/bars-pill.css' );
                wp_enqueue_style( 'mmcfr-barrating-bars-reversed', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/bars-reversed.css' );
                wp_enqueue_style( 'mmcfr-barrating-bars-horizontal', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/bars-horizontal.css' );
                wp_enqueue_style( 'mmcfr-barrating-fontawesome-stars', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/fontawesome-stars.css' );
                wp_enqueue_style( 'mmcfr-barrating-css-stars', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/css-stars.css' );
                wp_enqueue_style( 'mmcfr-barrating-fontawesome-stars-o', MMCFR_CSS_DIR_URL.'jquery-bar-rating/themes/fontawesome-stars-o.css' );
            }
            if(is_admin()){
                wp_enqueue_style( 'mmcfr-admin-style', MMCFR_CSS_DIR_URL.'admin.style.css' );
            }else{
                wp_enqueue_style( 'mmcfr-user-style', MMCFR_CSS_DIR_URL.'user.style.css' );
                wp_register_style( 'mmcfr_custom_style', add_query_arg( array( 'mmcfrcss' => 1 ), $url ) );
                wp_enqueue_style( 'mmcfr_custom_style' );
            }
        }
        
        protected function enqueueScripts(){
           
           if(is_admin()){
                $screen = get_current_screen();
           }
           
           if(!is_admin() || (isset($screen) && 'mmcfr-ratings' == $screen->id  || $screen->id == 'edit-comments' || $screen->id == 'comment') || (isset($_GET['page']) && 'mmcfr-options' == $_GET['page'])){
                wp_enqueue_script( 'mmcfr-barrating-js', MMCFR_JS_DIR_URL.'jquery.barrating.min.js', array('jquery'), '', true );
           }
           
            
            if(is_admin() && isset($screen) && ( 'mmcfr-ratings' == $screen->id  || $screen->id == 'edit-comments' || $screen->id == 'comment' || (isset($_GET['page']) && 'mmcfr-options' == $_GET['page'])) ){ 
                wp_enqueue_script( 'mmcfr-cmb2-conditionals-js', MMCFR_JS_DIR_URL.'cmb2-conditionals.js', array('jquery'), '', true );
                wp_enqueue_script( 'mmcfr-admin-script-js', MMCFR_JS_DIR_URL.'admin.script.js', array('mmcfr-barrating-js'), '', true );
            }else{
                wp_register_script( 'mmcfr-user-script-js', MMCFR_JS_DIR_URL.'user.script.js', array('mmcfr-barrating-js'), '', true );
			    wp_localize_script( 'mmcfr-user-script-js','mmcfrObj',array(
																	  'rating_styles' => array(121),
																	  ));
			    wp_enqueue_script( 'mmcfr-user-script-js');
            }
            
            if(is_admin() && isset($_GET['page']) && 'mmcfr-options' == $_GET['page']){
				  
                wp_enqueue_style( 'mm-codemirror-css', MMCFR_ASTS_DIR_URL.'codemirror/codemirror.css' );
                wp_enqueue_script( 'mm-codemirror-js', MMCFR_ASTS_DIR_URL.'codemirror/codemirror.js', array(), '', true );
                wp_enqueue_script( 'mm-codemirror-css-js', MMCFR_ASTS_DIR_URL.'codemirror/css.js', array(), '', true );
			  
			}
        }
      
    }
}

    function mmcfr_uninstall()
        {
            if(get_option('mmcfr-remove-settings')){
                
                /* Delete Options*/
                foreach (MMCFRCommon::$settings_options as $name=>$value)
                {
                        delete_option($name);   
                }
                /*============================================================*/
                /* Delete Comment Meta*/
                global $meta_prifix;
                $args = array('meta_key'=>$meta_prifix.'rating');
                $comments = get_comments($args);
			
                foreach($comments as $comment) :
                   delete_comment_meta( $comment->comment_ID, $meta_prifix.'rating' );
                endforeach;
                /*============================================================*/
                /* Delete Comment Fields*/
                $mmcfr_ratings_posts = get_posts( array( 'post_type' => 'mmcfr-ratings', 'posts_per_page'   => -1,));
                foreach( $mmcfr_ratings_posts as $mypost ) {
                   wp_delete_post( $mypost->ID, true);
                  }
                }
  
        }