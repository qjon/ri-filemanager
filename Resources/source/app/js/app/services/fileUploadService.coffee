###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Upload extends Service
  constructor: (modalService, dirStructureService, FileObj, fileIconsService) ->
    @$flow = null
    @modalDialog = null
    @dirStructureService = dirStructureService
    @fileObj = FileObj
    @fileIconService = fileIconsService
    @modalService = modalService
    @fileIconsService = fileIconsService

  getFlow: ->
    @$flow

  ###*
   * Change file upload percent
   *
   * @param $flow
   * @param $file
   ###
  uploadProgress: ($flow, $file) ->
    $file.percent = Math.round $file._prevUploadedSize / $file.size * 100
    return

  ###*
   * File upload complete action - remove file from $flow, add file to current dir
   *
   * @param $flow
   * @param $file
   * @param response
   ###
  fileUploadComplete: ($flow, $file, response) ->
    fileData = angular.fromJson response
    $file.percent = 100
    $flow.files.splice $flow.files.indexOf($file), 1

    fileObj = new @fileObj fileData, @dirStructureService.currentDir, @dirStructureService

    @dirStructureService.currentDir.files.push fileObj
    return

  ###*
   * Open dialog $flow
   *
   * @param flow
   ###
  openUploadFileDialog: (event, flow) ->
    @$flow = flow;
    @modalDialog = @modalService.open event, '/templates/files_upload.html'

    @

  ###*
   * Upload files
   ###
  uploadFiles: ->
    @$flow.upload()
    @

  ###*
   * Hide modal and clear $flow
   ###
  hideAndClear: ->
    @modalDialog.hide()
    @$flow.cancel()
    @$flow = undefined

  ###*
   * Check if uploaded file is image
   * @param flowFile
   * @returns {boolean}
   ###
  isImage: (flowFile) ->
    flowFile.file.type.indexOf('image') == 0

  getThumbnail: (flowFile) ->
    filenameSplitted = flowFile.file.name.split '.'

    @fileIconsService.getIconPath filenameSplitted.pop()
