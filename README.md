# ri-filemanager
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
    },
    "repositories": [
        {
          "type": "vcs",
          "url": "https://github.com/qjon/ri-filemanager"
        }
    ]

2) Add to AppKernel.php
    
    
    public function registerBundles()
    {
        $bundles = array(
           ...
            new RI\FileManagerBundle\RIFileManagerBundle(),
        );

        ...
    }
    
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
                
                
### Dev mode
1) Install nodejs
2) Install grunt-cli as global

    npm install grunt-cli
    
3) Install all dependences

    cd vendor/qjon/ri-filemanager/RI/FileManagerBundle/Resources/source
    
    npm install
    
    grunt
    
        
* upload_dir (string) - folder in "web" folder in which the bundle will be stored uploaded files
* resize (bool) - should the api resized uploaded files if are too big
* reszie_max_width (int) - max image width (if resize is set to true)
* dimensions (array) - list of available dimensions (used in crop images) 

## Usage
##### Standalone version

if you want to use filemanager as standalone you should 