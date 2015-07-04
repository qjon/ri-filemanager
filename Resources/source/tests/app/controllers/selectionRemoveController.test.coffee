describe 'selectionRemoveController', ->

  beforeEach ->
    @fileMock =
      id: 5
      name: 'Some file name'

    @dirMock =
      id: 7
      name: 'Some dir name'

    @scopeMock =
      files: [@fileMock]
      dirs: [@dirMock]

    @copyPasteServiceMock = {}


    @selectionRemoveController = new SelectionRemove @scopeMock, @copyPasteServiceMock


  describe 'constructor', ->
    it 'should set $scope', ->
      expect(@selectionRemoveController.$scope).toEqual @scopeMock

    it 'should set files', ->
      expect(@selectionRemoveController.files).toEqual @scopeMock.files

    it 'should set dirs', ->
      expect(@selectionRemoveController.dirs).toEqual @scopeMock.dirs

    it 'should set empty error message', ->
      expect(@selectionRemoveController.errorString).toEqual ''

    it 'should set copy-paste service', ->
      expect(@selectionRemoveController.copyPasteService).toEqual @copyPasteServiceMock

  describe 'showAlert', ->
    it 'should set errorMessage value', ->
      errorString = 'Some error message'

      @selectionRemoveController.showAlert
        message: errorString

      expect(@selectionRemoveController.errorString).toEqual errorString