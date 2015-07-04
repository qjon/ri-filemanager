describe 'fileUploadController', ->

  beforeEach ->
    @scopeMock = {}
    @fileUploadServiceMock = {}

    @fileUploadController = new FileUpload @scopeMock, @fileUploadServiceMock

  describe 'constructor', ->
    it 'should set scope', ->
      expect(@fileUploadController.$scope).toEqual @scopeMock

    it 'should set fileUploadService', ->
      expect(@fileUploadController.fileUploadService).toEqual @fileUploadServiceMock