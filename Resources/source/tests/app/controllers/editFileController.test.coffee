describe 'editFileController', ->

  beforeEach ->
    @scopeMock =
      $broadcast: jasmine.createSpy()

    @filemanagerConfigMock =
      availableDimensions: [800, 1200]

    @editFileController = new EditFile @scopeMock, @filemanagerConfigMock


  describe 'constructor', ->
    it 'should set scope', ->
      expect(@editFileController.$scope).toEqual @scopeMock

    it 'should set sizeLis', ->
      expect(@editFileController.sizeList).toEqual @filemanagerConfigMock.availableDimensions

    it 'should set size', ->
      expect(@editFileController.size).toEqual @filemanagerConfigMock.availableDimensions[0]


  describe 'isSize', ->
    it 'should return true if value is equal current size', ->
      expect(@editFileController.isSize 800).toBeTruthy()

    it 'should return false if value is not equal current size', ->
      expect(@editFileController.isSize 900).toBeFalsy()


  describe 'setSize', ->
    it 'should $broadcast ImageCrop:changeSize', ->
      @editFileController.setSize 900
      expect(@scopeMock.$broadcast).toHaveBeenCalledWith 'ImageCrop:changeSize', @editFileController.size