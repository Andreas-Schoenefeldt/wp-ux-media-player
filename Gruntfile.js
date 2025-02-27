'use strict';

const semver = require('semver');

module.exports = function(grunt) {
    const pkg = grunt.file.readJSON('package.json');
    const pluginName = pkg.name;
    const currentVersion = pkg.version;
    const webpackConf = require('./webpack.config');

    const gruntConf = {
        pkg: pkg,

        sass: {
            options: { implementation: require('sass')},
            ui: {
                files: [
                    {
                        expand: true,     // Enable dynamic expansion.
                        cwd: 'src/assets/scss/', // Src matches are relative to this path.
                        src: ['*.scss'], // Actual pattern(s) to match.
                        dest: 'enfold/shortcodes/audio-player/',   // Destination path prefix.
                        ext: '.css',   // Dest filepaths will have this extension.
                        extDot: 'first'   // Extensions in filenames begin after the first dot
                    }
                ]
            }
        },

        autoprefixer: { // adds vendor prefixes to the css
            core: {
                expand: true,
                flatten: true,
                src: 'assets/css/*.css',
                dest: 'assets/css/'
            }
        },

        webpack: {
            options: {
                mode: 'production'
            },

            core: webpackConf
        },

        watch: { // tracks changes of the watched files and rerunns the generation commands for development convenience
            scss: {
                files: ['src/assets/scss/**/*.scss'],
                tasks: ['sass', 'autoprefixer']
            },

            js: {
                files: ['src/assets/js/**/*.js', 'src/js/**/*.jsx'],
                tasks: ['webpack']
            }
        },

        translation_spreadsheet_sync: {
            options: {
                spreadsheetId: '1YQ8woWHWX6EYaTvn73ykog3I6_ZA34xq36cMhxtnxF4',  // The player translations:  https://docs.google.com/spreadsheets/d/1YQ8woWHWX6EYaTvn73ykog3I6_ZA34xq36cMhxtnxF4/edit#gid=0
                translationFormat: 'gettext',
                fileBaseName: 'wp-ux-media-player' // wordpress plugins are kind of requiring a certain namespace in the fileg
            },

            import: {
                options: {
                    mode: 'import'
                },
                files: {
                    src: 'languages/'
                },

            },
        },

        bump: {
            options: {
                files: ['package.json', pluginName + '.php', 'src/Plugin/Plugin.php'],
                commitFiles: ['-a'],
                pushTo: 'origin',
                globalReplace: true,
                regExp: /([\'|\"]?version[\'|\"]?[ ]*[:=][ ]*[\'|\"]?)(\d+\.\d+\.\d+(-false\.\d+)?(-\d+)?)[\d||A-a|.|-]*(['|"]?)/gmi
            }
        },
        prompt: {
            bump: {
                options: {
                    questions: [
                        {
                            config:  'bump.options.setVersion',
                            type:    'list',
                            message: 'Bump version from ' + '<%= pkg.version %>' + ' to:',
                            choices: [
                                {
                                    value: semver.inc(currentVersion, 'patch'),
                                    name:  'Patch:  ' + semver.inc(currentVersion, 'patch') + ' Backwards-compatible bug fixes.'
                                },
                                {
                                    value: semver.inc(currentVersion, 'minor'),
                                    name:  'Minor:  ' + semver.inc(currentVersion, 'minor') + ' Add functionality in a backwards-compatible manner.'
                                },
                                {
                                    value: semver.inc(currentVersion, 'major'),
                                    name:  'Major:  ' + semver.inc(currentVersion, 'major') + ' Incompatible API changes.'
                                },
                                {
                                    value: 'custom',
                                    name:  'Custom: ?.?.? Specify version...'
                                }
                            ]
                        },
                        {
                            config:   'bump.options.setVersion',
                            type:     'input',
                            message:  'What specific version would you like',
                            when:     function (answers) {
                                return answers['bump.options.setVersion'] === 'custom';
                            },
                            validate: function (value) {
                                var valid = semver.valid(value);
                                return !!valid || 'Must be a valid semver, such as 1.2.3-rc1. See http://semver.org/ for more details.';
                            }
                        }
                    ]
                }
            }
        },
        shell: {
            build: {
                command: [
                    'rm -rf vendor',
                    'composer install --no-dev --optimize-autoloader', // optimized autoloader for production builds
                    'rm -rf build',
                    'mkdir build',
                    'zip -r build/' + pluginName + '-<%= bump.options.setVersion %>.zip . -x "*.git*" -x "*node_modules/*" -x "*.idea*"',
                    'rm -rf vendor',
                    'composer install' // install the develop version again
                ].join('&&')
            }
        }
    };

    // Project configuration.
    grunt.initConfig(gruntConf);

    // load the grunt modules
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-webpack');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-prompt');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-bump');
    grunt.loadNpmTasks('grunt-translation-spreadsheet-sync');

    grunt.registerTask('ui', 'CSS and JS', function () {
        grunt.task.run('sass', 'autoprefixer', 'webpack');
    });

    grunt.registerTask('default', 'UI Development', function () {
        grunt.task.run('ui', 'watch');
    });

    grunt.registerTask('release', 'Production Release', function() {
        grunt.task.run('prompt', 'ui', 'bump', 'shell:build');
    });
};
