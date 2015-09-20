describe 'uploadService', ->
  fileUploadService = null
  modalServiceMock = null
  modalDialogMock = null
  dirStructureMock = null
  fileObjMock = null
  fileObjMockConstructor = null
  fileIconsMock = null
  flowMock = null
  eventMock = null
  growlMock = null
  configProviderMock = null

  fileOne =
    id: 1
    name: 'File 1'
    type: 'image/jpeg'
  fileTwo =
    id: 2
    name: 'File 2'
    type: 'image/png'
  files = [fileOne, fileTwo]

  beforeEach ->
    modalDialogMock =
      hide: jasmine.createSpy()
    eventMock = {}
    fileObjMockConstructor = jasmine.createSpy()
    flowMock =
      upload: jasmine.createSpy()
      cancel: jasmine.createSpy(),
      files: [fileOne, fileTwo]
    dirStructureMock =
      currentDir:
        files: files
    fileObjMock = jasmine.createSpy().and.returnValue(fileObjMockConstructor);
    modalServiceMock =
      open: jasmine.createSpy().and.returnValue(modalDialogMock)
      hide: jasmine.createSpy()

    fileIconsMock =
      getIconPath: jasmine.createSpy()

    growlMock =
      error: jasmine.createSpy()

    configProviderMock =
      availableMimeTypes: ['image/jpeg', 'image/png']

    fileUploadService = new Upload modalServiceMock, dirStructureMock, fileObjMock, fileIconsMock, configProviderMock, growlMock

    fileUploadService.openUploadFileDialog eventMock, flowMock

  afterEach ->
    expect(modalServiceMock.open).toHaveBeenCalledWith eventMock, '/templates/files_upload.html'

  describe 'beforeAddFile', ->
    it 'should return true', ->
      fileMock =
        file:
          type: 'image/jpeg'

      expect(fileUploadService.beforeAddFile fileMock).toBeTruthy()

    it 'should return false', ->
      fileMock =
        file:
          name: 'Some file.pdf'
          type: 'application/pdf'

      expect(fileUploadService.beforeAddFile fileMock).toBeFalsy()
      expect(growlMock.error).toHaveBeenCalledWith fileMock.file.name + ' (' + fileMock.file.type + ')', {title: 'UNAVAILABLE_MIME_TYPE'}

  describe 'uploadProgress', ->
    it 'should set file new percent value', ->
      file =
        _prevUploadedSize: 1200000
        size: 12000000

      fileUploadService.uploadProgress flowMock, file

      expect(file.percent).toEqual 10

  describe 'fileUploadComplete', ->
    file = null
    response = null

    beforeEach ->
      response = '{"id": 5, "name": "File 5"}'

      file =
        _prevUploadedSize: 1200000
        size: 12000000
        percent: 10

      flowMock.files = [file]

    it 'should set file.percent equal 1000 and push new file to currentDir files array', ->
      fileUploadService.fileUploadComplete flowMock, file, response

      expect(file.percent).toBe 100

    it 'should remove file from $flow.files ', ->
      fileUploadService.fileUploadComplete flowMock, file, response

      expect(flowMock.files).toEqual []

    it 'should call push new file to currentDir files array', ->
      spyOn dirStructureMock.currentDir.files, 'push'

      fileUploadService.fileUploadComplete flowMock, file, response

      expect(dirStructureMock.currentDir.files.push).toHaveBeenCalled()

  describe 'openUploadFileDialog', ->
    it 'should set $flow', ->
      expect(fileUploadService.getFlow()).toEqual flowMock

    it 'should not create modalDialog', ->
      flowMock2 =
        files: []
      modalServiceMock2 =
        open: jasmine.createSpy()
      fileUploadService2 = new Upload modalServiceMock2, {}, {}, {}, {}, {}
      fileUploadService2.openUploadFileDialog eventMock, flowMock2

      expect(fileUploadService2.modalDialog).toBeNull()

  describe 'uploadFiles', ->
    it 'should call $flow.upload', ->
      fileUploadService.uploadFiles()

      expect(flowMock.upload).toHaveBeenCalled()

  describe 'hideAndClear', ->
    it 'should call ModalDialog.hide', ->
      fileUploadService.hideAndClear()

      expect(modalDialogMock.hide).toHaveBeenCalled()

    it 'should call $flow.cancel', ->
      fileUploadService.hideAndClear()

      expect(flowMock.cancel).toHaveBeenCalled()

    it 'should not call ModalDialog.hide if files.length equal 0', ->

      fileUploadService.modalDialog = false
      fileUploadService.hideAndClear()
      expect(modalDialogMock.hide).not.toHaveBeenCalled()

  describe 'isImage', ->
    it 'should return true', ->
      flowFile =
        file:
          type: 'image/jpg'

      expect(fileUploadService.isImage flowFile).toBeTruthy()

    it 'should return false', ->
      flowFile =
        file:
          type: 'application/pdf'

      expect(fileUploadService.isImage(flowFile)).toBeFalsy()

  describe 'getThumbnail', ->
    it 'should call FileIcons.getIconPath', ->
      flowFile =
        file:
          name: 'house.jpg'
          type: 'image/jpeg'

      fileUploadService.getThumbnail flowFile
      expect(fileIconsMock.getIconPath).toHaveBeenCalledWith 'jpg'