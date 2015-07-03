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
  fileOne =
    id: 1
    name: 'File 1'
  fileTwo =
    id: 2
    name: 'File 2'
  files = [fileOne, fileTwo]

  beforeEach ->
    modalDialogMock =
      hide: jasmine.createSpy()
    eventMock = {}
    fileObjMockConstructor = jasmine.createSpy()
    flowMock =
      upload: jasmine.createSpy()
      cancel: jasmine.createSpy()
    dirStructureMock =
      currentDir:
        files: files
    fileObjMock = jasmine.createSpy().and.returnValue(fileObjMockConstructor);
    modalServiceMock =
      open: jasmine.createSpy().and.returnValue(modalDialogMock)
      hide: jasmine.createSpy()

    fileIconsMock =
      getIconPath: jasmine.createSpy()

    fileUploadService = new Upload modalServiceMock, dirStructureMock, fileObjMock, fileIconsMock

    fileUploadService.openUploadFileDialog eventMock, flowMock

  afterEach ->
    expect(modalServiceMock.open).toHaveBeenCalledWith eventMock, '/templates/files_upload.html'

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

  describe 'uploadFiles', ->
    it 'should call $flow.upload', ->
      fileUploadService.uploadFiles()

      expect(flowMock.upload).toHaveBeenCalled()

  describe 'hideAndClear', ->
    beforeEach ->
      fileUploadService.hideAndClear()

    it 'should call ModalDialog.hide', ->
      expect(modalDialogMock.hide).toHaveBeenCalled()

    it 'should call $flow.cancel', ->
      expect(flowMock.cancel).toHaveBeenCalled()

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

      fileUploadService.getThumbnail flowFile
      expect(fileIconsMock.getIconPath).toHaveBeenCalledWith 'jpg'