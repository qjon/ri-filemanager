describe 'addDirController', ->

  beforeEach ->
    @scopeMock =
      $hide: jasmine.createSpy()

    @timeoutMock = jasmine.createSpy()

    @dirStructureServiceMock =
      addFolder: jasmine.createSpy()


    @editDirController = new AddDir @scopeMock, @timeoutMock, @dirStructureServiceMock


  describe 'initialize', ->
    it 'should set empty folder name', ->
      expect(@editDirController.folderName).toEqual ''


  describe 'addFolder', ->
    it 'should do nothing if folder name is empty string', ->
      @editDirController.addFolder()

      expect(@dirStructureServiceMock.addFolder).not.toHaveBeenCalled()

    it 'should add folder if name is not empty', ->
      @editDirController.folderName = 'New folder name'

      @editDirController.addFolder()

      expect(@dirStructureServiceMock.addFolder).toHaveBeenCalledWith @editDirController.folderName, @scopeMock.$hide