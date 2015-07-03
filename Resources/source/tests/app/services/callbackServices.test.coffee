describe 'callbackService', ->
  callbackService = null
  filemanagerConfigMock = {}
  fileMock = {}
  folderMock = {}
  eventMock =
    stopPropagation: jasmine.createSpy()

  beforeEach ->
    module 'filemanager'
    callbackService = new Callback filemanagerConfigMock
    fileMock =
      toJSON: jasmine.createSpy()

    return

  describe 'isFileCallback', ->
    it 'should return false if FilemanagerConfig.filesSelectionCallback is not set', ->
      expect(callbackService.isFileCallback()).toBeFalsy();

    it 'should return false if FilemanagerConfig.filesSelectionCallback is object', ->
      filemanagerConfigMock =
        filesSelectCallback: {}
      expect(callbackService.isFileCallback()).toBeFalsy();

    it 'should return true if FilemanagerConfig.filesSelectionCallback is function', ->
      filemanagerConfigMock =
        filesSelectCallback: ->
      expect(callbackService.isFileCallback()).toBeFalsy();

  describe 'fileCallback', ->
    it 'should call FilemanagerConfig.filesSelectCallback and is set files list', ->
      spyOn(callbackService, 'isFileCallback').and.returnValue true
      filemanagerConfigMock.filesSelectCallback = jasmine.createSpy()

      fileMock.toJSON.and.returnValue fileMock

      callbackService.fileCallback eventMock, [fileMock]

      expect(filemanagerConfigMock.filesSelectCallback).toHaveBeenCalledWith [fileMock]
      expect(eventMock.stopPropagation).toHaveBeenCalled();

    it 'should call FilemanagerConfig.filesSelectCallback and is set one file', ->
      spyOn(callbackService, 'isFileCallback').and.returnValue true
      filemanagerConfigMock.filesSelectCallback = jasmine.createSpy()

      fileMock.toJSON.and.returnValue fileMock

      callbackService.fileCallback eventMock, fileMock

      expect(filemanagerConfigMock.filesSelectCallback).toHaveBeenCalledWith [fileMock]
      expect(eventMock.stopPropagation).toHaveBeenCalled()

    it 'should not call FilemanagerConfig.filesSelectCallback if it is not set', ->
      spyOn(callbackService, 'isFileCallback').and.returnValue false
      filemanagerConfigMock.filesSelectCallback = jasmine.createSpy()

      fileMock.toJSON.and.returnValue fileMock

      callbackService.fileCallback eventMock, fileMock

      expect(filemanagerConfigMock.filesSelectCallback).not.toHaveBeenCalled()
      expect(eventMock.stopPropagation).toHaveBeenCalled()

  describe 'isFolderCallback', ->
    it 'should return false if FilemanagerConfig.dirSelectCallback is not set', ->
      expect(callbackService.isFolderCallback()).toBeFalsy()

    it 'should return false if FilemanagerConfig.dirSelectCallback is object', ->
      filemanagerConfigMock =
        dirSelectCallback: {}

      expect(callbackService.isFolderCallback()).toBeFalsy()

    it 'should return true if FilemanagerConfig.dirSelectCallback is function', ->
      filemanagerConfigMock =
          dirSelectCallback: ->

      expect(callbackService.isFolderCallback()).toBeFalsy()

  describe 'folderCallback', ->
    it 'should call FilemanagerConfig.dirSelectCallback', ->
      spyOn(callbackService, 'isFolderCallback').and.returnValue true
      filemanagerConfigMock.dirSelectCallback = jasmine.createSpy()

      callbackService.folderCallback eventMock, folderMock

      expect(filemanagerConfigMock.dirSelectCallback).toHaveBeenCalledWith folderMock
      expect(eventMock.stopPropagation).toHaveBeenCalled()

    it 'should not call FilemanagerConfig.dirSelectCallback if not set', ->
      spyOn(callbackService, 'isFolderCallback').and.returnValue false
      filemanagerConfigMock.dirSelectCallback = jasmine.createSpy()

      callbackService.folderCallback eventMock, folderMock

      expect(filemanagerConfigMock.dirSelectCallback).not.toHaveBeenCalled()
      expect(eventMock.stopPropagation).toHaveBeenCalled()