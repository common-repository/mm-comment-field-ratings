<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once(MMCFR_ASTS_DIR_PATH.'cmb2/init.php');
require_once(MMCFR_ASTS_DIR_PATH.'extend.init.cmb2.php');
require_once(MMCFR_ASTS_DIR_PATH.'cmb2-conditionals.php');

$meta_prifix = 'mmcfr-meta-';


if(!class_exists('MMCFRMetaBoxes'))
{
    
    class MMCFRMetaBoxes
    {
        public static $instance;
    
        public static function get_instance(){
            if(NULL == self::$instance){
                self::$instance = new self;
            }else{
                return self::$instance;
            }
        }
      
        function __construct()
        {
            add_action( 'cmb2_admin_init', array($this,'mmcfrRegisterMetabox') );
        }
        
        function mmcfrRegisterMetabox() {
            
            global $meta_prifix;
            
            $mmcfr_meta = new_cmb2_box( array(
                                        'id'            => $meta_prifix . 'metabox',
                                        'title'         => esc_html__( 'Field Settings', 'mmcfr' ),
                                        'object_types'  => array( 'mmcfr-ratings', ), // Post type
                                        'cmb_styles' => true, // false to disable the CMB stylesheet
                                        )
                                    );
           
            //$mmcfr_meta->add_field( array(
            //                            'name'             => esc_html__( 'No of stars', 'mmcfr' ),
            //                            'id'               => $meta_prifix . 'mmcfr-stars-count',
            //                            'type'             => 'select',
            //                            'show_option_none' => false,
            //                            'default' => '5',
            //                            'options'          => array(
            //                                                '5'     => esc_html__( '5', 'mmcfr' ),
            //                                                '6'     => esc_html__( '6', 'mmcfr' ),
            //                                                '7'     => esc_html__( '7', 'mmcfr' ),
            //                                                '8'     => esc_html__( '8', 'mmcfr' ),
            //                                                '9'     => esc_html__( '9', 'mmcfr' ),
            //                                                '10'    => esc_html__( '10', 'mmcfr' ),
            //                                            ),
            //                            )
            //                      );
            
            $mmcfr_meta->add_field( array(
                                        'name'             => esc_html__( 'Select Style', 'mmcfr' ),
                                        'id'               => $meta_prifix . 'mmcfr-stars-style',
                                        'show_option_none' => false,
                                        'type'             => 'radio_image',
                                        'default' => 'fontawesome-stars',
                                         'options'          => array(
                                                            'bars-1to10'     => esc_html__( 'bars-1to10', 'mmcfr' ),
                                                            'bars-movie'     => esc_html__( 'bars-movie', 'mmcfr' ),
                                                            'bars-square'     => esc_html__( 'bars-square', 'mmcfr' ),
                                                            'bars-pill'     => esc_html__( 'bars-pill', 'mmcfr' ),
                                                            'bars-boxed'     => esc_html__( 'bars-boxed', 'mmcfr' ),
                                                            'fontawesome-stars'     => esc_html__( 'fontawesome-stars', 'mmcfr' ),
                                                        ),
                                        'images_path'      => MMCFR_IMAGE_DIR_PATH,
                                        'images'           => array(
                                            'bars-1to10'    => 'bars-1to10.png',
                                            'bars-movie'  => 'bars-movie.png',
                                            'bars-square' => 'bars-square.png',
                                            'bars-pill' => 'bars-pill.png',
                                            'bars-boxed' => 'bars-boxed.png',
                                            'fontawesome-stars' => 'fontawesome-stars.png',
                                        )
                                                                        

                                       
                                        )
                                  );
            $mmcfr_meta->add_field( array(
                          'name'       => esc_html__( 'Use default style', 'mmcfr' ),
                          'desc'     => 'If you want to customize it please uncheck it. Otherwise it customization has no effect',
                          'id'         => $meta_prifix . 'use-default-style',
                          'type'       => 'checkbox',
                          'default'    =>true
                          
                      )
                  );
            
            $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Color', 'mmcfr' ),
                                        //'desc'     => '',
                                        'id'         => $meta_prifix . 'color',
                                        'type'       => 'colorpicker',
                                        'attributes' => array(
                                                            'required'            => true, // Will be required only if visible.
                                                            'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                            'data-conditional-value' => wp_json_encode( array( 'bars-1to10','bars-movie', 'bars-pill','bars-boxed','fontawesome-stars' ) ),
                                                            ),
                                        'default' => '#c6c6c6',
                                    )
                                );
            
            $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Hover Color', 'mmcfr' ),
                                        //'desc'     => '',
                                        'id'         => $meta_prifix . 'hover-color',
                                        'type'       => 'colorpicker',
                                        'attributes' => array(
                                                            'required'            => true, // Will be required only if visible.
                                                            'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                            'data-conditional-value' => wp_json_encode( array( 'bars-1to10','bars-movie', 'bars-pill','bars-boxed','fontawesome-stars' ) ),
                                                            ),
                                        'default' => '#edb867',
                                    )
                                );
            
            $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Selected Color', 'mmcfr' ),
                                        //'desc'     => '',
                                        'id'         => $meta_prifix . 'selected-color',
                                        'type'       => 'colorpicker',
                                        'attributes' => array(
                                                            'required'            => true, // Will be required only if visible.
                                                            'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                            'data-conditional-value' => wp_json_encode( array( 'bars-1to10','bars-movie', 'bars-pill','bars-boxed','fontawesome-stars' ) ),
                                                            ),
                                        'default' => '#edb867',
                                    )
                                );
            
              $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Border Color', 'mmcfr' ),
                                        //'desc'     => '',
                                        'id'         => $meta_prifix . 'border-color',
                                        'type'       => 'colorpicker',
                                        'attributes' => array(
                                                            'required'            => true, // Will be required only if visible.
                                                            'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                            'data-conditional-value' => wp_json_encode( array('bars-square') ),
                                                            ),
                                        'default' => '#c6c6c6',
                                    )
                                );
              $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Text Color', 'mmcfr' ),
                                        //'desc'     => '',
                                        'id'         => $meta_prifix . 'text-color',
                                        'type'       => 'colorpicker',
                                        'attributes' => array(
                                                            'required'            => true, // Will be required only if visible.
                                                            'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                            'data-conditional-value' => wp_json_encode( array( 'bars-pill' ,'bars-square') ),
                                                            ),
                                        'default' => '#c6c6c6',
                                    )
                                );
            $mmcfr_meta->add_field( array(
                                 'name'       => esc_html__( 'Hover Text Color', 'mmcfr' ),
                                 //'desc'     => '',
                                 'id'         => $meta_prifix . 'hover-text-color',
                                 'type'       => 'colorpicker',
                                 'attributes' => array(
                                                     'required'            => true, // Will be required only if visible.
                                                     'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                     'data-conditional-value' => wp_json_encode( array( 'bars-pill' ,'bars-square') ),
                                                     ),
                                 'default' => '#c6c6c6',
                             )
                         );
            $mmcfr_meta->add_field( array(
                                 'name'       => esc_html__( 'Selected Text Color', 'mmcfr' ),
                                 //'desc'     => '',
                                 'id'         => $meta_prifix . 'selected-text-color',
                                 'type'       => 'colorpicker',
                                 'attributes' => array(
                                                     'required'            => true, // Will be required only if visible.
                                                     'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                                     'data-conditional-value' => wp_json_encode( array( 'bars-pill' ,'bars-square') ),
                                                     ),
                                 'default' => '#ffffff',
                             )
                         );
            $mmcfr_meta->add_field( array(
                         'name'       => esc_html__( 'Hover Border Color', 'mmcfr' ),
                         //'desc'     => '',
                         'id'         => $meta_prifix . 'hover-border-color',
                         'type'       => 'colorpicker',
                         'attributes' => array(
                                             'required'            => true, // Will be required only if visible.
                                             'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                             'data-conditional-value' => wp_json_encode( array('bars-square') ),
                                             ),
                         'default' => '#edb867',
                     )
                 );
            $mmcfr_meta->add_field( array(
                          'name'       => esc_html__( 'Selected Border Color', 'mmcfr' ),
                          //'desc'     => '',
                          'id'         => $meta_prifix . 'selected-border-color',
                          'type'       => 'colorpicker',
                          'attributes' => array(
                                              'required'            => true, // Will be required only if visible.
                                              'data-conditional-id' => $meta_prifix . 'mmcfr-stars-style',
                                              'data-conditional-value' => wp_json_encode( array('bars-square') ),
                                              ),
                          'default' => '#edb867',
                      )
                  );
                           
            $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Required', 'mmcfr' ),
                                        //'desc'     => '',
                                        'id'         => $meta_prifix . 'mmcfr-stars-required',
                                        'type'       => 'radio_inline',
                                        'options' => array(
                                                    '1' => __( 'Yes', 'mmcfr' ),
                                                    '0'   => __( 'No', 'mmcfr' ),
                                                ),
                                        'default' => '0',
                                    )
                                );
             $mmcfr_meta->add_field( array(
                                        'name'       => esc_html__( 'Required Message', 'mmcfr' ),
                                        //'desc'       => '',
                                        'id'         => $meta_prifix . 'mmcfr-stars-required-message',
                                        'type'       => 'text',
                                        'desc' => esc_html__('Use {{field_title}} to replace it with field name.')
                                    )
                                );

            
                                            
        }
    }
}
MMCFRMetaBoxes::get_instance();