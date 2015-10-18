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
        {
          id: 10
          name: "goal_2.pdf"
          src: "/uploads/15/02/17/4/49/24b556438274d7ca05be930bfca21770.pdf"
          mime: "application/pdf"
        }
        {
          id: 18
          name: "goal_2.jpg"
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


    @fileTypeFilterServiceMock =
      getFilterName: jasmine.createSpy()
      getCurrentFilterMimeList: jasmine.createSpy()

    @$filterMock = jasmine.createSpy()



    module('filemanager')
    module ($provide) =>
      $provide.value 'q', @$qMock
      $provide.value 'DirObj', @dirObjMock
      $provide.value 'FileObj', @fileObjMock
      $provide.value 'SpinnerService', @spinnerServiceMock
      $provide.value 'urlProvider', @urlProviderMock
      $provide.value 'fileTypeFilterService', @fileTypeFilterServiceMock
      $provide.value '$filter', @$filterMock

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


  describe 'searchFile', ->
    successCallback = null
    failureCallback = null
    successResponse = null
    failureResponse = null

    beforeEach ->
      successCallback = jasmine.createSpy()
      failureCallback = jasmine.createSpy()
      successResponse =
        success: true
        file:
          id: 7
          name: 'Some file'

      failureResponse =
        success: false

    afterEach ->
      @$httpBackend.verifyNoOutstandingExpectation()
      @$httpBackend.verifyNoOutstandingRequest()

    it 'should show spinner and then hide spinner if respond status 200', ->
      @$httpBackend.whenGET('ri_filemanager_api_file_search').respond successResponse

      @dirStructureService.searchFile '/some/path', successCallback, failureCallback

      @$httpBackend.flush()

      expect(@spinnerServiceMock.show).toHaveBeenCalled()
      expect(@spinnerServiceMock.hide).toHaveBeenCalled()
      expect(successCallback).toHaveBeenCalledWith successResponse.file

    it 'should show spinner and then hide spinner if respond status is not 200', ->
      @$httpBackend.whenGET('ri_filemanager_api_file_search').respond 404, failureResponse
      @dirStructureService.searchFile '/some/path', successCallback, failureCallback

      @$httpBackend.flush()

      expect(@spinnerServiceMock.show).toHaveBeenCalled()
      expect(@spinnerServiceMock.hide).toHaveBeenCalled()
      expect(failureCallback).toHaveBeenCalledWith failureResponse

    it 'should not call success function if is not set', ->
      @$httpBackend.whenGET('ri_filemanager_api_file_search').respond successResponse
      @dirStructureService.searchFile '/some/path', false, failureCallback
      @$httpBackend.flush()

      expect(successCallback.calls.count()).toEqual(0)


    it 'should call error function if response is 200 and response.success is false', ->
      @$httpBackend.whenGET('ri_filemanager_api_file_search').respond failureResponse
      @dirStructureService.searchFile '/some/path', successCallback, failureCallback
      @$httpBackend.flush()

      expect(failureCallback).toHaveBeenCalled()

    it 'should not call error function if response is 200 and response.success is false if errorCallback is not set', ->
      @$httpBackend.whenGET('ri_filemanager_api_file_search').respond failureResponse
      @dirStructureService.searchFile '/some/path', successCallback
      @$httpBackend.flush()

      expect(failureCallback.calls.count()).toEqual(0)


    it 'should not call error function if response is 404 if errorCallback is not set', ->
      @$httpBackend.whenGET('ri_filemanager_api_file_search').respond 404, failureResponse
      @dirStructureService.searchFile '/some/path', successCallback
      @$httpBackend.flush()

      expect(failureCallback.calls.count()).toEqual(0)

  describe 'files operation', ->
    files = []
    beforeEach ()->
      files = [
        {
          id: 1
          name: "goal.jpg"
          src: "/uploads/15/02/17/4/49/asjhdfkjasdkjfhaksdhfkajs.jpg"
          mime: "image/jpeg"
          width:1023
          height:682
        }
        {
          id: 8
          name: "goal.jpg"
          src: "/uploads/15/02/17/4/49/24b556438274d7ca05be930bfca21770.jpg"
          mime: "image/jpeg"
          width:1023
          height:682
        }
      ]
      @dirStructureService.filteredFilesList = files


    describe 'getPrevFile', ->
      it 'should return first file', ->
        expect(@dirStructureService.getPrevFile files[1]).toEqual files[0]

      it 'should return false if no previous file', ->
        expect(@dirStructureService.getPrevFile files[0]).toBeFalsy()


    describe 'getNextFile', ->
      it 'should return next file', ->
        expect(@dirStructureService.getNextFile files[0]).toEqual files[1]

      it 'should return false if no next file', ->
        expect(@dirStructureService.getNextFile files[1]).toBeFalsy()

    describe 'isFirstFile', ->
      it 'should return true if file is first', ->
        expect(@dirStructureService.isFirstFile files[0]).toBeTruthy()

      it 'should return false if file is not first file', ->
        expect(@dirStructureService.isFirstFile files[1]).toBeFalsy()

    describe 'isLastFile', ->
      it 'should return true if file is last', ->
        expect(@dirStructureService.isLastFile files[1]).toBeTruthy()

      it 'should return false if file is not last file', ->
        expect(@dirStructureService.isLastFile files[0]).toBeFalsy()

  describe 'getFilteredFiles', ->
    filterFunction = jasmine.createSpy()

    beforeEach () ->
      @$httpBackend.whenGET('ri_filemanager_api_index').respond @resultMock
      @dirStructureService.load(4)
      @$httpBackend.flush()

      @$filterMock.and.returnValue filterFunction

    afterEach () ->
      expect(@$filterMock).toHaveBeenCalledWith 'orderBy'


    it 'should call $filter orderBy', ->
      @dirStructureService.getFilteredFiles()
      expect(filterFunction).toHaveBeenCalledWith(@dirStructureService.currentDir.files, 'name')

    it 'should call $filter fileMime', ->
      @fileTypeFilterServiceMock.getFilterName.and.returnValue 'application/pdf'
      @fileTypeFilterServiceMock.getCurrentFilterMimeList.and.returnValue ['application/pdf']
      @dirStructureService.getFilteredFiles()

      expect(@$filterMock).toHaveBeenCalledWith 'fileMime'
      expect(filterFunction).toHaveBeenCalledWith @dirStructureService.currentDir.files, ['application/pdf']

    it 'should call $filter filter', ->
      search = 'search'
      @dirStructureService.getFilteredFiles search

      expect(@$filterMock).toHaveBeenCalledWith 'filter'
      expect(filterFunction).toHaveBeenCalledWith @dirStructureService.currentDir.files, {name: search}


