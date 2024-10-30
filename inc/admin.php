<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('MMCFRAdmin')){
    
	  class MMCFRAdmin extends MMCFRCommon
      {
	  
		public static $instance = NULL;
       
        static function get_instance()
        {
            if(NULL == self::$instance){
                self::$instance == new self;
            }
            return self::$instance;
        }
		   
		function __construct()
        {
			parent::__construct();
			add_action('init',array($this,'addPostType'));
			add_action('admin_menu',array($this,'addToMenu'));
			add_action( 'admin_init',array($this,'registerSetting'));
			add_action('admin_enqueue_scripts',array($this,'includeAdminJsCss'));
			add_filter( 'preprocess_comment', array($this,'verifyCommentMetadata') );
			add_action( 'comment_post', array($this,'postCommentMetadata') );
			add_action( 'add_meta_boxes_comment', array($this,'addToCommentEditingScreen') );
			add_action( 'edit_comment', array($this,'updateCommentMetaEditingScreen') );
			


	    }
		 
		function verifyCommentMetadata( $commentdata )
		{
			global $meta_prifix;
			$notify_message = array();
			$negetive = $greater = $not_numeric = false;
			
			$mmcfr_query = new wp_query(array('post_type'=>'mmcfr-ratings','meta_key'=>$meta_prifix.'mmcfr-stars-required','meta_value'=>'1','posts_per_page'=>-1,));
			
			$rating = (isset($_POST['rating']))?$_POST['rating']:array();
			
			if($mmcfr_query->have_posts()):while($mmcfr_query->have_posts()):$mmcfr_query->the_post();
				  
				  $rating_required = get_post_meta(get_the_id(),$meta_prifix.'mmcfr-stars-required',true);
				  if($rating_required && empty($rating[get_the_id()])){
						$rating_required_msg = get_post_meta(get_the_id(),$meta_prifix.'mmcfr-stars-required-message',true);
						$rating_required_msg = strtr($rating_required_msg,array('{{field_title}}'=> get_the_title()));
						if(empty($rating_required_msg)){
							  $rating_required_msg = 'Rating for '.get_the_title().' is required.';
						}
						array_push($notify_message,__($rating_required_msg,'mmcfr'));
						
				  }
			endwhile;endif;wp_reset_query();
			
			foreach($rating as $rate_key=>$rating_val){
						
				  if(!empty($rating_val)){
						if($rating_val < 0){
							  $negetive = true;
						}elseif($rating_val > $this->no_of_stars_override){
							  $greater = true;
						}elseif($rating_val == 0){
							  $not_numeric = true;
						}
						
				  }
			}
				  
				  
				  if($negetive){
				  array_push($notify_message,__('Rating value must be a positive value.','mmcfr'));
				  }
				  if($greater){
				  array_push($notify_message,__('Rating value cannot be greater than 5.','mmcfr'));
				  }
				  if($not_numeric){
				  array_push($notify_message,__('Rating value must be a numeric.','mmcfr'));
				  }

				  
			
			if ( !empty(array_filter($notify_message) ) ){
				  array_push($notify_message,'<a href="javascript:history.back()">« Back</a>');
				  wp_die(implode('<br>',$notify_message));
			}
			
			return $commentdata;
	    }
		
		function postCommentMetadata($comment_id){
			
			global $meta_prifix;
			$rating = $_POST['rating'];
			add_comment_meta( $comment_id, $meta_prifix.'rating', $rating );
			
		}
		
		
		
		function addToCommentEditingScreen(){
			
			add_meta_box( 'title', __( 'Comment Rating' ), array($this,'commentEditingScreenFallbackFunc'), 'comment', 'normal', 'high' );
			
		}
		
		function commentEditingScreenFallbackFunc($comment){
			
			global $meta_prifix;
			$rating_data = '';
			$comment_text = '';
			$rating_details = get_comment_meta( $comment->comment_ID, $meta_prifix.'rating', true );
			$rating_details = (!empty($rating_details))?$rating_details:array();
			$rating_details = (!empty($rating_details))?$rating_details:array();
			$plugin_url_path = WP_PLUGIN_URL;

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
					
					$ratings_count = (empty($ratings_count))?false:$ratings_count;
					$stars_style = get_post_meta($ratings_post_id,$meta_prifix.'mmcfr-stars-style',true);
					$rating_required = get_post_meta($ratings_post_id,$meta_prifix.'mmcfr-stars-required',true);
					
					$render_data = array(
											 'id' => $ratings_post_id,
											 'stars' => $this->no_of_stars_override,
											 'required' => $rating_required,
											 'current_rating' => $ratings_count,
											 'disabled' => true
						 
										 );
					$comment_text .= '<div class="mmcfr-stars mm-'.$stars_style.' mm-clearfix">';
					$comment_text .= '<span class="mm-rating-title">'.get_the_title($ratings_post_id).'</span>';
					$comment_text .= $this->renderRatingHtml($stars_style,$render_data);
					$comment_text .= '</div>';
					
			}
			
			echo $comment_text;
		}
		
		function updateCommentMetaEditingScreen($comment_id){
			
			/* Do Nothing */
			
		}
	  
	  
	    function addToMenu()
	    {
			global $submenu;
			add_submenu_page('edit.php?post_type=mmcfr-ratings',__('Settings','mmcfr'),__('Settings','mmcfr'),'edit_theme_options','mmcfr-options',array($this,'addSettingsPage'));
			unset($submenu['edit.php?post_type=mmcfr-ratings'][10]);
	    }
		
	   function addSettingsPage()
	    {
			$options = get_option( 'mmcfr-custom-css' );
			$custom_css = isset( $options ) && ! empty( $options ) ? $options : __( '/* Enter Your Custom CSS Here */', 'simple-custom-css' );

	    ?>
		
		<div class="wrap">
			<div id="poststuff">
				  <div class="metabox-holder columns-2" id="post-body">
						<div id="post-body-content">
							  <h3 class="mm-heading">MM Comment Form Ratings Field</h3>
							  <div class="mm-body">
			<form method="post" action="options.php" class="mmcfr-settings-form">
                    <?php settings_fields('mmcfr-general-options'); ?>
									  <div class="mm-form-sections mm-clearfix">
                        <label class="mm-label mm-float-left mm-column-1" for="mmcfr-custom-css"><?php _e('Remove settings ','mmcfr'); ?></label>
                        <div class="mm-input-field mm-float-left mm-column-3">
							  <label class="mm-checkbox">
									<input type="checkbox" name="mmcfr-remove-settings" <?php echo (get_option('mmcfr-remove-settings'))?'checked="checked"':''; ?>>
									<span class="mm-check-indicator"></span>
							  </label>
                            
                        </div>
                    </div>

				  <div class="mm-form-sections mm-clearfix">
                        <label class="mm-label mm-float-left mm-column-1" for="mmcfr-custom-css"><?php _e('Show Average Rating','mmcfr'); ?></label>
                        <div class="mm-input-field mm-float-left mm-column-3">
							  <label class="mm-checkbox">
									<input type="checkbox" name="mmcfr-show-avg-rating" <?php echo (get_option('mmcfr-show-avg-rating'))?'checked="checked"':''; ?>>
									<span class="mm-check-indicator"></span>
							  </label>
                            
                        </div>
                    </div>

					<div class="mm-form-sections mm-clearfix">
                        <label class="mm-label mm-float-left mm-column-1" for="mmcfr-custom-css"><?php _e('Custom Css','mmcfr'); ?></label>
                        <div class="mm-input-field mm-float-left mm-column-3">
                            <textarea id="mmcfr-custom-css" name="mmcfr-custom-css"> <?php echo esc_html( $custom_css ); ?> </textarea>
                        </div>
                    </div>

			<div class="mm-form-sections mm-clearfix">
				 <button type="submit" class="mm-submit-button" value="Submit"><i class="fa fa-floppy-o" aria-hidden="true"></i>
				<?php _e('Save Options','mmmm_text_domain'); ?></button>
			</div>

            </form>

							  </div>
						</div>
						<div class="postbox-container" id="postbox-container-1">
							  <div class="postbox">
									<h3 class="mm-heading">Donate</h3>
									<div class="mm-body">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" class="mm-donate-form">
									<input type="hidden" name="cmd" value="_donations">
									<input type="hidden" name="business" value="manidip143@gmail.com">
									<input type="hidden" name="item_name" value="Donation">
									<input type="hidden" name="item_number" value="1">
									<!--<input type="text" name="amount" value="10.00">-->
									<input type="hidden" name="no_shipping" value="0">
									<input type="hidden" name="currency_code" value="USD">
									<input type="image" src="<?php echo MMCFR_IMAGE_DIR_PATH.'/donate-button.png'; ?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
									<img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
									</form>
									</div>
							  </div>
						</div>
				  </div>
			</div>
		</div>
			
	    <?php
			
	    }
	  /**
	  * Registers post types needed by the plugin.
	  * @access public
	  * @return void
	  */
		function addPostType()
		{
			
			$args = array(
                            'labels'    => array(
                                        'name' => __( 'MM Comment Ratings','mmcfr' ),
                                        'singular_name' => __( 'Rating','mmcfr' ),
                                        'all_items'  => __( 'All Ratings Field','mmcfr'),
										'add_new'            => __( 'Add Ratings Field', 'mmcfr' ),
										'add_new_item' => __('Add Ratings Field','mmcfr'),
                                    ),
                            'public'             => false,
                            'publicly_queryable' => true,
                            'show_ui'            => true,
                            'show_in_menu'       => true,
                            'query_var'          => true,
                            'rewrite'            => array( 'slug' => 'mmcfr-ratings' ),
                            'capability_type'    => 'post',
                            'has_archive'        => true,
                            'hierarchical'       => false,
                            'menu_position'      => null,
                            'supports'           => array( 'title', 'editor'),
							'supports' => array('title')
                            //'menu_icon'			 => MMCR_PLUGIN_URL.'images/reviews2.png'
                        );
			register_post_type( 'mmcfr-ratings', $args );
		}
		 
		 
		 
		function registerSetting() {
            $settings_group = 'mmcfr-general-options';
            foreach (MMCFRCommon::$settings_options as $key=>$value){
             register_setting( $settings_group, $key);
            }
	  } 
	  
	 
	  
	  function includeAdminJsCss($hook){
			
			$this->enqueueStyles();
			$this->enqueueScripts();
			
	  }  
   }
}

MMCFRAdmin::get_instance();