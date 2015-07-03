###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class EditFile extends Controller
  constructor: ($scope, configProvider) ->
    @$scope = $scope
    @sizeList = configProvider.availableDimensions
    @size = @sizeList[0]

  isSize: (size) ->
    size == @size

  setSize: (size) ->
    @size = size
    @$scope.$broadcast 'ImageCrop:changeSize', @size
    return
