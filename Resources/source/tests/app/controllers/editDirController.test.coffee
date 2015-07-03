describe 'editDirController', ->

  beforeEach ->
    @dirMock =
      name: 'Some folder name'

    @scopeMock =
      dir: @dirMock

    @timeoutMock = jasmine.createSpy();

    @editDirController = new EditDir @scopeMock, @timeoutMock


  describe 'initialize', ->
    it 'should initialize values', ->
      expect(@editDirController.dir).toEqual @dirMock
      expect(@editDirController.folderName).toEqual @dirMock.name
      expect(@editDirController.orgName).toEqual @dirMock.name
