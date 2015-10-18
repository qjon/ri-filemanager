describe 'FileTypeFilter', ->
  filterName = 'audio'
  fileTypesMock =
    getType: jasmine.createSpy()
    isDefinedType: jasmine.createSpy()

  beforeEach ->
    module 'filemanager'

    @fileTypeFilterServiceMock = new FileTypeFilter fileTypesMock

  describe 'clearFilter', ->
    it 'should set filter name to false', ->
      fileTypesMock.isDefinedType.and.returnValue true

      @fileTypeFilterServiceMock.setFilterName filterName

      expect(@fileTypeFilterServiceMock.getFilterName()).toEqual filterName
      expect(fileTypesMock.isDefinedType.calls.count()).toEqual 1

      @fileTypeFilterServiceMock.clearFilter()
      expect(@fileTypeFilterServiceMock.getFilterName()).toBeFalsy()

  describe 'getFilterName', ->
    it 'should get default filter name equal false', ->
      expect(@fileTypeFilterServiceMock.getFilterName()).toBeFalsy()

  describe 'getCurrentFilterMimeList', ->
    it 'should call FileTypes.getType', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterServiceMock.setFilterName filterName
      @fileTypeFilterServiceMock.getCurrentFilterMimeList()

      expect(fileTypesMock.getType).toHaveBeenCalledWith filterName

  describe 'isActiveFilter', ->
    it 'should return true if checked value is the same as setted', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterServiceMock.setFilterName filterName

      expect(@fileTypeFilterServiceMock.isActiveFilter filterName).toBeTruthy()

    it 'should return true if checked value is not the same as setted', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterServiceMock.setFilterName filterName

      expect(@fileTypeFilterServiceMock.isActiveFilter 'video').toBeFalsy()

  describe 'setFilterName', ->
    it 'should clear filter name if filter is empty', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterServiceMock.setFilterName filterName
      expect(@fileTypeFilterServiceMock.getFilterName()).toEqual filterName

      @fileTypeFilterServiceMock.setFilterName()

      expect(@fileTypeFilterServiceMock.getFilterName()).toBeFalsy()

    it 'should clear filter name if filter is false', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterServiceMock.setFilterName filterName
      expect(@fileTypeFilterServiceMock.getFilterName()).toEqual filterName

      @fileTypeFilterServiceMock.setFilterName false

      expect(@fileTypeFilterServiceMock.getFilterName()).toBeFalsy()

    it 'should clear filter name if filter is not defined', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterServiceMock.setFilterName filterName
      expect(@fileTypeFilterServiceMock.getFilterName()).toEqual filterName

      fileTypesMock.isDefinedType.and.returnValue false
      @fileTypeFilterServiceMock.setFilterName 'fakeFilterName'

      expect(@fileTypeFilterServiceMock.getFilterName()).toBeFalsy()