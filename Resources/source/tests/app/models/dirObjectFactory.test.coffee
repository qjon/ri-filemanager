describe 'dirObjectFactory', ->
  $httpBackend = null
  modalServiceMock = null
  spinnerServiceMock = null
  urlProviderMock = null

  parentDirMock = null
  dirStructureMock = null

  $httpBackend = null

  dirData =
    id: 7
    name: 'Dir name'

  beforeEach ->
    module 'filemanager'

    parentDirMock = {}
    dirStructureMock =
      currentDir:
        id: 100
        files: []
        dirs: []

    modalServiceMock =
      open: jasmine.createSpy()

    spinnerServiceMock =
      show: jasmine.createSpy()
      hide: jasmine.createSpy()

    urlProviderMock =
      updateFolder: '/folder/update'
      deleteFolder: '/folder/remove'


    module ($provide) ->
      $provide.value 'modalService', modalServiceMock
      $provide.value 'SpinnerService', spinnerServiceMock
      $provide.value 'urlProvider', urlProviderMock

      return

    inject (_DirObj_, _$httpBackend_) ->
      $httpBackend = _$httpBackend_
      @dirObj = new _DirObj_ dirData, parentDirMock, dirStructureMock

      return


    $httpBackend.whenGET('/bundles/rifilemanager/translations/lang_en_EN.json').respond 200, {}


  describe 'constructor', ->
    it 'should set empty dirs list', ->
      expect(@dirObj.dirs).toEqual []

    it 'should set empty files list', ->
      expect(@dirObj.dirs).toEqual []

    it 'should set id', ->
      expect(@dirObj.id).toEqual dirData.id

    it 'should set name', ->
      expect(@dirObj.name).toEqual dirData.name


  describe '_setData', ->
    it 'should call angular.extend', ->
      data =
        id: 3
        name: 'new dir name'

      spyOn angular, 'extend'

      @dirObj._setData data

      expect(angular.extend).toHaveBeenCalledWith @dirObj, data


  describe 'getSubDir', ->
    it 'should call _.find', ->
      spyOn _, 'find'

      @dirObj.getSubDir 7

      expect(_.find).toHaveBeenCalledWith @dirObj.dirs, {id: 7}


  describe 'getParenrDir', ->
    it 'should return proper value', ->
      expect(@dirObj.getParentDir()).toEqual parentDirMock


  describe 'getDirStructure', ->
    it 'should return proper value', ->
      expect(@dirObj.getDirStructure()).toEqual dirStructureMock


  describe 'openDialogEditFolder', ->
    it 'should call open modal', ->
      event = {}
      @dirObj.openDialogEditFolder(event)

      expect(modalServiceMock.open).toHaveBeenCalledWith event, '/templates/dir_edit.html', {dir: @dirObj}


  describe 'openDialogRemoveFolder', ->
    it 'should call open modal', ->
      event = {}
      @dirObj.openDialogRemoveFolder(event)

      expect(modalServiceMock.open).toHaveBeenCalledWith event, '/templates/dir_remove.html', {dir: @dirObj}


  describe 'remove', ->

    afterEach ->
      $httpBackend.verifyNoOutstandingExpectation()
      $httpBackend.verifyNoOutstandingRequest()

      expect(spinnerServiceMock.show).toHaveBeenCalled()
      expect(spinnerServiceMock.hide).toHaveBeenCalled()


    it 'should call $http.post and remove dir from list', ->
      callbackSuccess = jasmine.createSpy()

      $httpBackend.whenDELETE 'ri_filemanager_api_directory_remove'
        .respond(
          success: true
        )

      spyOn _, 'remove'

      @dirObj.remove()

      $httpBackend.flush();

      expect(_.remove).toHaveBeenCalledWith dirStructureMock.currentDir.dirs, {id: @dirObj.id}
      expect(callbackSuccess).not.toHaveBeenCalled()


    it 'should call callbackSuccess if set', ->
      callbackSuccess = jasmine.createSpy()

      $httpBackend.whenDELETE 'ri_filemanager_api_directory_remove'
        .respond(
          success: true
        )

      spyOn _, 'remove'

      @dirObj.remove(callbackSuccess)

      $httpBackend.flush();

      expect(_.remove).toHaveBeenCalledWith dirStructureMock.currentDir.dirs, {id: @dirObj.id}
      expect(callbackSuccess).toHaveBeenCalled()


    it 'should call callbackError if error returned', ->
      callbackSuccess = jasmine.createSpy()
      callbackError = jasmine.createSpy()

      $httpBackend.whenDELETE 'ri_filemanager_api_directory_remove'
        .respond(
          success: false
          error: 'Some error'
        )


      @dirObj.remove(callbackSuccess, callbackError)

      $httpBackend.flush();

      expect(callbackSuccess).not.toHaveBeenCalled()
      expect(callbackError).toHaveBeenCalled()


    it 'should not call callbackError if error returned and errorCallback not defined', ->
      callbackSuccess = jasmine.createSpy()
      callbackError = jasmine.createSpy()

      $httpBackend.whenDELETE 'ri_filemanager_api_directory_remove'
        .respond(
          success: false
          error: 'Some error'
        )


      @dirObj.remove()

      $httpBackend.flush();

      expect(callbackSuccess).not.toHaveBeenCalled()
      expect(callbackError).not.toHaveBeenCalled()


    it 'should call callbackError if status 200 not returned', ->
      callbackSuccess = jasmine.createSpy()
      callbackError = jasmine.createSpy()

      $httpBackend.whenDELETE 'ri_filemanager_api_directory_remove'
        .respond(
          404
          {
            success: false
            error: 'Some error'
          }
        )


      @dirObj.remove(callbackSuccess, callbackError)

      $httpBackend.flush();

      expect(callbackError).toHaveBeenCalled()
      expect(callbackSuccess).not.toHaveBeenCalled()


    it 'should not call callbackError if status 200 not returned and callback not set', ->
      callbackSuccess = jasmine.createSpy()
      callbackError = jasmine.createSpy()

      $httpBackend.whenDELETE 'ri_filemanager_api_directory_remove'
        .respond(
          404
          {
            success: false
            error: 'Some error'
          }
        )


      @dirObj.remove(callbackSuccess)

      $httpBackend.flush();

      expect(callbackSuccess).not.toHaveBeenCalled()
      expect(callbackError).not.toHaveBeenCalled()


  describe 'save', ->
    newName = 'New name of the folder'

    afterEach ->
      $httpBackend.verifyNoOutstandingExpectation()
      $httpBackend.verifyNoOutstandingRequest()

      expect(spinnerServiceMock.show).toHaveBeenCalled()
      expect(spinnerServiceMock.hide).toHaveBeenCalled()


    it 'should call $http.post and save dir', ->
      callbackSuccess = jasmine.createSpy()
      newName = 'New name of the folder'

      $httpBackend.whenPUT 'ri_filemanager_api_directory_edit'
        .respond(
          success: true
        )

      spyOn _, 'remove'

      @dirObj.save newName

      $httpBackend.flush();

      expect(callbackSuccess).not.toHaveBeenCalled()


    it 'should save dir and call calbackSuccess', ->
      callbackSuccess = jasmine.createSpy()

      $httpBackend.whenPUT 'ri_filemanager_api_directory_edit'
        .respond(
          success: true
        )

      spyOn _, 'remove'

      @dirObj.save newName, callbackSuccess

      $httpBackend.flush();

      expect(callbackSuccess).toHaveBeenCalled()


    it 'should call callbackError if error returned', ->
      callbackSuccess = jasmine.createSpy()
      callbackError = jasmine.createSpy()

      $httpBackend.whenPUT 'ri_filemanager_api_directory_edit'
        .respond(
          401
          {
            success: false
            error: 'Some error'
          }
        )


      @dirObj.save newName, callbackSuccess, callbackError

      $httpBackend.flush();

      expect(callbackSuccess).not.toHaveBeenCalled()
      expect(callbackError).toHaveBeenCalled()


    it 'should not call callbackError if error returned and callback not set', ->
      callbackSuccess = jasmine.createSpy()
      callbackError = jasmine.createSpy()

      $httpBackend.whenPUT 'ri_filemanager_api_directory_edit'
        .respond(
          401
          {
            success: false
            error: 'Some error'
          }
        )


      @dirObj.save newName, callbackSuccess

      $httpBackend.flush();

      expect(callbackSuccess).not.toHaveBeenCalled()
      expect(callbackError).not.toHaveBeenCalled()
