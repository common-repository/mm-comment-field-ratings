<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('MMCFRUser')){
	 class MMCFRUser extends MMCFRCommon
	 {
		 
		  public static $instance = NULL;
	 
		  public static function get_instance()
		  {
			 if(NULL == self::$instance)
			 {
			  self::$instance = new self;
			 }
		   return self::$instance;
			}
		  
		  
		   public function __construct(){
		  
		    parent::__construct();
			add_action('wp_head',array($this,'registerStyle'));
			add_action( 'wp_enqueue_scripts',array($this,'includeUserJsCss'));
			add_action( 'comment_form_logged_in_after', array($this,'commentAdditionalFields' ));
			add_action( 'comment_form_after_fields', array($this,'commentAdditionalFields' ));
			add_action( 'comment_text', array($this,'outputCommentMetadata') );
			add_action( 'plugins_loaded', array($this,'customCss') );
	 
		}
		
		 function commentAdditionalFields () {
		
			 global $meta_prifix;
			 $mmcfr_query = new wp_query(array('post_type'=>'mmcfr-ratings','posts_per_page'=>-1,));
			 $output = '';
			 
			 if($mmcfr_query->have_posts()):while($mmcfr_query->have_posts()):$mmcfr_query->the_post();
			 
					if($this->no_of_stars_override == NULL){
						 $no_of_stars = get_post_meta(get_the_id(),$meta_prifix.'mmcfr-stars-count',true);
					}else{
						 $no_of_stars = $this->no_of_stars_override;
					}
					$stars_style = get_post_meta(get_the_id(),$meta_prifix.'mmcfr-stars-style',true);
					$rating_required = get_post_meta(get_the_id(),$meta_prifix.'mmcfr-stars-required',true);
					
					$render_data = array(
											 'id' => get_the_id(),
											 'stars' => $no_of_stars,
											 'required' => $rating_required,
											 'current_rating' => 0,
											 'disabled' => false
						 
										 );
					echo '<div class="mmcfr-stars mm-'.$stars_style.' mmcfr-color-'.get_the_id().'">';
					echo '<span class="mm-rating-title">'.get_the_title().'</span>';
					echo $this->renderRatingHtml($stars_style,$render_data);
					echo '</div>';
			 
			 endwhile;endif;wp_reset_query();
			 
		 }
		 
		 function outputCommentMetadata($comment_text){
			
			global $meta_prifix;
			$rating_data = '';
			$rating_details = get_comment_meta( get_comment_ID(), $meta_prifix.'rating', true );
			
			
			if($rating_details){
			   
			   $count = 1;
			   $rating_details = (!empty($rating_details))?$rating_details:array();
			   
			   $rating_details_val = array_values($rating_details);
			   $rating_details_val_total = array_sum($rating_details_val);
			   $rating_details_val_count = count($rating_details_val);
			   
			   if(get_option('mmcfr-show-avg-rating')){
			   
			   $avg_rate = round(($rating_details_val_total/$rating_details_val_count),1);
			   
			   $comment_text .= '<div class="mm-ratings">';
			   $comment_text .= '<div class="mmcfr-stars mm-fontawesome-stars-o">';
			   $comment_text .= '<span class="mm-rating-title">'.__( 'Average Rating', 'mmcfr' ).'</span>';
			   $comment_text .= $this->renderRatingHtml('fontawesome-stars-o',array(
																					 'id' => 'avg',
																					 'stars' => $this->no_of_stars_override,
																					 'required' => false,
																					 'current_rating' => $avg_rate,
																					 'disabled' => true
																 
																				 ));
			   $comment_text .= '</div>';
			   
			   }
			   foreach($rating_details as $ratings_post_id => $ratings_count ){
					   
					 $ratings_count = (empty($ratings_count))?0:$ratings_count;
					   
					   if($this->no_of_stars_override == NULL){
						 $no_of_stars = get_post_meta($ratings_post_id,$meta_prifix.'mmcfr-stars-count',true);
						 }else{
							  $no_of_stars = $this->no_of_stars_override;
						 }
					  
					   $stars_style = get_post_meta($ratings_post_id,$meta_prifix.'mmcfr-stars-style',true);
					   $rating_required = get_post_meta($ratings_post_id,$meta_prifix.'mmcfr-stars-required',true);
					   
					   $render_data = array(
												'id' => $ratings_post_id,
												'stars' => $no_of_stars,
												'required' => $rating_required,
												'current_rating' => $ratings_count,
												'disabled' => true
							
											);
					   
					   
					   $comment_text .= ($count==3 && is_admin())?'<div class="mm-minimize-target">':'';
					   $comment_text .= '<div class="mmcfr-stars mm-'.$stars_style.' mmcfr-color-'.$ratings_post_id.' mm-clearfix">';
					   $comment_text .= '<span class="mm-rating-title">'.get_the_title($ratings_post_id).'</span>';
					   $comment_text .= $this->renderRatingHtml($stars_style,$render_data);
					   $comment_text .= '</div>';
					   $comment_text .= ($count == $rating_details_val_count && is_admin() )?'</div"><span class="mm-minimize-click"> Show More</span>':'';
		  
					   $count ++;
					   
			   }
			   
			   $comment_text .= '</div>';
			}
			
			return $comment_text;
  
		}
		
		 public function includeUserJsCss(){
		  
			   $this->enqueueStyles();   
			   $this->enqueueScripts();
			   
		  }
		
		  function registerStyle(){
			   
			    global $meta_prifix;$style='';
			    $mmcfr_query = new wp_query(array('post_type'=>'mmcfr-ratings','posts_per_page'=>-1,));
		  ?>
		  <style>
		  <?php
			    $custom_css = get_option('mmcfr-custom-css');
				if($mmcfr_query->have_posts()):while($mmcfr_query->have_posts()):$mmcfr_query->the_post();
				
				if(get_post_meta(get_the_id(),$meta_prifix.'use-default-style',true)){
					continue;
				}
				$stars_style = get_post_meta(get_the_id(),$meta_prifix.'mmcfr-stars-style',true);
				
				$stars_color = get_post_meta(get_the_id(),$meta_prifix.'color',true);
				$stars_hover_color = get_post_meta(get_the_id(),$meta_prifix.'hover-color',true);
				$stars_selected_color = get_post_meta(get_the_id(),$meta_prifix.'selected-color',true);
				
				$stars_border_color = get_post_meta(get_the_id(),$meta_prifix.'border-color',true);
				$stars_border_hover_color = get_post_meta(get_the_id(),$meta_prifix.'hover-border-color',true);
				$stars_border_selected_color = get_post_meta(get_the_id(),$meta_prifix.'selected-border-color',true);
				
				
				$stars_text_color = get_post_meta(get_the_id(),$meta_prifix.'text-color',true);
				$stars_text_hover_color = get_post_meta(get_the_id(),$meta_prifix.'hover-text-color',true);
				$stars_text_selected_color = get_post_meta(get_the_id(),$meta_prifix.'selected-text-color',true);
				
				
				
				
		  ?>
		  <?php if($stars_style == 'bars-1to10' || $stars_style == 'bars-movie' || $stars_style == 'bars-boxed'){ ?>
			   
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-widget a{background-color: <?php echo $stars_color; ?>; }
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-widget a.br-active{background-color: <?php echo $stars_hover_color; ?>; }
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-widget a.br-selected{background-color: <?php echo $stars_selected_color; ?>; }
		  
		  <?php }elseif($stars_style == 'bars-square'){?>
			   
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-bars-square .br-widget a{color: <?php echo $stars_text_color; ?>;border-color: <?php echo $stars_border_color; ?>; }
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-bars-square .br-widget a.br-active{color: <?php echo $stars_text_hover_color; ?>;border-color: <?php echo $stars_border_hover_color; ?>; }
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-bars-square .br-widget a.br-selected{color: <?php echo $stars_text_selected_color; ?>;border-color:  <?php echo $stars_border_selected_color; ?>;}
			   
		  
		  <?php }elseif($stars_style == 'bars-pill'){?>
		  
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-bars-pill .br-widget a{color: <?php echo $stars_text_color; ?>;background-color:  <?php echo $stars_color; ?>; }
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-bars-pill .br-widget a.br-active{color: <?php echo $stars_text_hover_color; ?>;background-color:  <?php echo $stars_hover_color; ?>; }
			   .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-bars-pill .br-widget a.br-selected{color: <?php echo $stars_text_selected_color; ?>;background-color:  <?php echo $stars_selected_color; ?>;}
	 
		  <?php }elseif($stars_style == 'fontawesome-stars'){?>
			
			    .mmcfr-color-<?php echo get_the_id(); ?> .br-theme-fontawesome-stars .br-widget a::after{color: <?php echo $stars_color; ?>; }
				.mmcfr-color-<?php echo get_the_id(); ?> .br-theme-fontawesome-stars .br-widget a.br-active::after{color: <?php echo $stars_hover_color; ?>; }
				.mmcfr-color-<?php echo get_the_id(); ?> .br-theme-fontawesome-stars .br-widget a.br-selected::after{color: <?php echo $stars_selected_color; ?> !important;}
				
			<?php } ?>
			
			<?php endwhile;endif;wp_reset_query(); ?>
			<?php echo $custom_css;?>
			</style>
		  <?php
	   }
	   
	   function customCss()
	   {

		  // Only print CSS if this is a stylesheet request
		  if( ! isset( $_GET['mmcfrcss'] ) || intval( $_GET['mmcfrcss'] ) !== 1 ) {
			  return;
		  }
	  
		  ob_start();
		  header( 'Content-type: text/css' );
		  $custom_css     = get_option('mmcfr-custom-css');
		  $raw_content = isset( $custom_css ) ? $custom_css : '';
		  $custom_css     = wp_kses( $custom_css, array( '\'', '\"' ) );
		  $custom_css     = str_replace( '&gt;', '>', $custom_css );
		  echo $custom_css; //xss okay
		  die();
	 }
	   
	 }
}

MMCFRUser::get_instance();
?>