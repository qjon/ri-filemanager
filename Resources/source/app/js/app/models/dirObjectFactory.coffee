###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class DirObj extends Factory
  constructor: ($http, modalService, SpinnerService, urlService) ->
    parent = null
    structure = null

    return class DirObj
      constructor: (data, parentDir, dirStructure) ->
        parent = parentDir
        structure = dirStructure

        @dirs = []
        @files = []

        @_setData data

      _setData: (data) ->
        angular.extend @, data

      getSubDir: (id) ->
        _.find @dirs, {id: id}

      getParentDir: ->
        parent

      getDirStructure: ->
        structure

      ###
      Open dialog to edit folder name

      @param {Event} event
      ###
      openDialogEditFolder: (event) ->
        modalService.open event, '/templates/dir_edit.html', {dir: @}

      ###
      Open dialog to remove folder

      @param {Event} event
      ###
      openDialogRemoveFolder: (event) ->
        modalService.open event, '/templates/dir_remove.html', {dir: @}

      ###
      Remove folder

      @param {Function} callbackSuccess
      @param {Function} callbackError
      ###
      remove: (callbackSuccess, callbackError) ->
        SpinnerService.show();
        url = urlService.generate 'ri_filemanager_api_directory_remove', {id: @id}
        $http.delete url
          .success (data) =>
            if !data.error
              _.remove @getDirStructure().currentDir.dirs, {id: parseInt @id}
              callbackSuccess() if callbackSuccess
            else
              callbackError data.error if callbackError
            SpinnerService.hide()
          .error (data) ->
            callbackError data.error if callbackError
            SpinnerService.hide()

      ###
      Save folder

      @param {String} name to save
      @param {Function} callbackSuccess
      @param {Function} callbackError
      ###
      save: (name, callbackSuccess, callbackError) ->
        SpinnerService.show();
        url = urlService.generate 'ri_filemanager_api_directory_edit', {id: @id}
        $http.put url, {name: name}
          .success (data) =>
            @name = name
            SpinnerService.hide()
            callbackSuccess(@) if callbackSuccess
          .error (data) ->
            callbackError data.error if callbackError
            SpinnerService.hide()