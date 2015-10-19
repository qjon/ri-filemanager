describe 'crop directive', ->
  $scope = null
  $el = null
  previewServiceMock = null
  dirStructureMock = null
  $document = null
  fileMock = null

  beforeEach ->
    module 'filemanager'

    dirStructureMock =
      isLastFile: jasmine.createSpy()
      isFirstFile: jasmine.createSpy()

    fileMock =
        getDirStructure: jasmine.createSpy().and.returnValue dirStructureMock

    previewServiceMock =
      isOpen: jasmine.createSpy()
      nextFile: jasmine.createSpy()
      prevFile: jasmine.createSpy()
      file: fileMock

    module ($provide) ->
      $provide.value 'previewService', previewServiceMock

      return

    inject ($rootScope, $compile, $httpBackend, _$document_) ->
      $document = _$document_
      $httpBackend.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond 200, {}
      $scope = $rootScope.$new()
      $el = $compile('<preview></preview>')($scope)
      $scope.$digest()

      return


  describe 'link', ->
    it 'press RIGHT ARROW should call go to next file if next file exist', ->
      previewServiceMock.isOpen.and.returnValue true
      dirStructureMock.isLastFile.and.returnValue false
      $document.triggerHandler
        type: 'keydown'
        keyCode: 39

      $scope.$digest()

      expect(previewServiceMock.file.getDirStructure).toHaveBeenCalled()
      expect(dirStructureMock.isLastFile).toHaveBeenCalledWith fileMock
      expect(previewServiceMock.nextFile).toHaveBeenCalled()


    it 'press LEFT ARROW should call go to prev file if prev file exists', ->
      previewServiceMock.isOpen.and.returnValue true
      dirStructureMock.isFirstFile.and.returnValue false
      $document.triggerHandler
        type: 'keydown'
        keyCode: 37

      $scope.$digest()

      expect(previewServiceMock.file.getDirStructure).toHaveBeenCalled()
      expect(dirStructureMock.isFirstFile).toHaveBeenCalledWith fileMock
      expect(previewServiceMock.prevFile).toHaveBeenCalled()

    it 'should not change file if preview is not open', ->
      previewServiceMock.isOpen.and.returnValue false
      $document.triggerHandler
        type: 'keydown'
        keyCode: 37

      $scope.$digest()

      expect(previewServiceMock.prevFile).not.toHaveBeenCalled()


      $document.triggerHandler
        type: 'keydown'
        keyCode: 37

      $scope.$digest()

      expect(previewServiceMock.nextFile).not.toHaveBeenCalled()

    it 'press RIGHT ARROW should not call go to next file if the file is last file', ->
      previewServiceMock.isOpen.and.returnValue true
      dirStructureMock.isLastFile.and.returnValue true
      $document.triggerHandler
        type: 'keydown'
        keyCode: 39

      $scope.$digest()

      expect(previewServiceMock.nextFile).not.toHaveBeenCalled()


    it 'press LEFT ARROW should not call go to prev file if the file is first file', ->
      previewServiceMock.isOpen.and.returnValue true
      dirStructureMock.isFirstFile.and.returnValue true
      $document.triggerHandler
        type: 'keydown'
        keyCode: 37

      $scope.$digest()

      expect(previewServiceMock.prevFile).not.toHaveBeenCalled()

