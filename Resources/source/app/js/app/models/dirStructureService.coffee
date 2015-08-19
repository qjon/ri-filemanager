###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class DirStructure extends Service
  constructor: ($q, $http, DirObj, FileObj, SpinnerService, urlService) ->
    @currentDir = false
    @$q = $q
    @$http = $http
    @dirObj = DirObj
    @fileObj = FileObj
    @spinnerService = SpinnerService
    @url = urlService

  addFolder: (name, callbackSuccess, callbackError) ->
    @spinnerService.show();
    url = @url.generate 'ri_filemanager_api_directory_add'
    request = @$http.post url, {'dir_id': @currentDir.id, name: name}
    request.success (data) =>
      dir = new @dirObj data, @currentDir, @
      @currentDir.dirs.push dir
      @spinnerService.hide()
      callbackSuccess dir if callbackSuccess
      data

    request.error (data) =>
      @spinnerService.hide()
      callbackError data if callbackError
      data

  getFileById: (id) ->
    _.find @currentDir.files, {id: parseInt id}

  getSubDirById: (id) ->
    _.find @currentDir.dirs, {id: parseInt id}

  load: (dirId) ->
    defer = @$q.defer()

    if @currentDir != false and parseInt(dirId, 10) == @currentDir.id
      return @

    url = @url.generate 'ri_filemanager_api_index', {id: dirId}

    @spinnerService.show();
    @$http.get url
      .success (data) =>
        currentDir = new @dirObj data
        dirs = []
        files = []

        currentDir.parentId = data['parent_id']
        currentDir.dirs = []
        currentDir.files = []

        data.dirs.forEach (dirData) =>
          dirs.push(new @dirObj dirData, @currentDir, @)
          return

        data.files.forEach (fileData) =>
          files.push(new @fileObj fileData, @currentDir, @)
          return

        @currentDir = currentDir
        angular.copy dirs, currentDir.dirs
        angular.copy files, currentDir.files

        defer.resolve @
        @spinnerService.hide()
        return

    defer.promise

  reload: ->
    dirId = @currentDir.id
    @currentDir.id = null

    @load dirId


  searchFile: (path, callbackSuccess, callbackError) ->

    @spinnerService.show();
    url = @url.generate 'ri_filemanager_api_file_search', {path: path}
    @$http.get url
      .success (data) =>
        if not data.error and data.success
          callbackSuccess data.file if callbackSuccess
        else
          callbackError data if callbackError

        @spinnerService.hide()
        return
      .error (data) =>
        @spinnerService.hide()
        callbackError data if callbackError
        return