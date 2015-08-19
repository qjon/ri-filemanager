describe 'RunFirst', ->
  initDirProviderMock = null
  routingChangeServiceMock = null
  $windowMock = null
  dirStructureServiceMock = null
  $translateMock = null
  configProviderMock = null

  beforeEach ->
    initDirProviderMock =
      getFilePath: jasmine.createSpy()

    $windowMock =
      btoa: jasmine.createSpy()

    dirStructureServiceMock =
      searchFile: jasmine.createSpy()

    $translateMock =
      use: jasmine.createSpy()

    configProviderMock =
      defaultLanguage: 'en_EN'

  describe 'constructor', ->
    it 'should call $translate.use', ->
      new RunFirst routingChangeServiceMock, dirStructureServiceMock, $windowMock, initDirProviderMock, $translateMock, configProviderMock

      expect($translateMock.use).toHaveBeenCalledWith configProviderMock.defaultLanguage

    it 'should not call dirStructureService.searchFile if file path is not string', ->
      initDirProviderMock.getFilePath.and.returnValue false

      new RunFirst routingChangeServiceMock, dirStructureServiceMock, $windowMock, initDirProviderMock, $translateMock, configProviderMock

      expect(dirStructureServiceMock.searchFile.calls.count()).toBe 0


    it 'should call dirStructureService.searchFile if file path is string', ->
      path = '/path/to/some/file'
      initDirProviderMock.getFilePath.and.returnValue path

      new RunFirst routingChangeServiceMock, dirStructureServiceMock, $windowMock, initDirProviderMock, $translateMock, configProviderMock

      expect($windowMock.btoa).toHaveBeenCalledWith path
      expect(dirStructureServiceMock.searchFile).toHaveBeenCalled()