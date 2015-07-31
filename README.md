# ri-filemanager ![alt=""](https://travis-ci.org/qjon/ri-filemanager.svg)
Symfony2 filemanager bundle

## Requirements
##### JavaScript
* lodash
* jquery
* bootstrap
* angular
* angular-route
* angular-animate
* angular-resource
* angular-strap
* angular-translate
* angular-translate-loader-static-files
* ng-flow
* cropper

##### PHP
* stfalcon/tinymce-bundle
* friendsofsymfony/jsrouting-bundle
 
## Instalation

This tool is ready to use, you dont need to do anything

1) Add to your composer.json

    ...,
    "require": {
        ...,
        "qjon/ri-filemanager": "dev-master"
    }

2) Add to AppKernel.php
    
    
    public function registerBundles()
    {
        $bundles = array(
           ...
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new RI\FileManagerBundle\RIFileManagerBundle(),
        );

        ...
    }
    
The first bundle is used to use Symfony routing in JS, the second is our filemanager bundle.  
    
3) Add routing
    
    RIFileManagerBundle:
        resource: "@RIFileManagerBundle/Resources/config/routing.yml"
        prefix: /filemanager
        
4) Set proper configuration in app/config.yml

    assetic:
        ...
        bundles: ["RIFileManagerBundle"]
    
    ...
       
    ri_file_manager:
        upload_dir: /uploads
        resize: true
        resize_max_width: 1600
        dimensions: 
            -
                name: Crop size one
                width: 800
                height: 500
            -
                name: Crop size two
                width: 1200
                height: 400
                
* __upload_dir__ (string) - name of dir in _web_ directory when will be upload all files
* __resize__ (bool) - all uploaded image will be resized if they are too large
* __resize_max_width__ (int) - works only with _resize_ = _true_
* __dimensions__ (array) - predefined list of available crop dimensions

## Usage
##### Standalone version

This is the simple way to use this bundle. In your twig template you should include:

1) CSS template (include all CSS files)
 
    {% include 'RIFileManagerBundle:Default:css.html.twig' %}
 
2) JS template (include third part libraries, application and templates)

    {% include 'RIFileManagerBundle:Default:javascript_min.html.twig' %}

Then you should initialize application where you can set some configuration 
    
    <script>
        var fm = angular.module('fm', ['filemanager'])
                .config(['configProviderProvider', function (ConfigProvider) {
                    ConfigProvider.setConfig({{ filemanager_configuration|json_encode|raw }})
                }]);
    </script>
    
    <div class="filemanager" ng-app="fm">
        <h1>{% verbatim %}{{'FILEMANAGER' | translate}}{% endverbatim %}</h1>
        <div class="animation" ng-view  style="width: 100%;"></div>
    </div>

All above configuration you find in _Resources/views/Default/index.html.twig_  


##### TinyMce file and image plugin

This bundle can be used as TinyMce file and image plugin. First you should prepare page with TinyMce editor. 
You can use _stfalcon/tinymce-bundle_ (read installation manual on https://github.com/stfalcon/TinymceBundle).

If you have working example of TinyMce editor, you can attach _filemanager_ plugin.

    stfalcon_tinymce:
        ...
        theme:
           advanced:
                ...
                file_browser_callback: 'myFileBrowser'
                ...

Then you should include tinymce JS plugin file and routing files, which open select image dialog.

    <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
    <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
    <script src="{{ asset('bundles/rifilemanager/js/tinymce_plugin.js') }}"></script>
    
or simple one line
    
    {% include 'RIFileManagerBundle:Default:javascript_tinymce.html.twig' %}

    
After that you should change filemanager application configuration and set non stand alone version.

    <script>
        var fm = angular.module('fm', ['filemanager'])
            .config(['configProviderProvider', function (configProviderProvider) {
                configProviderProvider.setConfig({
                    standAlone: false
                })
            }]);
    </script>
    
That is all, everything should work. 
