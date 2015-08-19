###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class FileObj extends Factory
  constructor: ($http, modalService, fileTypesService, fileIconsService, SpinnerService, urlService) ->
    structure = null
    return class FileObj
      constructor: (data, dir, dirStructure) ->
        structure = dirStructure
        @dir = dir
        @image = false
        @icon = false
        @cropData = {}

        @_resetCropData()
        @_setData data


      _resetCropData: ->
        @cropData.x = 0
        @cropData.y = 0
        @cropData.width = 0
        @cropData.height = 0

      _setData: (data) ->
        angular.extend @, data
        @image = fileTypesService.hasTypeGetMime 'images', @mime
        @icon = fileIconsService.getIconPath @src if not @image
        return


      crop: ->
        if @cropData.width > 0 and @cropData.height > 0
          url = urlService.generate 'ri_filemanager_api_file_edit', {id: @id}
          $http.put url, {
              id: @id
              x: @cropData.x
              y: @cropData.y
              width: @cropData.width
              height: @cropData.height
            }
          .success (response) =>
            if response.success
              @width = @cropData.width
              @height = @cropData.height
              # @todo: double change create not valid url
              @src = @src + '?' + new Date().getTime()
              @_resetCropData()

            return


      getDirStructure: ->
        structure


      isImage: ->
        return @image


      openRemoveDialog: (event) ->
        modalService.open event, '/templates/file_remove.html', {file: @}


      openEditDialog: (event) ->
        modalService.open event, '/templates/file_edit.html', {file: @}


      remove: (callbackSuccess, callbackError) ->
        fileId = @id;

        SpinnerService.show();
        url = urlService.generate 'ri_filemanager_api_file_delete', {id: fileId}
        $http.delete url
          .success (data) =>
            if not data.error
              _.remove @getDirStructure().currentDir.files, {id: parseInt(fileId)}

              callbackSuccess() if callbackSuccess
            else
              callbackError data if callbackError

            SpinnerService.hide()
            return
          .error (data) ->
            SpinnerService.hide()
            callbackError data if callbackError
            return



      setCropData: (x, y, width, height) ->
        @cropData.x = x
        @cropData.y = y
        @cropData.width = width
        @cropData.height = height
        @


      toJSON: ->
        name: @name
        src: @src
        width: @width
        height: @height
        mime: @mime
        image: @isImage