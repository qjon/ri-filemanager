describe 'dirStructureService', ->


  beforeEach ->
    @resultMock =
      id: 4
      parent_id: 0,
      name:" Pierwszy katalog"
      createAt:
        date: "2015-02-17 08:33:59"
        timezone_type: 3
        timezone: "Europe/Warsaw"
      dirs: [
        {
          id: 5
          parent_id:4,
          name: "Drugi katalog",
          createAt:
            date: "2015-02-21 19:05:43"
            timezone_type: 3
            timezone: "Europe/Warsaw"
            parentsList: []
            files: []
        }
      ]
      files: [
        {
          id: 8
          name: "goal.jpg"
          src: "/uploads/15/02/17/4/49/24b556438274d7ca05be930bfca21770.jpg"
          mime: "image/jpeg"
          width:1023
          height:682
        }
      ]

    @$qMock =
      defer: jasmine.createSpy()
    @responseMock =
      success: jasmine.createSpy()
      error: jasmine.createSpy()

    @dirObjMock = (data, currentDir, that) ->
      @id = data.id

    @fileObjMock = (data, currentDir, that) ->
      @id = data.id

    @spinnerServiceMock =
      show: jasmine.createSpy()
      hide: jasmine.createSpy()

    @urlProviderMock =
      addFolder: '/folder/add'
      updateFolder: '/folder/update'
      loadFolder: '/folder/load'


    module('filemanager')
    module ($provide) =>
      $provide.value 'q', @$qMock
      $provide.value 'DirObj', @dirObjMock
      $provide.value 'FileObj', @fileObjMock
      $provide.value 'SpinnerService', @spinnerServiceMock
      $provide.value 'urlProvider', @urlProviderMock

      return

    inject (dirStructureService, $httpBackend) =>
      @$httpBackend = $httpBackend
      @dirStructureService = dirStructureService


    @$httpBackend.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond 200, {}


  describe 'constructor', ->
    it 'should set currentDir to false', ->
      expect(@dirStructureService.currentDir).toBeFalsy()

  describe 'addFolder', ->
    createdObj =
      id: 10
      name: 'New folder name'
      dirs: []
      files: []

    beforeEach ->
      @$httpBackend.whenGET('ri_filemanager_api_index').respond @resultMock
      @dirStructureService.load(4)
      @$httpBackend.flush()

    afterEach ->
      @$httpBackend.verifyNoOutstandingExpectation()
      @$httpBackend.verifyNoOutstandingRequest()

    it 'should show spinner and then hide spinner if respond status 200', ->
      @$httpBackend.whenPOST('ri_filemanager_api_directory_add').respond createdObj
      @dirStructureService.addFolder 'New folder name'
      @$httpBackend.flush()

      expect(@spinnerServiceMock.show.calls.count()).toBe 2
      expect(@spinnerServiceMock.hide.calls.count()).toBe 2

    it 'should show spinner and then hide spinner if respond status is not 200', ->
      @$httpBackend.whenPOST('ri_filemanager_api_directory_add').respond 500, {}
      @dirStructureService.addFolder 'New folder name'
      @$httpBackend.flush()

      expect(@spinnerServiceMock.show.calls.count()).toBe 2
      expect(@spinnerServiceMock.hide.calls.count()).toBe 2

    it 'should have add new dir with id 10', ->
      @$httpBackend.whenPOST('ri_filemanager_api_directory_add').respond createdObj
      @dirStructureService.addFolder 'New folder name'
      @$httpBackend.flush()

      newDir = @dirStructureService.currentDir.dirs[1]
      expect(newDir.id).toEqual 10

    it 'should call success callback function', ->
      callbackFunction = jasmine.createSpy()
      @$httpBackend.whenPOST('ri_filemanager_api_directory_add').respond createdObj
      @dirStructureService.addFolder 'New folder name', callbackFunction
      @$httpBackend.flush()

      expect(callbackFunction).toHaveBeenCalled()

    it 'should call error callback function if set', ->
      callbackFunction = jasmine.createSpy()
      errorCallbackFunction = jasmine.createSpy()
      @$httpBackend.whenPOST('ri_filemanager_api_directory_add').respond 500, createdObj
      @dirStructureService.addFolder 'New folder name', callbackFunction, errorCallbackFunction
      @$httpBackend.flush()

      expect(errorCallbackFunction).toHaveBeenCalled()

    it 'should not call error callback function if not set', ->
      callbackFunction = jasmine.createSpy()
      errorCallbackFunction = jasmine.createSpy()
      @$httpBackend.whenPOST('ri_filemanager_api_directory_add').respond 500, createdObj
      @dirStructureService.addFolder 'New folder name', callbackFunction
      @$httpBackend.flush()

      expect(errorCallbackFunction).not.toHaveBeenCalled()


  describe 'getFileById', ->

    beforeEach ->
      @$httpBackend.whenGET('ri_filemanager_api_index').respond @resultMock
      @dirStructureService.load(4)
      @$httpBackend.flush()

    it 'should call underscore find', ->
      spyOn(_, 'find')
      @dirStructureService.getFileById 4

      expect(_.find).toHaveBeenCalledWith @dirStructureService.currentDir.files, {id: 4}


  describe 'getSubDirById', ->

    beforeEach ->
      @$httpBackend.whenGET('ri_filemanager_api_index').respond @resultMock
      @dirStructureService.load(4)
      @$httpBackend.flush()

    it 'should call underscore find', ->
      spyOn(_, 'find')
      @dirStructureService.getSubDirById 10

      expect(_.find).toHaveBeenCalledWith @dirStructureService.currentDir.dirs, {id: 10}


  describe 'load', ->

    beforeEach ->
      @$httpBackend.whenGET('ri_filemanager_api_index').respond @resultMock
      @dirStructureService.load(4)
      @$httpBackend.flush()

    it 'should return this if load the same dir twice', ->
      expect(@dirStructureService.load(4)).toEqual @dirStructureService

    it 'should only once call show and hide spinner', ->
      @dirStructureService.load(4)
      expect(@spinnerServiceMock.show.calls.count()).toBe 1
      expect(@spinnerServiceMock.hide.calls.count()).toBe 1


  describe 'reload', ->

    beforeEach ->
      @$httpBackend.whenGET('ri_filemanager_api_index').respond @resultMock
      @dirStructureService.load(4)
      @$httpBackend.flush()

    it 'should reset currentDirId and call load function', ->
      spyOn @dirStructureService, 'load'
      @dirStructureService.reload()

      expect(@dirStructureService.currentDir.id).toEqual null
      expect(@dirStructureService.load).toHaveBeenCalledWith 4

