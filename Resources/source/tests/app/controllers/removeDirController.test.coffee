describe 'removeDirController', ->

  beforeEach ->
    @scopeMock =
      dir:
        id: 7
        name: 'Some dir'

    @dirStructureMock = {}

    @removeDirController = new RemoveDir @scopeMock, @dirStructureMock


  describe 'constructor', ->
    it 'should set scope', ->
      expect(@removeDirController.$scope).toEqual @scopeMock

    it 'should set removeDir service', ->
      expect(@removeDirController.dirStructure).toEqual @dirStructureMock

    it 'should set empty error string', ->
      expect(@removeDirController.errorString).toEqual ''

  describe 'showAlert', ->
    it 'should set errorString proper value', ->
      errorString = 'Some error message'

      @removeDirController.showAlert
        message: errorString

      expect(@removeDirController.errorString).toEqual errorString