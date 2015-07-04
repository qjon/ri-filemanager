###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class AddDir extends Controller
  constructor: ($scope, $timeout, dirStructureService) ->
    @dirStructure = dirStructureService
    @folderName = ''
    @scope = $scope

    timeoutCallback = ->
      angular.element('input[name="folder_name"]').focus()

    $timeout timeoutCallback, 200

  addFolder: ->
    if @folderName != ''
      @dirStructure.addFolder @folderName, @scope.$hide
    return