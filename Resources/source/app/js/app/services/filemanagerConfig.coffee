###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Config extends Provider
  constructor: ->
    @configData =
      ###
       * Dir to file types icons thumbnails
      ###
      standAlone: true
      ###
       * Dir to file types icons thumbnails
      ###
      fileIconTypesDir: '/bundles/rifilemanager/img/icons/'
      ###
       * Not found file icon
      ###
      blankIconType: '_blank.png'
      ###
       * Mime types filters
      ###
      mimeTypes:
          images: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/png']
          audio: ['audio/mpeg', 'audio/x-ms-wma', 'audio/vnd.rn-realaudio', 'audio/x-wav']
          video: ['video/mpeg', 'video/mp4', 'video/quicktime', 'video/x-ms-wmv']
          archive: ['application/zip']
      ###
       * Callback function execute after select file
      ###
      filesSelectCallback: (files) ->
        file = files[0]
        windowManager = top.tinymce.activeEditor.windowManager

        windowManager.getParams().oninsert file
        windowManager.close()
        return

      ###
       * Callback function execute after select dir
      ###
      dirSelectCallback: null
      ###
       * List all available dimensions for edit images
      ###
      availableDimensions: [
        {
          name: 'Artykuł'
          width: 750
          height: 300
        }
        {
          name: 'Slider'
          width: 1140
          height: 350
        }
      ]

  setConfig: (data) ->
    angular.extend @configData, data

  $get: ->
    @configData