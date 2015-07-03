describe 'FileTypeFilter', ->
  filterName = 'audio'
  fileTypesMock =
    getType: jasmine.createSpy()
    isDefinedType: jasmine.createSpy()

  beforeEach ->
    module 'filemanager'

    @fileTypeFilterService = new FileTypeFilter fileTypesMock

  describe 'clearFilter', ->
    it 'should set filter name to false', ->
      fileTypesMock.isDefinedType.and.returnValue true

      @fileTypeFilterService.setFilterName filterName

      expect(@fileTypeFilterService.getFilterName()).toEqual filterName
      expect(fileTypesMock.isDefinedType.calls.count()).toEqual 1

      @fileTypeFilterService.clearFilter()
      expect(@fileTypeFilterService.getFilterName()).toBeFalsy()

  describe 'getFilterName', ->
    it 'should get default filter name equal false', ->
      expect(@fileTypeFilterService.getFilterName()).toBeFalsy()

  describe 'getCurrentFilterMimeList', ->
    it 'should call FileTypes.getType', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterService.setFilterName filterName
      @fileTypeFilterService.getCurrentFilterMimeList()

      expect(fileTypesMock.getType).toHaveBeenCalledWith filterName

  describe 'isActiveFilter', ->
    it 'should return true if checked value is the same as setted', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterService.setFilterName filterName

      expect(@fileTypeFilterService.isActiveFilter filterName).toBeTruthy()

    it 'should return true if checked value is not the same as setted', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterService.setFilterName filterName

      expect(@fileTypeFilterService.isActiveFilter 'video').toBeFalsy()

  describe 'setFilterName', ->
    it 'should clear filter name if filter is empty', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterService.setFilterName filterName
      expect(@fileTypeFilterService.getFilterName()).toEqual filterName

      @fileTypeFilterService.setFilterName()

      expect(@fileTypeFilterService.getFilterName()).toBeFalsy()

    it 'should clear filter name if filter is false', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterService.setFilterName filterName
      expect(@fileTypeFilterService.getFilterName()).toEqual filterName

      @fileTypeFilterService.setFilterName false

      expect(@fileTypeFilterService.getFilterName()).toBeFalsy()

    it 'should clear filter name if filter is not defined', ->
      fileTypesMock.isDefinedType.and.returnValue true
      @fileTypeFilterService.setFilterName filterName
      expect(@fileTypeFilterService.getFilterName()).toEqual filterName

      fileTypesMock.isDefinedType.and.returnValue false
      @fileTypeFilterService.setFilterName 'fakeFilterName'

      expect(@fileTypeFilterService.getFilterName()).toBeFalsy()