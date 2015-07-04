###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Callback extends Service
  constructor: (configProvider) ->
    @configProvider = configProvider;

  isFileCallback: ->
    angular.isFunction @configProvider.filesSelectCallback

  fileCallback: (event, file) ->
    filesData = [];
    event.stopPropagation();
    if @isFileCallback()
      if angular.isArray file
        file.forEach (f) ->
          filesData.push f.toJSON()
          return
      else
        filesData.push file.toJSON()

      @configProvider.filesSelectCallback filesData

    return

  isFolderCallback: ->
    angular.isFunction @configProvider.dirSelectCallback

  folderCallback: (event, dir) ->
    event.stopPropagation()
    if @.isFolderCallback()
      @configProvider.dirSelectCallback dir

    return