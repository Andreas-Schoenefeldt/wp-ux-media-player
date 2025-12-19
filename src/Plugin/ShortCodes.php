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

        $attributes = [
            'title' => $attributes['title'],
            'wpPlayerAttributes' => [
                'ids' => $mediaIds
            ]
        ];

        if (array_key_exists('collapse-from', $attributes)) {
            $attributes['collapseFrom'] = (int) $attributes['collapse-from'];
        }

        $player = new Player($attributes);

        $player->attach_assets();

        return $player->get_markup();
    }

}