###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class RemoveFile extends Controller
  constructor: ($scope) ->
    @$scope = $scope
    @file = $scope.file
    @errorString = ''

  showAlert: (responseData) ->
    this.errorString = responseData.message
    return