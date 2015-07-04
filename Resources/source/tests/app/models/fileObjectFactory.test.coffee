describe 'fileObjectFactory', ->

  modalServiceMock = null
  fileTypesMock = null
  fileIconsMock = null
  spinnerServiceMock = null
  urlProviderMock = null
  dirStructure = null
  dirObj = null
  fileData = null
  $httpBackend = null


  beforeEach ->
    dirObj =
      id: 4
      name: 'Dir'
      dirs: []
      files: []

    dirStructure =
      currentDir:
        id: 100
        files: []

    fileTypesMock =
      hasTypeGetMime: jasmine.createSpy()

    fileIconsMock =
      getIconPath: jasmine.createSpy()

    modalServiceMock =
      open: jasmine.createSpy()

    spinnerServiceMock =
      show: jasmine.createSpy()
      hide: jasmine.createSpy()

    fileData =
      id: 7
      name: 'Some file name'
      src: '/some/files/path'
      mime: 'image/jpeg'
      width: 200
      height: 100

    urlProviderMock =
      deleteFile: '/delete/file'
      cropImage: 'crop/image'

    module 'filemanager'

    module ($provide) ->
      $provide.value 'modalService', modalServiceMock
      $provide.value 'fileTypesService', fileTypesMock
      $provide.value 'fileIconsService', fileIconsMock
      $provide.value 'SpinnerService', spinnerServiceMock
      $provide.value 'urlProvider', urlProviderMock
      return

    inject (_FileObj_, _$httpBackend_) ->
      @fileObj = new _FileObj_ fileData, dirObj, dirStructure
      $httpBackend = _$httpBackend_

    $httpBackend.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond 200, {}

  describe 'constructor', ->
    it 'should setCrop data', ->
      cropData = @fileObj.cropData
      expect(cropData.x).toBe 0
      expect(cropData.y).toBe 0
      expect(cropData.width).toBe 0
      expect(cropData.height).toBe 0

    it 'should set initial data', ->
      expect(@fileObj.id).toEqual fileData.id
      expect(@fileObj.name).toEqual fileData.name
      expect(@fileObj.src).toEqual fileData.src
      expect(@fileObj.mime).toEqual fileData.mime

  describe '_resetCropData', ->
    it 'should reset cropData object, all properties should have value 0', ->
      @fileObj.cropData.x = 50
      @fileObj.cropData.y = 50
      @fileObj.cropData.width = 125
      @fileObj.cropData.height = 135

      @fileObj._resetCropData()

      expect(@fileObj.cropData.x).toBe 0
      expect(@fileObj.cropData.y).toBe 0
      expect(@fileObj.cropData.width).toBe 0
      expect(@fileObj.cropData.height).toBe 0


  describe '_setData', ->
    it 'should call abgular.copy', ->
      spyOn angular, 'extend'

      @fileObj._setData fileData

      expect(angular.extend).toHaveBeenCalledWith @fileObj, fileData

    it 'should check if mime is in images mime types list', ->
      @fileObj._setData fileData

      expect(fileTypesMock.hasTypeGetMime).toHaveBeenCalledWith 'images', fileData.mime

    it 'should set icon if file is not image', ->
      fileTypesMock.hasTypeGetMime.and.returnValue false
      @fileObj._setData fileData

      expect(fileIconsMock.getIconPath).toHaveBeenCalledWith fileData.src
      expect(fileIconsMock.getIconPath.calls.count()).toBe 2

    it 'should not set icon if file is image', ->
      fileTypesMock.hasTypeGetMime.and.returnValue true

      @fileObj._setData fileData

      # first time called during initialization
      expect(fileIconsMock.getIconPath.calls.count()).toBe 1


  describe 'crop', ->
    width = 50
    height = 30

    describe 'after success response', ->


      beforeEach ->
        @fileObj.setCropData 10, 20, width, height
        $httpBackend.whenPOST(urlProviderMock.cropFile, {id: 7, x: 10, y: 20, width: width, height: height})
          .respond(
            success: true
          )
        @fileObj.crop()
        $httpBackend.flush()


      afterEach ->
        $httpBackend.verifyNoOutstandingExpectation()
        $httpBackend.verifyNoOutstandingRequest()

      it 'should set new width', ->
        expect(@fileObj.width).toBe width

      it 'should set new height', ->
        expect(@fileObj.height).toBe height

      it 'should call reset cropData', ->
        expect(@fileObj.cropData.x).toBe 0
        expect(@fileObj.cropData.y).toBe 0
        expect(@fileObj.cropData.width).toBe 0
        expect(@fileObj.cropData.height).toBe 0


    describe 'after error response', ->
      it 'if cropData.width <= 0 nothing should have changed', ->
        @fileObj.setCropData 10, 20, 0, 160
        @fileObj.crop()

        expect(@fileObj.width).toBe fileData.width
        expect(@fileObj.height).toBe fileData.height

      it 'if cropData.height <= 0 nothing should have changed', ->
        @fileObj.setCropData 20, 20, 60, 0
        @fileObj.crop()

        expect(@fileObj.width).toBe fileData.width
        expect(@fileObj.height).toBe fileData.height

      it 'nothing should have changed if response.success is false', ->
        @fileObj.setCropData 10, 20, width, height
        $httpBackend.whenPOST(urlProviderMock.cropFile, {id: 7, x: 10, y: 20, width: width, height: height})
          .respond(
            success: false
          )
        @fileObj.crop()
        $httpBackend.flush()

        expect(@fileObj.width).toBe fileData.width
        expect(@fileObj.height).toBe fileData.height

        $httpBackend.verifyNoOutstandingExpectation()
        $httpBackend.verifyNoOutstandingRequest()


  describe 'getDirStructure', ->
    it 'should return dir structure object', ->
      expect(@fileObj.getDirStructure()).toEqual dirStructure


  describe 'isImage', ->
    it 'should return fileObj.image value', ->
      expect(@fileObj.isImage()).toEqual @fileObj.image


  describe 'openRemoveDialog', ->
    it 'should call modal service open function with proper values', ->
      $event = {}
      @fileObj.openRemoveDialog $event

      expect(modalServiceMock.open).toHaveBeenCalledWith $event, '/templates/file_remove.html', {file: @fileObj}


  describe 'openEditDialog', ->
    it 'should call modal service open function with proper values', ->
      $event = {}
      @fileObj.openEditDialog $event

      expect(modalServiceMock.open).toHaveBeenCalledWith $event, '/templates/file_edit.html', {file: @fileObj}


  describe 'remove', ->
    callbackSuccessFunction = jasmine.createSpy()
    callbackErrorFunction = jasmine.createSpy()

    it 'should remove file and not run callback', ->
      spyOn(_, 'remove')
      callbackSuccessFunction = jasmine.createSpy()

      $httpBackend.whenPOST(urlProviderMock.deleteFile, {file_id: 7})
      .respond(
        success: true
      )
      @fileObj.remove()
      $httpBackend.flush()

      expect(callbackSuccessFunction).not.toHaveBeenCalled()
      expect(callbackErrorFunction).not.toHaveBeenCalled()
      expect(_.remove).toHaveBeenCalledWith dirStructure.currentDir.files, {id: 7}

    it 'should remove file and run callback', ->
      spyOn(_, 'remove')

      $httpBackend.whenPOST(urlProviderMock.deleteFile, {file_id: 7})
      .respond(
        success: true
      )
      @fileObj.remove callbackSuccessFunction, callbackErrorFunction
      $httpBackend.flush()

      expect(callbackSuccessFunction).toHaveBeenCalled()
      expect(callbackErrorFunction).not.toHaveBeenCalled()
      expect(_.remove).toHaveBeenCalledWith dirStructure.currentDir.files, {id: 7}

    it 'should not remove file and run error callback', ->

      $httpBackend.whenPOST(urlProviderMock.deleteFile, {file_id: 7})
        .respond(
          error: 'Some error msg'
        )
      @fileObj.remove callbackSuccessFunction, callbackErrorFunction
      $httpBackend.flush()

      expect(callbackErrorFunction).toHaveBeenCalledWith
        error: 'Some error msg'

    it 'should not remove file and not run error callback if not set', ->
      $httpBackend.whenPOST(urlProviderMock.deleteFile, {file_id: 7})
        .respond(
          error: 'Some error msg'
        )
      @fileObj.remove callbackSuccessFunction
      $httpBackend.flush()

    it 'should not remove file and run error callback if response status is not 200', ->
      $httpBackend.whenPOST(urlProviderMock.deleteFile, {file_id: 7})
      .respond(500, {error: 'Some error msg'})
      @fileObj.remove callbackSuccessFunction, callbackErrorFunction
      $httpBackend.flush()

      expect(callbackErrorFunction).toHaveBeenCalledWith
        error: 'Some error msg'

    it 'should not remove file and not run error callback if not set when response status is not 200', ->
      $httpBackend.whenPOST(urlProviderMock.deleteFile, {file_id: 7})
      .respond(500, {error: 'Some error msg'})
      @fileObj.remove callbackSuccessFunction
      $httpBackend.flush()


    afterEach ->
      expect(spinnerServiceMock.show).toHaveBeenCalled()
      expect(spinnerServiceMock.hide).toHaveBeenCalled()

      $httpBackend.verifyNoOutstandingExpectation()
      $httpBackend.verifyNoOutstandingRequest()


  describe 'setCropData', ->
    it 'should set cropData values', ->
      @fileObj.setCropData 5, 10, 200, 100

      expect(@fileObj.cropData.x).toBe 5
      expect(@fileObj.cropData.y).toBe 10
      expect(@fileObj.cropData.width).toBe 200
      expect(@fileObj.cropData.height).toBe 100

    it 'should return itself', ->
      expect(@fileObj.setCropData 5, 10, 200, 100).toEqual @fileObj


  describe 'toJSON', ->
    it 'should return proper object', ->
      json = @fileObj.toJSON()

      expect(json.name).toEqual 'Some file name'
      expect(json.width).toEqual 200
      expect(json.height).toEqual 100
      expect(json.mime).toEqual 'image/jpeg'
      expect(json.image).toBeTruthy()
