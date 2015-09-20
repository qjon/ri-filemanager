module.exports = function (config) {

    config.set({
        basePath: '..',

        frameworks: ['jasmine'],

        // list of files / patterns to load in the browser
        files: [
            'bower_components/jquery/dist/jquery.js',
            'bower_components/bootstrap/dist/js/bootstrap.js',
            'bower_components/angular/angular.min.js',
            'bower_components/angular-mocks/angular-mocks.js',
            'bower_components/angular-route/angular-route.min.js',
            'bower_components/angular-animate/angular-animate.min.js',
            'bower_components/angular-resource/angular-resource.min.js',
            'bower_components/angular-strap/dist/angular-strap.min.js',
            'bower_components/angular-strap/dist/angular-strap.tpl.min.js',
            'bower_components/angular-translate/angular-translate.js',
            'bower_components/angular-growl-2/build/angular-growl.js',
            'bower_components/angular-translate-loader-static-files/angular-translate-loader-static-files.js',
            'bower_components/flow.js/dist/flow.js',
            'bower_components/ng-flow/dist/ng-flow.js',
            'bower_components/cropper/dist/cropper.min.js',
            'bower_components/lodash/dist/lodash.js',
            '../public/js/template.js',


            'app/js/modules/**/*.js',
            'temp/js/app/app.js',
            'temp/js/app/**/*.js',
            'tests/topWindowMock.coffee',
            'tests/routingMock.coffee',
            'tests/**/*.test.js',
            'tests/**/*.test.coffee'
        ],

        exclude: [],
        reporters: ['progress', 'junit', 'coverage'],
        junitReporter: {
            // will be resolved to basePath (in the same way as files/exclude patterns)
            outputFile: 'test-results.xml'
        },

        preprocessors: {
            'temp/js/**/*.js': ['coverage'],
            'tests/**/*.test.coffee': ['coffee'],
            'tests/routingMock.coffee': ['coffee']
        },

        coffeePreprocessor: {
            // options passed to the coffee compiler
            options: {
                bare: true,
                sourceMap: false
            },
            // transforming the filenames
            transformPath: function(path) {
                return path.replace(/\.coffee$/, '.js');
            }
        },

        coverageReporter: {
            type : 'html',
            dir : 'coverage/'
        },

        // web server port
        // CLI --port 9876
        port: 9876,

        // enable / disable colors in the output (reporters and logs)
        // CLI --colors --no-colors
        colors: true,

        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        // CLI --log-level debug
        logLevel: config.LOG_INFO,

        // enable / disable watching file and executing tests whenever any file changes
        // CLI --auto-watch --no-auto-watch
        autoWatch: true,

        // Start these browsers, currently available:
        // - Chrome
        // - ChromeCanary
        // - Firefox
        // - Opera
        // - Safari (only Mac)
        // - PhantomJS
        // - IE (only Windows)
        // CLI --browsers Chrome,Firefox,Safari
        browsers: ['PhantomJS'],

        // If browser does not capture in given timeout [ms], kill it
        // CLI --capture-timeout 5000
        captureTimeout: 10000,

        // Auto run tests on start (when browsers are captured) and exit
        // CLI --single-run --no-single-run
        singleRun: true,

        // report which specs are slower than 100ms
        // CLI --report-slower-than 100
        reportSlowerThan: 100,

        plugins: [
            'karma-jasmine',
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-phantomjs-launcher',
            'karma-junit-reporter',
            'karma-coverage',
            'karma-coffee-preprocessor'
        ]

    });
};
