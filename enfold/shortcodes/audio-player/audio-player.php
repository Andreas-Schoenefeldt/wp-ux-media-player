<?php
/**
 * Audio File Playlist Element
 * 
 * Shortcode that allows to add an audio playlist
 * 
 * @since 4.1.3
 */

use WpUxMediaPlayer\Plugin\Player;
use WpUxMediaPlayer\Plugin\Plugin;

if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly

if ( ! class_exists( 'sh_sc_audio_player' ) )
{
	
	class sh_sc_audio_player extends aviaShortcodeTemplate
	{
		/**
		 *
		 * @since 4.1.3
		 * @var int 
		 */
		static protected $instance = 0;
		
		/**
		 *
		 * @since 4.1.3
		 * @var string 
		 */
		protected $extra_style;
		
		/**
		 *
		 * @since 4.1.3
		 * @var string 
		 */
		static protected $non_ajax_style = '';
		
		
		/**
		 *
		 * @since 4.1.3
		 * @var array 
		 */
		protected $atts;

		/**
		 * 
		 * @since 4.1.3
		 * @param AviaBuilder $builder
		 */
		public function __construct( $builder )
		{
			parent::__construct( $builder );
			
			$this->extra_style = '';
			$this->atts = array();
		}
		
		/**
		 * 
		 * @since 4.1.3
		 */
		public function __destruct()
		{
			parent::__destruct();
			
			unset( $this->atts );
		}
		
		/**
		 * Create the config array for the shortcode button
		 * 
		 * @since 4.1.3
		 */
		public function shortcode_insert_button()
		{
			$this->config['version']		= '1.0';
			$this->config['self_closing']	= 'no';
			
			$this->config['name']			= __( 'SH Audio Player', 'avia_framework' );
			$this->config['tab']			= __( 'Media Elements', 'avia_framework' );
			$this->config['icon']			= AviaBuilder::$path['imagesURL'] . 'sc-audio-player.png';
			$this->config['order']			= 90;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode'] 		= 'sh_player';
			$this->config['shortcode_nested'] = array( 'av_playlist_element' );
			$this->config['tooltip'] 	    = __( 'Add a beautiful audio player element', 'avia_framework' );
			$this->config['tinyMCE'] 		= array( 'disable' => 'true' );
			$this->config['drag-level'] 	= 3;
			$this->config['preview']		= false;
			$this->config['disabling_allowed'] = true;
			$this->config['disabled']		= array(
									'condition'	=> ( avia_get_option( 'disable_mediaelement' ) == 'disable_mediaelement' ), 
									'text'		=> __( 'This element is disabled in your theme options. You can enable it in Enfold &raquo; Performance', 'avia_framework' )
								);
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'yes';				//	we use original code - not $meta										

		}

        function extra_assets() {

            global $GLOBALS;
            /** @var Plugin $plugin */
            $plugin = $GLOBALS['wp-ux-media-player'];

            //load css
            wp_enqueue_style( 'sh-module-audioplayer' , $plugin->getPluginFileUrl('enfold/shortcodes/audio-player/audio-player.css'), array('avia-layout'), false );

            //load js
            wp_enqueue_script( 'sh-module-audioplayer' , $plugin->getPluginFileUrl('enfold/shortcodes/audio-player/audio-player.js') , array('avia-shortcodes'), false, true );
        }
		
		
		/**
		* Popup Elements
		*
		* If this function is defined in a child class the element automatically gets an edit button, that, when pressed
		* opens a modal window that allows to edit the element properties
		*
		* @since 4.1.3
		* @return void
		*/
		public function popup_elements()
		{
			//if the element is disabled
			if( true === $this->config['disabled']['condition'] )
			{
				$this->elements = array(
					
					array(	
								'type'			=> 'template',
								'template_id'	=> 'element_disabled',
								'args'			=> array(
														'desc'	=> $this->config['disabled']['text']
													)
							),
						);

				return;
			}

			
			
			$this->elements = array(

				array(
						'type' 	=> 'tab_container', 
						'nodescription' => true
					),
				
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),
					
					array(
							'type'			=> 'template',
							'template_id'	=> 'toggle_container',
							'templates_include'	=> array(
													$this->popup_key( 'content_playlist' ),
													$this->popup_key( 'content_player' )
												),
							'nodescription' => true
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
						
				
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Styling', 'avia_framework' ),
						'nodescription' => true
					),
				
					array(
							'type'			=> 'template',
							'template_id'	=> 'toggle_container',
							'templates_include'	=> array(
													$this->popup_key( 'styling_player' ),
													$this->popup_key( 'styling_colors' )
												),
							'nodescription' => true
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
				
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Advanced', 'avia_framework' ),
						'nodescription' => true
					),
				
					array(
							'type' 	=> 'toggle_container',
							'nodescription' => true
						),
				
						array(	
								'type'			=> 'template',
								'template_id'	=> 'screen_options_toggle'
							),
				
						array(	
								'type'			=> 'template',
								'template_id'	=> 'developer_options_toggle',
								'args'			=> array( 'sc' => $this )
							),
				
					array(
							'type' 	=> 'toggle_container_close',
							'nodescription' => true
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)
		
			);
		}
		
		/**
		 * Create and register templates for easier maintainance
		 * 
		 * @since 4.6.4
		 */
		protected function register_dynamic_templates()
		{
			
			/**
			 * Content Tab
			 * ===========
			 */
			
			$c = array(

                        array(
                            "name"			=> Plugin::get_translation('widget.titles.playlist-title'),
                            "desc"			=> Plugin::get_translation('widget.descriptions.playlist-title'),
                            "id"			=> "title",
                            "type"			=> "input",
                            "std"			=> ''
                        ),
						
						array(	
								'type'				=> 'modal_group', 
								'id'				=> 'content',
								'container_class'	=> 'avia-element-fullwidth avia-multi-img',
								'modal_title'		=> __( 'Edit Form Element', 'avia_framework' ),
								'modal_open'		=> 'no',
								'trigger_button'	=> 'avia-builder-audio-edit',
								'add_label'			=> __( 'Add single audio', 'avia_framework' ),
								'disable_manual'	=> 'yes',
								'std'				=> array(),
								'creator'			=> array(
															'name'		=> __( 'Create and Edit Audio Playlist', 'avia_framework' ),
															'desc'		=> __( 'Here you can add new audio files to the playlist, remove files or reorder them.', 'avia_framework' ),
															'id'		=> 'id',
															'type'		=> 'audio_player',
															'state'		=> 'avia_insert_multi_audio',
															'title'		=> __( 'Add/Edit Audio Files', 'avia_framework' ),
															'button'	=> __( 'Insert Audio Files', 'avia_framework' ),
															'std'		=> ''
														),
								'subelements'		=> $this->create_modal()
						),
				
						array(	
							'name'			=> __( 'Autoplay', 'avia_framework' ),
							'desc'			=> __( 'Choose if the player starts on pageload or has to be started manually', 'avia_framework' ),
							'id'			=> 'autoplay',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array(
													__( 'Start manually', 'avia_framework' )	=> 'manual',
													__( 'Start on pageload', 'avia_framework' )	=> 'autoplay'
												)
						),
				
						array(	
							'name'			=> __( 'Loop playlist', 'avia_framework' ),
							'desc'			=> __( 'Choose if you want to stop after playing the list once or if you want to continue from beginning again. <strong>Since WP 5.2 Firefox does not stop when Enfold javascript file merging and compression is enabled. Other browsers work as expected.</strong>', 'avia_framework' ),
							'id'			=> 'loop',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array(
													__( 'Start from beginning again', 'avia_framework' )	=> '',
													__( 'Stop after playing last song', 'avia_framework' )	=> 'avia-playlist-no-loop'
												)
						),
				
						array(	
							'name'			=> __( 'Playlist Order', 'avia_framework' ),
							'desc'			=> __( 'Here you can select how to sort the playlist when rendering to the player on each pageload.', 'avia_framework' ),
							'id'			=> 'playorder',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array(
													__( 'Use order of playlist as selected', 'avia_framework' )	=> 'normal',
													__( 'Shuffle the playlist randomly', 'avia_framework' )		=> 'shuffle',
													/*__( 'Reverse the playlist', 'avia_framework' )			=> 'reverse'*/
												)
						)
						
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Playlist', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_playlist' ), $template );
			
			$c = array(
						
						array(	
							'name'			=> __( 'Choose a Cover Image', 'avia_framework' ),
							'desc'			=> __( 'Either upload a new or choose an existing image from your media library', 'avia_framework' ),
							'id'			=> 'cover_id',
							'fetch'			=> 'id',
							'type'			=> 'image',
							'title'			=> __( 'Choose a Cover Image', 'avia_framework' ),
							'button'		=> __( 'Choose a Cover Image', 'avia_framework' ),
							'std'			=> ''
						),
				
/*
						array(	
							'name'			=> __( 'Cover Image Location', 'avia_framework' ),
							'desc'			=> __( 'Here you can select where to show the cover for larger screens. On mobile devices the image will be centered above the player by default.', 'avia_framework' ),
							'id'			=> 'cover_location',
							'type'			=> 'select',
							'std'			=> 'top left',
							'subtype'		=> array(
													__( 'Hide the cover image', 'avia_framework' )				=> 'hide',
													__( 'Show above player left aligned', 'avia_framework' )	=> 'top left',
													__( 'Show above player centered', 'avia_framework' )		=> 'top center',
													__( 'Show above player right aligned', 'avia_framework' )	=> 'top right',
													__( 'Show left of player', 'avia_framework' )				=> 'aside left',
													__( 'Show right of player', 'avia_framework' )				=> 'aside right'
												)
						),	
						
						array(	
							'name'			=> __( 'Cover Image Size', 'avia_framework' ),
							'desc'			=> __( 'Choose image size for your cover.', 'avia_framework' ),
							'id'			=> 'cover_size',
							'type'			=> 'select',
							'std'			=> 'thumbnail',
							'required'		=> array( 'cover_location', 'not', 'hide' ),
							'subtype'		=>  AviaHelper::get_registered_image_sizes( array(), false, true )		
						),

				
						
						
						array(	
							'name'			=> __( 'Playlist styling', 'avia_framework' ),
							'desc'			=> __( 'Here you can select the styling of the playlist', 'avia_framework' ),
							'id'			=> 'playlist_style',
							'type'			=> 'select',
							'std'			=> 'light',
							'subtype'		=> array(
													__( 'Light', 'avia_framework' )	=> 'light',
													__( 'Dark', 'avia_framework' )	=> 'dark'
												)
						),	
						
						*/
				
						array(	
							'name'			=> __( 'Tracklist', 'avia_framework' ),
							'desc'			=> __( 'Here you can select to show or hide the tracklist', 'avia_framework' ),
							'id'			=> 'tracklist',
							'type'			=> 'select',
							'std'			=> 'show',
							'subtype'		=> array(
													__( 'Show tracklist', 'avia_framework' )	=> 'show',
													__( 'Hide tracklist', 'avia_framework' )	=> 'hide'
												)
						),	
				
						array(	
							'name'			=> __( 'Tracknumbers', 'avia_framework' ),
							'desc'			=> __( 'Here you can select to show or hide the tracknumbers next to entries in the playlist', 'avia_framework' ),
							'id'			=> 'tracknumbers',
							'type'			=> 'select',
							'std'			=> 'show',
							'required'		=> array( 'tracklist', 'equals', 'show' ),
							'subtype'		=> array(
													__( 'Show tracknumbers', 'avia_framework' )	=> 'show',
													__( 'Hide tracknumbers', 'avia_framework' )	=> 'hide'
												)
						),	
				
						array(	
							'name'			=> __( 'Artists Name', 'avia_framework' ),
							'desc'			=> __( 'Here you can select to show or hide the artists name in the playlist', 'avia_framework' ),
							'id'			=> 'artists',
							'type'			=> 'select',
							'std'			=> 'show',
							'required'		=> array( 'tracklist', 'equals', 'show' ),
							'subtype'		=> array(
													__( 'Show artists name', 'avia_framework' )	=> 'show',
													__( 'Hide artists name', 'avia_framework' )	=> 'hide'
												)
						),	
				
						array(	
							'name'			=> __( 'Media Icon/Album Cover', 'avia_framework' ),
							'desc'			=> __( 'Here you can select to show or hide the media icon in the playlist. This icon can be set in the media gallery for each element as the featured image. WP will use a default icon on upload, if none is set.', 'avia_framework' ),
							'id'			=> 'media_icon',
							'type'			=> 'select',
							'std'			=> 'show',
							'subtype'		=> array(
													__( 'Show media icon/album cover', 'avia_framework' )		=> 'show',
													__( 'Hide', 'avia_framework' ) => 'hide'
												)
						),
						
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Player', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_player' ), $template );
			
			
			/**
			 * Content Tab
			 * ===========
			 */
			
			$c = array(
						array(	
							'name'			=> __( 'Player styling', 'avia_framework' ),
							'desc'			=> __( 'Here you can select the general appearance of the player', 'avia_framework' ),
							'id'			=> 'player_style',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array(
													__( 'Classic (boxed)', 'avia_framework' )	=> 'classic',
													__( 'Minimal (borderless, no background)', 'avia_framework' )	=> 'minimal',
												)
							),	
				
						
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Player', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_player' ), $template );
			
			
			$c = array(
						array(	
							'name'			=> __( 'Font Color', 'avia_framework' ),
							'desc'			=> __( 'Select a font color', 'avia_framework' ),
							'id'			=> 'font_color',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array( 
													__( 'Default Color', 'avia_framework' )	=> '', 
													__( 'Custom Color', 'avia_framework' )	=> 'custom-font-color'
												)
						), 
					
						array(	
							'name'			=> __( 'Custom Font Color', 'avia_framework' ),
							'desc'			=> __( 'Select a custom font color for your Player here', 'avia_framework' ),
							'id'			=> 'custom_font_color',
							'type'			=> 'colorpicker',
							'std'			=> '',
							'rgba'			=> true,
							'required'		=> array( 'font_color', 'equals', 'custom-font-color' )
						),
				
						array(	
							'name'			=> __( 'Background Color', 'avia_framework' ),
							'desc'			=> __( 'Select a background color', 'avia_framework' ),
							'id'			=> 'background_color',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array( 
													__( 'Default Color', 'avia_framework' )	=> '', 
													__( 'Custom Color', 'avia_framework' )	=> 'custom-background-color'
												)
						), 
				
						array(	
							'name'			=> __( 'Custom Background Color', 'avia_framework' ),
							'desc'			=> __( 'Select a custom background color for your Player here', 'avia_framework' ),
							'id'			=> 'custom_background_color',
							'type'			=> 'colorpicker',
							'std'			=> '',
							'rgba'			=> true,
							'required'		=> array( 'background_color', 'equals', 'custom-background-color' )
						),
						
						array(	
							'name'			=> __( 'Border Color', 'avia_framework' ),
							'desc'			=> __( 'Select a border color', 'avia_framework' ),
							'id'			=> 'border_color',
							'type'			=> 'select',
							'std'			=> '',
							'subtype'		=> array( 
													__( 'Default Color', 'avia_framework' )	=> '', 
													__( 'Custom Color', 'avia_framework' )	=> 'custom-border-color'
												)
						), 
				
						array(	
							'name'			=> __( 'Custom Border Color', 'avia_framework' ),
							'desc'			=> __( 'Select a custom background color for your Player here', 'avia_framework' ),
							'id'			=> 'custom_border_color',
							'type'			=> 'colorpicker',
							'rgba'			=> true,
							'std'			=> '',
							'required'		=> array( 'border_color', 'equals', 'custom-border-color' )
						),
				
						
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Colors', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_colors' ), $template );
			
			
		}
		
		/**
		 * Creates the modal popup for a single entry
		 * 
		 * @since 4.6.4
		 * @return array
		 */
		protected function create_modal()
		{
			$elements = array(
				
				array(
						'type' 	=> 'tab_container', 
						'nodescription' => true
					),
						
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),
					
					//	Dummy element only to avoid notices
					array(	
							'name'		=> __('Which type of media is this?', 'avia_framework' ),
							'id'		=> 'audio_type',
							'type'		=> 'select',
							'std'		=> 'audio',
							'subtype'	=> array(   
												__( 'Audio File', 'avia_framework' )	=> 'audio',
												__( 'Video File', 'avia_framework' )	=> 'video',
											)
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
				
				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)
				
				
				);
			
			return $elements;
		}
		
		
		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 *
		 * @since 4.1.3
		 * @param array $params			this array holds the default values for $content and $args. 
		 * @return $params				the return array usually holds an innerHtml key that holds item specific markup.
		 */
		public function editor_element( $params )
		{	
			$element = $this->get_popup_element_by_id( 'autoplay' );
			
			/**
			 * Element has been disabled with option 'Disable self hosted video and audio features'
			 */
			if( false === $element )
			{
				return $params;
			}
			
			$playmodes = $element['subtype'];
			
			$update_template =	'<span class="av-player-{{autoplay}}">';
			
			foreach( $playmodes as $info => $playmode )
			{
				$update_template .=		'<span class="av-play-' . $playmode . '">' . $info . '</span>';
			}
			
			$update_template .=	'</span>';
			
			
			$update	= $this->update_template( 'autoplay', $update_template );
			
			$selected = empty( $params['args']['autoplay'] ) ? 'manual' : $params['args']['autoplay'];
			$template = str_replace('{{autoplay}}', $selected, $update_template );
												
			$params['innerHtml'] = "<img src='{$this->config['icon']}' title='{$this->config['name']}' />";
			$params['innerHtml'].= "<div class='av-player'>{$this->config['name']} -  <span {$update}>{$template}</span></div>";
			
			return $params;
		}
		
		/**
		 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
		 * Works in the same way as Editor Element
		 * 
		 * @since 4.1.3
		 * @param array $params		this array holds the default values for $content and $args. 
		 * @return array			the return array usually holds an innerHtml key that holds item specific markup.
		 */
		public function editor_sub_element( $params )
		{	

			$img_template 		= $this->update_template( 'img_fakeArg', '{{img_fakeArg}}' );
			$title				= $this->update_template( 'title_info', '{{title_info}}' );
			$artist				= $this->update_template( 'artist', '{{artist}}' );
			$album				= $this->update_template( 'album', '{{album}}' );
			$description		= $this->update_template( 'description', '{{description}}' );
			$filename 			= $this->update_template( 'filename', '{{filename}}' );
			$id					= $this->update_template( 'id', '{{id}}' );
			$filelength			= $this->update_template( 'filelength', '{{filelength}}' );
			
			$title_info		= isset( $params['args']['title'] ) ? $params['args']['title'] : '';
			$thumbnail		= isset( $params['args']['icon'] ) ? '<img src="' . $params['args']['icon'] .  '" title="' . esc_attr(  $title_info ) . '" alt="" />' : "";
			$album_info		= isset( $params['args']['album'] ) && 'undefined' != $params['args']['album'] ?  $params['args']['album'] : '';
			$desc_info		= isset( $params['args']['description'] ) && 'undefined' != $params['args']['description'] ?  $params['args']['description'] : '';
			$file_info		= isset( $params['args']['filename'] ) && 'undefined' != $params['args']['filename'] ?  $params['args']['filename'] : '';
			$id_info		= isset( $params['args']['id'] ) && 'undefined' != $params['args']['id'] ? $params['args']['id'] : '0';
			
			$main = '<span class="avia-audiolist-title" '. $title . '>';
			
			if( isset( $params['args']['title'] ) && 'undefined' != $params['args']['title'] )
			{
				$main .=	'<span class="avia-known-title">' . $params['args']['title'];
			}
			else
			{
				$main .=	'<span class="avia-unknown-title">' . __( ' Unknown ' , 'avia_framework' );
			}
			$main .=		'</span></span>';	
			
			/**
			 * Currently WP does not return artist when editing an existing playlist in popup playlist editor.
			 * 
			 * This might change in future -> then uncomment the following lines to show the artist
			 */
//			$main .=		'<span class="avia_audiolist-by">' . __( ' by ' , 'avia_framework' ) . '</span>';
//			
//			if( isset( $params['args']['artist'] ) && 'undefined' != $params['args']['artist'] )
//			{
//				$main .=	'<span class="avia-audiolist-artist" ' . $artist . '>' . $params['args']['artist'];
//			}
//			else
//			{
//				$main .=	'<span class="avia-audiolist-artist avia-unknown" ' . $artist . '>' . __( ' unknown ' , 'avia_framework' );
//			}
//			$main .= '</span>';
			
			if( isset( $params['args']['filelength'] ) )
			{
				$main .=	' (<span class="avia-audiolist-length" ' . $filelength . '>' . $params['args']['filelength'];
			}
			else
			{
				$main .=	' (<span class="avia-audiolist-length avia-unknown" ' . $filelength . '>' . __( ' ??? ' , 'avia_framework' );
			}
			$main .= '</span>)';
			

			$params['innerHtml']  = '';
			$params['innerHtml'] .= "<div class='avia_title_container'>";
			$params['innerHtml'] .=		'<div ' . $this->class_by_arguments( 'audio_type' ,$params['args'] ) . '>';
			$params['innerHtml'] .=			"<span class='avia_audiolist_image' {$img_template} >{$thumbnail}</span>";
			$params['innerHtml'] .=			"<div class='avia_audiolist_content'>";
			$params['innerHtml'] .=				"<h4 class='avia_title_container_inner'>{$main}</h4>";
			$params['innerHtml'] .=				"<p class='avia_content_album' {$album}>" . stripslashes( $album_info ) . "</p>";
			$params['innerHtml'] .=				"<p class='avia_content_description' {$description}>" . stripslashes( $desc_info ) . "</p>";
			$params['innerHtml'] .=				"<small class='avia_audio_url' {$filename}>" . stripslashes( $file_info ) . "</small>";
			$params['innerHtml'] .=			'</div>';
			$params['innerHtml'] .=			"<div class='hidden-attachment-id' style='display: none;' {$id}>{$id_info}</div>";
			$params['innerHtml'] .=		'</div>';
			$params['innerHtml'] .= '</div>';

			return $params;
		}
			
		/**
		 * Frontend Shortcode Handler
		 *
		 * @since 4.1.3
		 * @param array $atts array of attributes
		 * @param string $content text within enclosing form of shortcode element 
		 * @param string $shortcodename the shortcode found, when == callback name
		 * @return string $output returns the modified html string 
		 */
		public function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
		{
			extract( AviaHelper::av_mobile_sizes( $atts ) ); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
			
			$this->extra_style = '';
			$this->atts = shortcode_atts( array(
									'handle'			=> $shortcodename,
									'id'				=> '',
									'title'             => '',
									'autoplay'			=> 'manual',
									'loop'				=> '',
									'playorder'			=> 'normal',
									'player_style'		=> 'classic',
									'cover_id'			=> '',
									'cover_size'		=> 'thumbnail',
									'cover_location'	=> 'hide',
									'playlist_style'	=> 'light',
									'tracklist'			=> 'show',
									'tracknumbers'		=> 'show',
									'artists'			=> 'show',
									'media_icon'		=> 'show',
									'font_color'		=> '',
									'custom_font_color'	=> '',
									'background_color'	=> '',
									'custom_background_color'	=>'',
									'border_color'		=> '',
									'custom_border_color'		=> '',

									'content'			=> ShortcodeHelper::shortcode2array( $content, 1 )

							), $atts, $this->config['shortcode'] );
		
			//replace some values that are removed for simplicity with defaults. can be later changed if user request those features
			$this->atts['cover_location'] = 'hide';
			$this->atts['playlist_style'] = 'light';
			
			if( $this->atts['media_icon'] == 'cover') 
			{
				$this->atts['media_icon'] = 'show';
			}
		
			/**
			 * Replace empty default values so we can later use them to add classes
			 */
			if( empty( $this->atts['id'] ) )
			{
				$this->atts['id'] = $this->config['shortcode'] . '-' . sh_sc_audio_player::$instance;
			}
			
			sh_sc_audio_player::$instance++;
			
			if( empty( $this->atts['player_style'] ) )
			{
				$this->atts['player_style'] = 'classic';
			}

			extract( $this->atts );
			
			/**
			 * Return if no playlist defined
			 */
			if( empty( $content ) )
			{
				return '';
			}
			
			$ids = array();
			foreach( $content as $key => $audio )
			{
				$ids[] = $audio['attr']['id'];
			}
			
			if( 'shuffle' == $playorder )
			{
				shuffle( $ids );
			}
			else if( 'reverse' == $playorder )
			{
				$ids = array_reverse( $ids );
			}
			
			/**
			 * With WP 5.2 we need to show tracklist and hide with CSS to allow stop of loop
			 */
			$args = array(
					'type'          => 'audio',
					'ids'			=> $ids,
					'style'         => empty( $playlist_style ) ? 'classic' : $playlist_style,
					'tracklist'     => true,			
					'tracknumbers'  => empty( $tracknumbers ) || ( 'hide' != $tracknumbers )  ? true : false,
					'images'        => empty( $media_icon) || ( 'hide' != $media_icon )  ? true : false,
					'artists'       => empty( $artists ) || ( 'hide' != $artists )  ? true : false
				);
			
			
			if( ( $media_icon == 'show' ) && ( is_numeric( $cover_id  ) ) )
			{
				add_filter( 'wp_get_attachment_image_src', array( $this, 'handler_wp_get_attachment_image_src' ), 10, 4 );
				add_filter( 'wp_mime_type_icon', array( $this, 'handler_wp_mime_type_icon' ), 10, 3 );
			}
			
			if( ( $media_icon == 'show' ) && ( is_numeric( $cover_id  ) ) )
			{
				remove_filter( 'wp_get_attachment_image_src', array( $this, 'handler_wp_get_attachment_image_src' ), 10 );
				remove_filter( 'wp_mime_type_icon', array( $this, 'handler_wp_mime_type_icon' ), 10 );
			}
			
			$output  = '';
			
			$outer_cls = array( 
								'sh-player',
								'sh-player-container',
								$av_display_classes,
								$meta['el_class']
							);
			
			if( 'hide' == $tracklist )
			{
				$outer_cls[] = 'sh-player-hide-playlist';
			}
			
			if( 1 == sh_sc_audio_player::$instance )
			{
				$outer_cls[] = 'first';
			}
			
			if( ! empty( $loop ) )
			{
				$outer_cls[] = $loop;
			}
			
			$outer_styles = array();
			
			$outer_cls[] = 'avia-playerstyle-' . $player_style;
			
			if( $autoplay == 'autoplay' )
			{
				$outer_cls[] = 'avia-playlist-autoplay';
			}
			
			if( ( $font_color == 'custom-font-color') && ( ! empty( $custom_font_color ) ) )
			{
				$outer_cls[] = 'avia-playlist-' . $font_color;
				$outer_styles[] = 'color:' . $custom_font_color . ';';
			}
			
			if( ( $background_color == 'custom-background-color') && ( ! empty( $custom_background_color ) ) )
			{
				$outer_cls[] = 'avia-playlist-' . $background_color;
				$outer_styles[] = 'background-color:' . $custom_background_color . ';';
			}
			
			if( ( $border_color == 'custom-border-color') && ( ! empty( $custom_border_color ) ) )
			{
				$outer_cls[] = 'avia-playlist-' . $border_color;
				$outer_styles[] = 'border-color:' . $custom_border_color . ';';
				
				//generate thumb width based on columns
				$this->extra_style .= "<style type='text/css'>";
				$this->extra_style .= "#top #wrap_all #{$id} .wp-playlist-item {border-color:{$custom_border_color};}";
				$this->extra_style .= '</style>';
				
				if(!empty($this->extra_style))
				{
					if(!empty($atts['ajax_request']) || !empty($_POST['avia_request']))
					{
						$output .= $this->extra_style;
						$this->extra_style = '';
					}
					else
					{
						sh_sc_audio_player::$non_ajax_style .= $this->extra_style;
						if( false === has_action( 'wp_footer', array( $this, 'print_extra_style' ) ) )
						{
							add_action( 'wp_footer', array( $this, 'print_extra_style' ) );
						}
					}
				}
				
			}
			
			$cover = '';
			if( ( $cover_location != 'hide' ) && ( ! empty( $cover_id ) ) )
			{
				$outer_cls[] = 'avia-playlist-show-cover';
				$outer_cls[] = $cover_location;
				$cover = wp_get_attachment_link( $cover_id, $cover_size ); 
			}
			else
			{
				$outer_cls[] = 'avia-playlist-hide-cover';
			}
			
			if( ! empty( $outer_styles ) )  {
				$outer_styles = ' style="' . implode( '', $outer_styles ) . '" ';
			} else {
				$outer_styles = '';
			}

            $player = new Player([
                'id' => $id,
                'outerClasses' => $outer_cls,
                'outerStyles' => $outer_styles,
                'mediaIds' => $ids,
                'cover' => $cover,
                'title' => $title,
                'wpPlayerAttributes' => $args
            ]);
			
			$output .= $player->get_markup();
			
		
			return $output;
		}
		
		
		/**
		 * If user uploads an image for tbe player this image will overwrite any preset featured image.
		 * To speed up the code the filter should only be activated when images should be exchanged
		 * 
		 * @since 4.1.3
		 * @param array|false  $image         Either array with src, width & height, icon src, or false.
		 * @param int          $attachment_id Image attachment ID.
		 * @param string|array $size          Size of image. Image size or array of width and height values
		 *                                    (in that order). Default 'thumbnail'.
		 * @param bool         $icon          Whether the image should be treated as an icon. Default false.
		 * @return array|false
		 */
		public function handler_wp_get_attachment_image_src( $image, $attachment_id, $size, $icon )
		{
			static $recursive = 0;
			
			/**
			 * To avoid duplicating code we call the original function. Avoid endless recursions.
			 */
			if( $recursive > 0 )
			{
				return $image;
			}
			
			$new_id = $this->atts['cover_id'];
			
			if( empty( $new_id ) || ( ! is_numeric( $new_id ) ) || ( (int) $new_id == $attachment_id ) )
			{
				return $image;
			}
			
			$recursive++;
			
			$image = wp_get_attachment_image_src( $new_id, $size, $icon );
			
			$recursive--;
			
			return $image;
		}
		
		/**
		 * If user uploads an image for tbe player this image will overwrite the default mime icon from WP if no featured image was assigned
		 * to the media file.
		 * To speed up the code the filter should only be activated when images should be exchanged
		 * 
		 * @since 4.1.3
		 * @param string	$icon
		 * @param string	$mime
		 * @param int		$post_id
		 * @return string
		 */
		public function handler_wp_mime_type_icon( $icon, $mime, $post_id )
		{
			$new_id = $this->atts['cover_id'];
			
			if( empty( $new_id ) || ! is_numeric( $new_id ) )
			{
				return $icon;
			}
			
			$image = wp_get_attachment_image_src( $new_id, 'thumbnail', false );
			return is_array( $image ) ? $image[0] : '';
		}
		
		/**
		 * @since 4.1.3
		 */
		public function print_extra_style()
		{
			echo sh_sc_audio_player::$non_ajax_style;
		}

	}		//	end class definition
	
}
