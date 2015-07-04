###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class EditDir extends Controller
  constructor: ($scope, $timeout) ->
    @dir = $scope.dir;
    @folderName = @dir.name;
    @orgName = @folderName;


    timeoutCallback = ->
      angular.element('input[name="folder_name"]').focus()

    $timeout timeoutCallback, 200