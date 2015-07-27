describe 'CopyPasteService', ->
  copyPasteService = null
  filesIds = null
  files = null
  folderIds = null
  folders = null
  $httpMock = null
  modalServiceMock = null
  selectionServiceMock = null
  dirStructureMock = null
  spinnerServiceMock = null
  urlService = null

  beforeEach ->
    filesIds = [1, 7, 12]
    folderIds = [2, 17, 21]
    files = [{id: 1}, {id: 7}, {id: 12}]
    folders = [{id: 2}, {id: 17}, {id: 21}]
    $httpMock =
      put: jasmine.createSpy()
    modalServiceMock =
      open: jasmine.createSpy()
    selectionServiceMock =
      getFiles: jasmine.createSpy().and.returnValue files
      getFolders: jasmine.createSpy().and.returnValue folders
      getFilesIds: jasmine.createSpy().and.returnValue filesIds
      getFoldersIds: jasmine.createSpy().and.returnValue folderIds
      clear: jasmine.createSpy()
    dirStructureMock =
      reload: jasmine.createSpy()
    spinnerServiceMock =
      show: jasmine.createSpy()
      hide: jasmine.createSpy()

    urlServiceMock =
      generate: jasmine.createSpy()

    module('filemanager');

    module ($provide) ->
#      $provide.value '$http', $httpMock
      $provide.value 'selectionService', selectionServiceMock
      $provide.value 'dirStructureService', dirStructureMock
      $provide.value 'SpinnerService', spinnerServiceMock
      $provide.value 'modalService', modalServiceMock
#      $provide.value 'urlService', urlServiceMock
      return
