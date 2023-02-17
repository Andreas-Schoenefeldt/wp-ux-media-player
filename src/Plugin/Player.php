<?php

namespace WpUxMediaPlayer\Plugin;

class Player
{
    protected const DEFAULTS = [
        'outerClasses' => [],
        'outerStyles' => '',
        'cover' => '',
        'title' => ''
    ];

    protected const WP_PLAYER_DEFAULTS = [
        'type' => 'audio',
        'style' => 'classic',
        'tracklist' => true,
        'tracknumbers' => true,
        'images' => false,
        'artists' => false
    ];

    protected array $attributes;

    /**
     *
     * [
        * 'id' => $id,
        * 'outerClasses' => $outer_cls,
        * 'outerStyles' => $outer_styles,
        * 'cover' => $cover,
        * 'title' => $title,
        * 'wpPlayerAttributes' => [
            * 'type'          => 'audio',
            * 'ids'			=> $ids, // attachment ids
            * 'style'         => empty( $playlist_style ) ? 'classic' : $playlist_style,
            * 'tracklist'     => true,
            * 'tracknumbers'  => empty( $tracknumbers ) || ( 'hide' != $tracknumbers )  ? true : false,
            * 'images'        => empty( $media_icon) || ( 'hide' != $media_icon )  ? true : false,
            * 'artists'       => empty( $artists ) || ( 'hide' != $artists )  ? true : false
     *   ]
     * ]
     *
     * @param array $attributes
     */
    public function __construct(
        array $attributes = []
    ) {

        $attributes['wpPlayerAttributes'] = array_merge(self::WP_PLAYER_DEFAULTS, $attributes['wpPlayerAttributes'] ?: []);

        $this->attributes = array_merge(self::DEFAULTS, $attributes);
        $this->attributes['outerClasses'][] = 'js-sh-widget';

        if (empty( $this->attributes['id'])) {
            $this->attributes['id'] = 'player-' . substr(md5(json_encode($this->attributes, JSON_THROW_ON_ERROR)), 0, 6);
        }
    }

    public function attach_assets (): void
    {
        global $GLOBALS;
        /** @var Plugin $plugin */
        $plugin = $GLOBALS['wp-ux-media-player'];

        //load css
        wp_enqueue_style( 'sh-module-audioplayer' , $plugin->getPluginFileUrl('enfold/shortcodes/audio-player/audio-player.css'));

        //load js
        wp_enqueue_script( 'sh-module-audioplayer' , $plugin->getPluginFileUrl('enfold/shortcodes/audio-player/audio-player.js'), ['jquery'], false, true );
    }


    public function get_markup (): string
    {

        $player = wp_playlist_shortcode( $this->attributes['wpPlayerAttributes'] );
        $ids = $this->attributes['wpPlayerAttributes']['ids'];

        $output = '<div id="' . $this->attributes['id'] . '" class="' . implode( ' ', $this->attributes['outerClasses'] ) . '" ' . $this->attributes['outerStyles'] . ' data-widgets="sh-ux-media-player" data-share_text="' . Plugin::get_translation('label.share-file') . '" data-download_text="' . Plugin::get_translation('label.download-file') . '" data-item_count="' . count($ids) . '">';

        if( !empty($this->attributes['cover']) || !empty($this->attributes['title']) ) {
            $output .=	'<div class="sh-player-cover-container">';
            if (!empty($this->attributes['cover'])) {
                $output .= '<div class="sh-player-cover">' . $this->attributes['cover'] . '</div>';
            }
            if (!empty($this->attributes['title'])) {
                $output .= '<div class="sh-title-container">';
                $output .= '<div class="sh-title">' . $this->attributes['title'] . '</div>';
                $output .= '<span class="sh-title-tracks">' . count($ids) . ' ' . Plugin::get_translation('label.audio-files') . '</span>';
                $output .= '</div>';
            }
            $output .=	'</div>';
        }

        $output .=		'<div class="sh-player-player-container">';
        $output .=			$player;
        $output .=		'</div>';
        $output .=		'<div class="sh-player__view-more trigger--more" data-view_less="' . Plugin::get_translation('label.view-less') . '"><span class="target--text">' . Plugin::get_translation('label.view-more') . '</span> <span class="sh-player__arrow-icon"></span></div>';

        $output .= '</div>';

        return $output;
    }

}