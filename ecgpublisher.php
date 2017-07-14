<?php
    /*
    Plugin Name: eyeCanGo publisher
    Plugin URI: http://www.eyecango.com/content/wp/publisher/ecgpublisher.zip
    Description: Enables easy blog post link submission to eyeCanGo Travel Blog Gallery.
    Version: 1.0.1.1
    Author: eyeCanGo Team
    Author URI: http://www.eyecango.com/
    */
    
    defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
    
    if ( ! class_exists( 'EyeCanGoPublisher' ) ) {
        class EyeCanGoPublisher
        {
            /**
             * Initiate the plugin by setting the default values and assigning any
             * required actions and filters.
             *
             * @access public
             */
            public function __construct()
            {
                add_action( 'admin_bar_menu', array( &$this, 'new_toolbar_items'), 999 );
                add_action( 'add_meta_boxes', array( &$this, 'add_ecgsubmit_metabox') );
            }
    
            function new_toolbar_items( $wp_admin_bar ) {
    
                if (is_single() || $this->is_edit_page('edit')){
                    $post = get_post();
    
                    if (isset($post) && $post->post_type =='post'){
    
                        $postPermaLink = get_permalink($post);
                        if (!$this->IsNullOrEmptyString($postPermaLink)){
    
                            $url = 'http://www.eyecango.com/home/submit?postUrl=' . urlencode($postPermaLink);
    
                            $args = array(
                                'id'    => 'ecgSubmission',
                                'title' => 'Submit to eyeCanGo',
                                'href'  => $url,
                                'meta'  => array( 'class' => 'my-toolbar-page', 'target'=> '_blank' )
                            );
                            $wp_admin_bar->add_node( $args );
                        }
                    }
                }
            }
    
            function add_ecgsubmit_metabox() {

                if (is_single() || $this->is_edit_page('edit')){
                    $post = get_post();
                    if (isset($post) && $post->post_type =='post'){
                        $postPermaLink = get_permalink($post);
                        if (!$this->IsNullOrEmptyString($postPermaLink)){
                              add_meta_box(
			                     'submit_to_eyecango'
			                     ,'Submit to eyeCanGo'
			                     ,array( $this, 'render_meta_box_content' )
			                     ,$post->post_type
			                     ,'side'
			                     ,'default'
		                    );
                        }
                    }
                }
            }

            function render_meta_box_content( $post ) {
                $postPermaLink = get_permalink($post);
                if (!$this->IsNullOrEmptyString($postPermaLink)){
    
                    $url = 'http://www.eyecango.com/home/submit?postUrl=' . urlencode($postPermaLink);

                    echo '<a href="' . $url . '" target="_blank" rel="external" title="Submit to eyeCanGo Travel Blog Gallery">';
                    echo '  <img style="width: 50%;padding-left: 25%;padding-right: 25%;" src="http://www.eyecango.com/content/eyecango_400x400.jpg"></img>';
                    echo '</a>';
                }
            }
    
            function IsNullOrEmptyString($question){
                return (!isset($question) || trim($question)==='');
            }
    
            /**
             * is_edit_page 
             * function to check if the current page is a post edit page
             * 
             * @author Ohad Raz <admin@bainternet.info>
             * 
             * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
             * @return boolean
             */
            function is_edit_page($new_edit = null){
                global $pagenow;
                //make sure we are on the backend
                if (!is_admin()) return false;
    
    
                if($new_edit == "edit")
                    return in_array( $pagenow, array( 'post.php',  ) );
                elseif($new_edit == "new") //check for new post page
                    return in_array( $pagenow, array( 'post-new.php' ) );
                else //check for either new or edit
                    return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
            }
        }
        new EyeCanGoPublisher;
    }
