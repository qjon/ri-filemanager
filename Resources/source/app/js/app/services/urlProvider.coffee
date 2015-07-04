###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Url extends Provider
  contructor: ->
    @loadFolder = '/admin/filemanager/api/directory'
    @addFolder = '/admin/filemanager/api/directory/add'
    @cropImage = '/admin/filemanager/api/file/crop'
    @deleteFile = '/admin/filemanager/api/file/remove'
    @deleteFolder = '/admin/filemanager/api/directory/remove'
    @updateFolder = '/admin/filemanager/api/directory/save'

  setUrl: (url) ->
    @loadFolder = url.loadFolder
    @addFolder = url.addFolder
    @cropImage = url.cropImage
    @deleteFile = url.deleteFile
    @deleteFolder = url.deleteFolder
    @updateFolder = url.updateFolder
    return

  $get: ->
    loadFolder: @loadFolder,
    addFolder: @addFolder,
    cropImage: @cropImage,
    deleteFile: @deleteFile,
    deleteFolder: @deleteFolder,
    updateFolder: @updateFolder
