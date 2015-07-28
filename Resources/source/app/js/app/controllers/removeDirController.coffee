###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class RemoveDir extends Controller
  constructor: ($scope, dirStructureService) ->
    @$scope = $scope
    @removedDir = $scope.dir
    @dirStructure = dirStructureService
    @errorString = ''

  showAlert: (responseData) =>
    @errorString = responseData.message
    return