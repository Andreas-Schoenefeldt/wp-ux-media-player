<?php


namespace WpUxMediaPlayer\Plugin;

class Plugin {

    const version = '1.1.0';
    const plugin_name = 'wp-ux-media-player';
    const admin_page = 'wp-ux-media-player-admin';

    const defaultLocale = 'en';
    const availableLocales = ['en', 'de'];

    /**
     * @var string
     */
    private static $cssNamespace;
    /**
     * @var string
     */
    private static $jsNamespace;
    /**
     * @var string
     */
    private static $jsJwNamespace;

    private $pluginFile;

    public function __construct($pluginFile) {

        $this->pluginFile = $pluginFile;

        self::$cssNamespace = self::plugin_name . '-style';
        self::$jsNamespace = self::plugin_name . '-js';
        self::$jsJwNamespace = self::plugin_name . '-jw-js';

        // load the text domains
        add_action( 'plugins_loaded', [$this, 'initTranslations'], 0);
        add_filter( 'plugin_locale', [$this, 'setLocale'], 10, 2);

        // shortcode Area
        add_action( 'init', [$this, 'init']);
        // add_action( "admin_init", array( $this, "admin_init") );
        // add_action( 'wp_enqueue_scripts', array( $this, "init_assets") );
    }

    public function init () {
        if (class_exists('aviaShortcodeTemplate')) {
            // enfold theme shortcode link
            include_once ($this->getPluginFilePath('enfold/shortcodes/audio-player/audio-player.php'));
        } else {
            // default wordpress shortcodes
            // tbd
        }
    }

    public function initTranslations () {
        load_plugin_textdomain( $this::plugin_name, false, dirname(plugin_basename($this->pluginFile)) . '/languages/' );
    }

    /**
     * Enforces the use of an actual translated locale "en_US" locale for translations.
     *
     * This is necessary since we're using placeholder values for text instead of English text.
     * thx to: https://carlalexander.ca/placeholders-wordpress-translations/
     *
     * @param string $locale
     * @param string $domain
     *
     * @return string
     */
    public function setLocale ($locale, $domain) {
        if ($domain === $this::plugin_name) {
            if (!array_search($locale, $this::availableLocales)) {
                $localParts = explode('_', $locale);
                $locale = $localParts[0];

                if (!array_search($locale, $this::availableLocales)) {
                    $locale = $this::defaultLocale;
                }
            }

        }
        return $locale;
    }

    public static function getCacheDirBase() {
        return __DIR__ . '/../../../../wp-content/uploads/' . self::plugin_name .  '/cache/';
    }

    public function getPluginFileUrl($relativePath) {
        return plugins_url($relativePath, $this->pluginFile);
    }

    public function getPluginFilePath ($relativePath) {
        return dirname($this->pluginFile) . DIRECTORY_SEPARATOR . $relativePath;
    }

    public static function get_option($name, $default = '') {
        $options = get_option( self::plugin_name );
        return isset($options[$name]) ? $options[$name] : $default;
    }

    public static function get_translation ($key) {
        return __($key, self::plugin_name);
    }

    public function init_assets () {

        if (self::get_option('jw_url')) {
            wp_register_script(self::$jsJwNamespace, self::get_option('jw_url'));
            wp_enqueue_script(self::$jsJwNamespace);
        }

        wp_register_style(self::$cssNamespace, $this->getPluginFileUrl('assets/css/frontend.css'));
        wp_enqueue_style(self::$cssNamespace);

        wp_register_script(self::$jsNamespace . '-vimeo', 'https://player.vimeo.com/api/player.js');
        wp_enqueue_script(self::$jsNamespace. '-vimeo');

        wp_register_script(self::$jsNamespace, $this->getPluginFileUrl('assets/js/app.js'));
        wp_enqueue_script(self::$jsNamespace);
    }

    public function admin_init () {

    }

}