describe 'crop directive', ->
  cropDirective = null

  $timeoutMock = null
  configProviderMock = null
  scopeMock = null
  elementMock = null

  beforeEach ->
    scopeMock =
      $on: jasmine.createSpy()

    $timeoutMock = jasmine.createSpy()

    elementMock =
      cropper: jasmine.createSpy()

    configProviderMock =
      availableDimensions: [
        {
          width: 100
          height: 60
        }
      ]

    cropDirective = new Crop $timeoutMock, configProviderMock


  describe 'constructor', ->
    it 'should set proper values', ->
      expect(cropDirective.restrict).toEqual 'A'
      expect(cropDirective.replace).toBeTruthy()
      expect(cropDirective.scope.file).toEqual '='


    describe 'link', ->

      beforeEach ->

        callback = (cb) ->
          cb()
          return

        $timeoutMock.and.callFake callback

        cropDirective.link scopeMock, elementMock
        return


      it 'should calls scope.$on', ->
        expect(scopeMock.$on).toHaveBeenCalled()


      it 'should call $timeout', ->
        expect($timeoutMock).toHaveBeenCalled()