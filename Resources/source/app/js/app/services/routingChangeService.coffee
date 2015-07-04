###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class RoutingChange extends Service
  constructor: ($window, $location, dirStructureService) ->
    @$window = $window
    @$location = $location
    @dirStructureService = dirStructureService

  goToFolder: (event, folderId) ->
    @$location.url('/dir/' + folderId) if not event.ctrlKey
    return

  goToFolderUp: ->
    if @dirStructureService.currentDir.id
      @goToFolder {}, @dirStructureService.currentDir.parentId
    return

  downloadFile: (file, event) ->
    event.stopPropagation()
    @$window.open file.src
    return