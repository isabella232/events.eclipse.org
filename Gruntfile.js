module.exports = function(grunt) {
    // Initializing the configuration object
    grunt.initConfig({
        // Import package manifest
        pkg: grunt.file.readJSON("package.json"),

        meta: {
            banner: "/*\n" +
                " *  <%= pkg.title || pkg.name %> - v<%= pkg.version %>\n" +
                " *  <%= pkg.description %>\n" +
                " *\n" +
                " * Copyright (c) 2013-2016 Angelika Wittek (Munich, Germany).\n" +
                " * All rights reserved. This program and the accompanying materials\n" +
                " * are made available under the terms of the Eclipse Public License v1.0\n" +
                " * which accompanies this distribution, and is available at\n" +
                " * http://www.eclipse.org/legal/epl-v10.html\n" +
                " *\n" +
                " * Contributors:\n" +
                " *    Angelika Wittek\n" +
                " *    Ralph Müller (rm)\n" +
                " *    Christopher Guindon\n" +
                " */\n"
        },
        copy: {
            main: {
                files: [
                    // includes files within path
                    {
                        expand: true,
                        flatten: true,
                        src: ['bower_components/bootstrap/fonts/*'],
                        dest: 'assets/fonts/',
                        filter: 'isFile'
                    }, {
                        expand: true,
                        flatten: true,
                        src: ['bower_components/fontawesome/fonts/*'],
                        dest: 'assets/fonts/',
                        filter: 'isFile'
                    }, {
                        expand: true,
                        cwd: 'bower_components/solstice-assets/images/template',
                        src: ['**'],
                        dest: 'assets/images/template',
                        filter: 'isFile'
                    }, {
                        expand: true,
                        cwd: 'bower_components/solstice-assets/images/components/back-to-top',
                        src: ['**'],
                        dest: 'assets/images/components/back-to-top',
                        filter: 'isFile'
                    }
                ]
            }
        },
        less: {
            development: {
                options: {
                    compress: true
                        // minifying the result
                },
                files: {
                    // compiling styles.less into styles.css
                    "./assets/css/main.min.css": "./src/less/EclipseEvents.less"
                }
            }
        },
        concat: {
            options: {
                separator: ';',
            },
            js_plugin: {
                src: [
                    //'./bower_components/jquery/jquery.min.js',
                    './bower_components/leaflet/dist/leaflet.js',
                    './bower_components/leaflet.markercluster/dist/leaflet.markercluster.js'
                ],
                dest: './assets/js/plugins.js',
            },
            js_src: {
                src: [
                    './src/js/EclipseEvents.js',
                    './src/js/EclipseEventsConstants.js',
                ],
                dest: './assets/js/EclipseEvents.js',
            }
        },
        uglify: {
            options: {
                mangle: false,
                banner: "<%= meta.banner %>"
            },
            src: {
                files: {
                    './assets/js/EclipseEvents.min.js': './assets/js/EclipseEvents.js',
                }
            },
        },
        watch: {
            js_frontend: {
                files: ['./src/js/*.js'],
                // tasks to run
                tasks: ['concat:js_plugin', 'concat:js_src', 'uglify:src'],
            },
            less: {
                files: ['./src/less/*.less'],
                // watched files
                tasks: ['less', 'copy'],
                // tasks to run
            },
        }
    });
    // Plugin loading
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    // Task definition
    grunt.registerTask('default', ['watch']);
};