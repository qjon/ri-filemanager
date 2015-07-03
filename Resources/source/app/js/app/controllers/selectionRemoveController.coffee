###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class SelectionRemove extends Controller
  constructor: ($scope, copyPasteService) ->
    @$scope = $scope
    @files = $scope.files
    @dirs = $scope.dirs
    @errorString = ''
    @copyPasteService = copyPasteService

  showAlert: (responseData) ->
    @errorString = responseData.message
    return