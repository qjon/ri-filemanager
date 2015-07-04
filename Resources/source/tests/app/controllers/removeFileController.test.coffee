describe 'removeFileController', ->

  beforeEach ->
    @scopeMock =
      file:
        id: 5
        name: 'Some file name'

    @removeFileController = new RemoveFile @scopeMock

  describe 'constructor', ->
    it 'should set $scope', ->
      expect(@removeFileController.$scope).toEqual @scopeMock

    it 'should set file', ->
      expect(@removeFileController.file).toEqual @scopeMock.file

    it 'should set empty error message', ->
      expect(@removeFileController.errorString).toEqual ''

  describe 'showAlert', ->
    it 'should set errorMessage value', ->
      errorString = 'Some error message'

      @removeFileController.showAlert
        message: errorString

      expect(@removeFileController.errorString).toEqual errorString