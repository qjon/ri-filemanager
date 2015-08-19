###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class RunFirst extends Run
  constructor: (routingChangeService, dirStructureService, $window, initDirProvider, $translate, configProvider) ->

    $translate.use configProvider.defaultLanguage

#   check if is file initial path, if is then we try to find directory
    path = initDirProvider.getFilePath();
    if path
      successCallback = (file) ->
        routingChangeService.goToFolder {}, file.dirId

      failureCallback = ->
        routingChangeService.goToFolder {}, 0

      dirStructureService.searchFile($window.btoa(path), successCallback, failureCallback);
