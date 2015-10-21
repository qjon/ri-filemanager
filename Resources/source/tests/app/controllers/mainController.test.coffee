describe 'mainController', ->

  beforeEach ->
    @scopeMock = {}
    @dirStructureMock = {}
    @fileTypeFilterMock = {}
    @fileTypesMock = {}
    @routingChangeServiceMock = {}
    @pluginMock = {}
    @selectionServiceMock =
      toggleFile: jasmine.createSpy()

    @copyPasteMock = {}
    @fileUploadServiceMock = {}
    @callbackServiceMock = {}
    @$translateMock =
      use: jasmine.createSpy()

    @configProviderMock = {
      allowChangeLanguage: true
    }

    @previewServiceMock =
      open: jasmine.createSpy()

    @mainController = new Main @scopeMock, @dirStructureMock, @fileTypesMock, @fileTypeFilterMock, @routingChangeServiceMock, @selectionServiceMock, @copyPasteServiceMock, @fileUploadServiceMock, @callbackServiceMock, @$translateMock, @configProviderMock, @previewServiceMock

  describe 'constructor', ->
    it 'should set $scope', ->
      expect(@mainController.$scope).toEqual @scopeMock

    it 'should set dirStructure', ->
      expect(@mainController.dirStructure).toEqual @dirStructureMock

    it 'should set fileTypeFilter', ->
      expect(@mainController.fileTypeFilter).toEqual @fileTypeFilterMock

    it 'should set fileTypes', ->
      expect(@mainController.fileTypes).toEqual @fileTypesMock

    it 'should set routingChangeService', ->
      expect(@mainController.routingChangeService).toEqual @routingChangeServiceMock

    it 'should set selectionService', ->
      expect(@mainController.selection).toEqual @selectionServiceMock

    it 'should set fileUploadService', ->
      expect(@mainController.fileUploadService).toEqual @fileUploadServiceMock

    it 'should set callbackService', ->
      expect(@mainController.callbackService).toEqual @callbackServiceMock

    it 'should set allowChangeLanguage', ->
      expect(@mainController.allowChangeLanguage).toEqual @configProviderMock.allowChangeLanguage

  describe 'setLanguage', ->
    it 'should call $translate.use', ->
      lang = 'pl_PL'

      @mainController.setLanguage lang

      expect(@$translateMock.use).toHaveBeenCalledWith lang

  describe 'getLanguageSymbol', ->
    it 'should return PL', ->

      @$translateMock.use.and.returnValue 'pl_PL'

      expect(@mainController.getLanguageSymbol()).toEqual 'PL'
      expect(@$translateMock.use).toHaveBeenCalled()

    it 'should return EN', ->

      @$translateMock.use.and.returnValue 'en_EN'

      expect(@mainController.getLanguageSymbol()).toEqual 'EN'
      expect(@$translateMock.use).toHaveBeenCalled()

    it 'should return EN if not set EN and not set PL', ->

      @$translateMock.use.and.returnValue 'de_DE'

      expect(@mainController.getLanguageSymbol()).toEqual 'EN'
      expect(@$translateMock.use).toHaveBeenCalled()


  describe 'onClick', ->
    it 'should call selection.toggleFile', ->
      $event =
        type: 'click'
      file =
        id: 7

      @mainController.onClick file, $event

      expect(@selectionServiceMock.toggleFile).toHaveBeenCalledWith file, $event

  describe 'openPreview', ->
    it 'should call previewService.open', ->
      $event =
        type: 'click'
        stopPropagation: jasmine.createSpy()
      file =
        id: 7

      @mainController.openPreview $event, file

      expect($event.stopPropagation).toHaveBeenCalled()
      expect(@previewServiceMock.open).toHaveBeenCalledWith file

