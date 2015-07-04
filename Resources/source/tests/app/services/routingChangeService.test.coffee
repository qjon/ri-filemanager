describe 'routingChangeServiceMock', ->
  windowMock = null
  locationMock = null
  dirStructureServiceMock = null
  eventMock = null

  beforeEach ->
    eventMock =
      stopPropagation: jasmine.createSpy()

    locationMock =
      url: jasmine.createSpy()

    windowMock =
      open: jasmine.createSpy()

    dirStructureServiceMock =
      currentDir:
        id: 5
        parentId: 7

    @routingChangeService = new RoutingChange(windowMock, locationMock, dirStructureServiceMock)


  describe 'goToFolder', ->
    it 'should call location.url with proper value', ->
      eventMock.ctrlKey = false

      @routingChangeService.goToFolder eventMock, 5
      expect(locationMock.url).toHaveBeenCalledWith '/dir/5'

    it 'should not call location.url if event.ctrlKey is true', ->
      eventMock.ctrlKey = true

      @routingChangeService.goToFolder eventMock, 5
      expect(locationMock.url).not.toHaveBeenCalled()


  describe 'goToFolderUp', ->
    it 'should call goToFolder with proper values', ->
      spyOn @routingChangeService, 'goToFolder'

      @routingChangeService.goToFolderUp()

      expect(@routingChangeService.goToFolder).toHaveBeenCalledWith {}, 7

    it 'should not call goToFolder', ->
      dirStructureServiceMock.currentDir.id = 0

      spyOn @routingChangeService, 'goToFolder'

      @routingChangeService.goToFolderUp()

      expect(@routingChangeService.goToFolder).not.toHaveBeenCalled()


  describe 'downloadFile', ->
    file =
      id: 17
      name: 'File name'
      src: '/some/file/path.jpg'

    it 'should stop event propagation', ->
      @routingChangeService.downloadFile file, eventMock

      expect(eventMock.stopPropagation).toHaveBeenCalled()

    it 'should call window.open', ->
      @routingChangeService.downloadFile file, eventMock

      expect(windowMock.open).toHaveBeenCalledWith file.src