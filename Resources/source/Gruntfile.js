/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
module.exports = function (grunt) {

    var path = require('path');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        banner: '/*\n' +
            ' * This file is part of the RIFilemanagerBundle package.\n' +
            ' *\n' +
            ' * (c) Rafal Ignaszewski <https://github.com/qjon>\n' +
            ' *\n' +
            ' * For the full copyright and license information, please view the LICENSE\n' +
            ' * file that was distributed with this source code.\n' +
            ' */',
        bower: {
            options: {
                install: true,
                verbose: true,
                cleanTargetDir: true,
                cleanBowerDir: false,
                layout: 'byComponent'
            },
            dist: {
                options: {
                    targetDir: '<%= pkg.options.dist %>js/components'
                }
            }
        },
        clean: {
            prod: {
                src: ['<%= pkg.options.temp %>/', '<%= pkg.options.dist %>js/app.min.js', '<%= pkg.options.dist %>css/main.min.css'],
                options: {
                    force: true
                }
            }
        },
        copy: {
            css: {
                expand: true,
                flatten: true,
                src: [
                    '<%= pkg.options.src %>/../bower_components/bootstrap/dist/css/bootstrap.min.css',
                    '<%= pkg.options.src %>/../bower_components/font-awesome-bower/css/font-awesome.css',
                    '<%= pkg.options.src %>/../bower_components/bootstrap-additions/dist/bootstrap-additions.min.css',
                    '<%= pkg.options.src %>/../bower_components/cropper/dist/cropper.min.css',
                    '<%= pkg.options.src %>/../bower_components/angular-motion/dist/angular-motion.min.css'
                ],
                dest: '<%= pkg.options.dist %>/css/'
            },
            fonts: {
                expand: true,
                flatten: true,
                src: [
                    '<%= pkg.options.src %>/../bower_components/bootstrap/dist/fonts/*',
                    '<%= pkg.options.src %>/../bower_components/font-awesome-bower/fonts/*'
                ],
                dest: '<%= pkg.options.dist %>/fonts/'
            }
        },
        coffee: {
            dev: {
                expand: true,
                cwd: '<%= pkg.options.temp %>',
                src: ['**/*.coffee'],
                dest: '<%= pkg.options.temp %>',
                ext: '.js',

                options: {
                    bare: true
                }
            },
            prod: {
                expand: true,
                cwd: '<%= pkg.options.temp %>',
                src: ['**/*.coffee'],
                dest: '<%= pkg.options.temp %>',
                ext: '.js',

                options: {
                    bare: false
                }
            }
        },
        jade: {
            build: {
                files: {
                    '<%= pkg.options.src %>templates/': ['<%= pkg.options.src %>js/app/**/*.jade'],
                    '<%= pkg.options.src %>templates/modules/': ['<%= pkg.options.src %>js/modules/**/*.jade']
                },
                options: {
                    extension: '.html',
                    client: false,
                    pretty: true
                }
            }
        },
        karma: {
            unit: {
                configFile: 'karma/karma.conf.js'
            }
        },
        less: {
            dist: {
                options: {},
                files: {
                    "<%= pkg.options.dist %>css/main.min.css": "<%= pkg.options.src %>/css/main.less"
                }
            }
        },
        ngtemplates: {
            dist: {
                cwd: '<%= pkg.options.src %>',
                src: ['templates/*.html', 'templates/modules/*.html'],
                dest: '<%= pkg.options.dist %>js/template.js',
                options: {
                    standalone: true,
                    module: 'templates',
                    prefix: '/'
                }
            }
        },
        ngClassify: {
            app: {
                files: [
                    {
                        cwd: '<%= pkg.options.src %>',
                        src: '**/*.coffee',
                        dest: '<%= pkg.options.temp %>',
                        expand: true
                    }
                ],
                options: {
                    callback: function (filePath) {
                        return {appName: 'filemanager'};
                    }
                }
            }
        },
        uglify: {
            options: {
                mangle: false
            },
            files: {
                src: [
                    '<%= pkg.options.src %>js/modules/**/*.js',
                    '<%= pkg.options.temp %>js/app/app.js',
                    '<%= pkg.options.temp %>js/app/config.js',
                    '<%= pkg.options.temp %>js/app/filters/*.js',
                    '<%= pkg.options.temp %>js/app/directives/*.js',
                    '<%= pkg.options.temp %>js/app/models/*.js',
                    '<%= pkg.options.temp %>js/app/services.js',
                    '<%= pkg.options.temp %>js/app/services/*.js',
                    '<%= pkg.options.temp %>js/app/controllers/*.js'
                ],
                dest: '<%= pkg.options.dist %>js/app.min.js'
            },
            libs: {
                src: [
                    '<%= pkg.options.dist %>js/components/lodash/lodash.compat.js',
                    '<%= pkg.options.dist %>js/components/jquery/jquery.js',
                    '<%= pkg.options.dist %>js/components/bootstrap/js/bootstrap.min.js',
                    '<%= pkg.options.dist %>js/components/angular/angular.js',
                    '<%= pkg.options.dist %>js/components/angular-route/angular-route.js',
                    '<%= pkg.options.dist %>js/components/angular-animate/angular-animate.js',
                    '<%= pkg.options.dist %>js/components/angular-resource/angular-resource.js',
                    '<%= pkg.options.dist %>js/components/angular-strap/angular-strap.js',
                    '<%= pkg.options.dist %>js/components/angular-strap/angular-strap.tpl.js',
                    '<%= pkg.options.dist %>js/components/angular-translate/angular-translate.js',
                    '<%= pkg.options.dist %>js/components/angular-translate-loader-static-files/angular-translate-loader-static-files.js',
                    '<%= pkg.options.dist %>js/components/flow.js/flow.js',
                    '<%= pkg.options.dist %>js/components/ng-flow/ng-flow.js',
                    '<%= pkg.options.dist %>js/components/cropper/cropper.min.js'
                ],
                dest: '<%= pkg.options.dist %>js/lib.min.js'
            },
            angular: {
                src: [
                    '<%= pkg.options.dist %>js/components/lodash/lodash.compat.js',
                    '<%= pkg.options.dist %>js/components/angular/angular.js',
                    '<%= pkg.options.dist %>js/components/angular-route/angular-route.js',
                    '<%= pkg.options.dist %>js/components/angular-animate/angular-animate.js',
                    '<%= pkg.options.dist %>js/components/angular-resource/angular-resource.js',
                    '<%= pkg.options.dist %>js/components/angular-strap/angular-strap.js',
                    '<%= pkg.options.dist %>js/components/angular-strap/angular-strap.tpl.js',
                    '<%= pkg.options.dist %>js/components/angular-translate/angular-translate.js',
                    '<%= pkg.options.dist %>js/components/angular-translate-loader-static-files/angular-translate-loader-static-files.js',
                    '<%= pkg.options.dist %>js/components/flow.js/flow.js',
                    '<%= pkg.options.dist %>js/components/ng-flow/ng-flow.js',
                    '<%= pkg.options.dist %>js/components/cropper/cropper.min.js'
                ],
                dest: '<%= pkg.options.dist %>js/lib_ang.min.js'
            }
        },
        usebanner: {
            dist: {
                options: {
                    position: 'top',
                    banner: '<%= banner %>'
                },
                files: {
                    src: [ '<%= pkg.options.dist %>js/app.min.js', '<%= pkg.options.dist %>js/template.js', '<%= pkg.options.dist %>css/app.min.css' ]
                }
            }
        },
        watch: {
            ngClassify: {
                options: {
                    spawn: true
                },
                files: ['<%= pkg.options.temp %>js/*.coffee', '<%= pkg.options.src %>js/**/*.coffee'],
                tasks: ['ngClassify']
            },
            coffee: {
                options: {
                    spawn: true
                },
                files: ['<%= pkg.options.temp %>js/*.coffee', '<%= pkg.options.temp %>js/**/*.coffee'],
                tasks: ['coffee', 'uglify']
            },
            css: {
                options: {
                    spawn: true
                },
                files: ['<%= pkg.options.src %>/css/**', '<%= pkg.options.src %>/js/**/*.less'],
                tasks: ['less']
            },
            jade: {
                options: {
                    spawn: true
                },
                files: [ '<%= pkg.options.src %>/js/**/*.jade', '<%= pkg.options.src %>/../*.jade'],
                tasks: ['jade', 'ngtemplates']
            },
            all: {
                options: {
                    spawn: true
                },
                files: ['<%= pkg.options.src %>js/app.js', '<%= pkg.options.src %>/js/app/**/*.js', '<%= pkg.options.src %>/data/**/*.json'],
                tasks: ['uglify']
            }
        }
    });

    require('load-grunt-tasks')(grunt, { scope: 'devDependencies' });
    require('time-grunt')(grunt);


    grunt.registerTask('build', ['bower', 'clean', 'less', 'ngClassify', 'coffee:prod', 'uglify', 'jade', 'ngtemplates', 'usebanner', 'copy']);
    grunt.registerTask('dev', ['build', 'watch']);
    grunt.registerTask('karma-dev', ['ngClassify', 'coffee:dev', 'karma']);
    grunt.registerTask('default', ['build']);
};