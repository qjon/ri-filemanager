###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Selection extends Service
  folders = []
  files = []

  addFile: (file) ->
    files.push file
    @

  getFileById: (id) ->
    _.find files, {id: id}

  getFiles: ->
    files

  getFilesIds: ->
    ids = []

    files.forEach (file) ->
      ids.push file.id

    ids

  isSelectedFile: (id) ->
    file = @getFileById id
    return if file then true else false

  removeFile: (id) ->
    file = @getFileById id
    index = -1

    if file
      index = files.indexOf file
      files.splice index, 1

    @

  toggleFile: (event, file) ->
    event.preventDefault()

    if event.ctrlKey
      if @isSelectedFile file.id
        @removeFile file.id
      else
        @addFile file


  addFolder: (folder) ->
    folders.push folder
    @

  getFolders: ->
    folders

  getFoldersIds: ->
    ids = []
    folders.forEach (folder) ->
      ids.push folder.id

    ids

  getFolderById: (id) ->
    _.find folders, {id: id}

  isSelectedFolder: (id) ->
    folder = @getFolderById id
    if folder then true else false

  deleteFolder: (id) ->
    folder = @getFolderById id
    index = -1

    if folder
      index = folders.indexOf folder
      folders.splice index, 1

    @

  toggleFolder: (event, folder) ->
    event.stopPropagation()

    if event.ctrlKey
      if @isSelectedFolder folder.id
        @deleteFolder folder.id
      else
        @addFolder folder

    @

  isEmptySelection: ->
    folders.length == 0 and files.length == 0

  clear: ->
    angular.copy [], files
    angular.copy [], folders
    @




