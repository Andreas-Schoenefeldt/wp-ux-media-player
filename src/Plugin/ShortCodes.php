<?php

namespace WpUxMediaPlayer\Plugin;

use Exception;

class ShortCodes
{


    /**
     * the `ux-audio-player` shortcode.
     * @param $attributes
     * @param $content
     * @return string
     */
    public static function audioPlayer($attributes, $content)
    {

        $mediaIds = array_map( function ($id) {
                return (int) trim($id);
            },
            explode(',', $attributes['media-ids'])
        );

        $player = new Player([

            'title' => $attributes['title'],

            'wpPlayerAttributes' => [
                'ids' => $mediaIds
            ]

        ]);

        $player->attach_assets();

        return $player->get_markup();
    }

}