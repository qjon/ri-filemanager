###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Preview extends Directive
  constructor: ($document, previewService) ->
    return {
      restrict: 'E',
      replace: true,
      templateUrl: '/templates/preview.html'
      controller: 'previewController as previewCtrl'
      link: ($scope) ->
        $document.on 'keydown', (event) =>
          if previewService.isOpen()
            if event.keyCode == 39 && !previewService.file.getDirStructure().isLastFile(previewService.file)
              previewService.nextFile()
              $scope.$digest()
            else if event.keyCode == 37 && !previewService.file.getDirStructure().isFirstFile(previewService.file)
              previewService.prevFile()
              $scope.$digest()

    }