#
#      copyPasteService = new CopyPaste $httpMock, modalServiceMock, selectionServiceMock, dirStructureMock, spinnerServiceMock
    inject (_$httpBackend_, _copyPasteService_) ->
      copyPasteService = _copyPasteService_
      $httpMock = _$httpBackend_
      return


  describe 'doAction', ->
    dirId = 12

    it 'it should call this.move', ->
      copyPasteService.setActionType 1
      spyOn copyPasteService, 'move'
      copyPasteService.doAction dirId

      expect(copyPasteService.move).toHaveBeenCalledWith dirId

    it 'it should call this.copy', ->
      copyPasteService.setActionType 2
      spyOn copyPasteService, 'copy'
      copyPasteService.doAction dirId

      expect(copyPasteService.copy).toHaveBeenCalledWith dirId

    it 'it should not call any function', ->
      copyPasteService.setActionType 0
      spyOn copyPasteService, 'copy'
      spyOn copyPasteService, 'move'
      copyPasteService.doAction dirId

      expect(copyPasteService.copy).not.toHaveBeenCalled()
      expect(copyPasteService.move).not.toHaveBeenCalled()

  describe 'setActionType', ->
    it 'it should set actionType 0 if current type is equal that is set', ->
      copyPasteService.setActionType 1

      expect(copyPasteService.isCutSelected()).toBeTruthy()

      copyPasteService.setActionType 1

      expect(copyPasteService.isNotSelected()).toBeTruthy()

    it 'should set proper action type', ->
      copyPasteService.setActionType 1
      expect(copyPasteService.isCutSelected()).toBeTruthy()

      copyPasteService.setActionType 2
      expect(copyPasteService.isCopySelected()).toBeTruthy()

    it 'should set actionType 0 if setting type is unknown', ->
      copyPasteService.setActionType 1

      expect(copyPasteService.isCutSelected()).toBeTruthy()

      copyPasteService.setActionType 7

      expect(copyPasteService.isNotSelected()).toBeTruthy()

  describe 'isCutSelected', ->
    it 'should return true if action type equal 1', ->
      copyPasteService.setActionType 1

      expect(copyPasteService.isCutSelected()).toBeTruthy()

    it 'should return false if action type not equal 1', ->
      copyPasteService.setActionType 2

      expect(copyPasteService.isCutSelected()).toBeFalsy()

  describe 'isCopySelected', ->
    it 'should return true if action type equal 2', ->
      copyPasteService.setActionType 2

      expect(copyPasteService.isCopySelected()).toBeTruthy()

    it 'should return false if action type not equal 2', ->
      copyPasteService.setActionType 1

      expect(copyPasteService.isCopySelected()).toBeFalsy()

  describe 'isNotSelected', ->
    it 'should return true if action type equal 0', ->
      copyPasteService.setActionType 0

      expect(copyPasteService.isNotSelected()).toBeTruthy()

    it 'should return false if action type not equal 0', ->
      copyPasteService.setActionType 1

      expect(copyPasteService.isNotSelected()).toBeFalsy()

  describe 'setCut', ->
    it 'should call this.setActionType(1)', ->
      spyOn copyPasteService, 'setActionType'
      copyPasteService.setCut()

      expect(copyPasteService.setActionType).toHaveBeenCalledWith 1

  describe 'setCopy', ->
    it 'should call this.setActionType(2)', ->
      spyOn copyPasteService, 'setActionType'
      copyPasteService.setCopy()

      expect(copyPasteService.setActionType).toHaveBeenCalledWith 2

  describe 'copy', ->
    dirId = 2

    it 'should call self.responseCallbackFunction', ->
      responseMock =
        data:
          success: true

      spyOn copyPasteService, 'responseCallbackFunction'

      $httpMock.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond {}
      $httpMock.whenPUT('ri_filemanager_api_copy_selection').respond 200, responseMock

      copyPasteService.copy dirId

      $httpMock.flush()

      expect(spinnerServiceMock.show).toHaveBeenCalled()
      expect(copyPasteService.responseCallbackFunction).toHaveBeenCalledWith responseMock


  describe 'openRemoveDialog', ->
    it 'should call modalService.open', ->
      eventMock = {}

      copyPasteService.openRemoveDialog eventMock

      expect(modalServiceMock.open).toHaveBeenCalledWith eventMock, '/templates/selection_remove.html', {dirs: folders, files: files}

  describe 'remove', ->
    callback = jasmine.createSpy();

    it 'should call self.responseCallbackFunction and callback', ->
      responseMock =
        data:
          success: true

      spyOn copyPasteService, 'responseCallbackFunction'

      $httpMock.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond {}
      $httpMock.whenPUT('ri_filemanager_api_delete_selection').respond 200, responseMock

      copyPasteService.remove callback

      $httpMock.flush()

      expect(spinnerServiceMock.show).toHaveBeenCalled()
      expect(copyPasteService.responseCallbackFunction).toHaveBeenCalledWith responseMock
      copyPasteService.remove callback

  describe 'move', ->
    dirId = 2

    it 'should call self.responseCallbackFunction', ->
      responseMock =
        data:
          success: true

      $httpMock.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond {}
      $httpMock.whenPUT('ri_filemanager_api_move_selection').respond 200, responseMock

      spyOn copyPasteService, 'responseCallbackFunction'

      copyPasteService.move dirId

      $httpMock.flush()

      expect(spinnerServiceMock.show).toHaveBeenCalled()
      expect(copyPasteService.responseCallbackFunction).toHaveBeenCalledWith responseMock


  describe 'responseCallbackFunction', ->

    beforeEach ->
      spyOn copyPasteService, 'setActionType'

    afterEach ->
      expect(spinnerServiceMock.hide).toHaveBeenCalled()
      expect(selectionServiceMock.clear).toHaveBeenCalled()
      expect(copyPasteService.setActionType).toHaveBeenCalledWith 0

    it 'should call dirstructure.reload if response is success', ->
      responseMock =
        data:
          success: true

      copyPasteService.responseCallbackFunction responseMock.data

      expect(dirStructureMock.reload).toHaveBeenCalled()

    it 'should not call dirstructure.reload if response is not success', ->
      responseMock =
        data:
          success: false

      copyPasteService.responseCallbackFunction responseMock.data

      expect(dirStructureMock.reload).not.toHaveBeenCalled()