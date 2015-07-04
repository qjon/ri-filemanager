###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Crop extends Directive
  constructor: ($timeout, configProvider) ->
    return {
      restrict: 'A'
      replace: true
      scope:
        file: "="
      link: (scope, element) ->
        scope.$on('ImageCrop:changeSize', (event, size) ->
          element.cropper 'setData', size
          element.cropper 'setAspectRatio', size.width / size.height
          return
        )

        timef = ->
          firstDimension = configProvider.availableDimensions[0]

          element.cropper(
            aspectRatio: firstDimension.width / firstDimension.height
            data:
              width: firstDimension.width
              height: firstDimension.height
            modal: true
            minWidth: firstDimension.width
            minHeight: firstDimension.height
            resizeable: true
            done: (data) ->
              scope.file.setCropData data.x, data.y, data.width, data.height
              return
          )
          return

        $timeout timef, 500
        return
    }