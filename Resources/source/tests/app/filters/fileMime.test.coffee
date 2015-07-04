describe 'fileMimeFilter', ->

  fileTypesMimeMock = ['image/jpg', 'image/png']

  fileOne =
    id: 8
    name: 'PDF'
    mime: 'application/pdf'

  fileTwo =
    id: 10
    name: 'Image JPEG'
    mime: 'image/jpg'

  fileThree =
    id: 21
    name: 'Image PNG'
    mime: 'image/png'


  beforeEach ->
    @fileMimeFilter = new fileMime()


  describe 'constructor', ->
    it 'should return empty array', ->
      files = [fileOne]

      expect(@fileMimeFilter files, fileTypesMimeMock).toBeTruthy()

    describe 'should return all array', ->
      it 'if types array is undefined', ->
        files = [fileOne, fileThree, fileTwo]
        expect(@fileMimeFilter files, undefined).toEqual files;

      it 'if types array is empty', ->
        files = [fileOne, fileThree, fileTwo]
        expect(@fileMimeFilter files, []).toEqual files;

    it 'should return reduced array', ->
      files = [fileOne, fileThree, fileTwo]
      expect(@fileMimeFilter files, fileTypesMimeMock).toEqual [fileThree, fileTwo]