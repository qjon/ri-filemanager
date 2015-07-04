describe 'FileTypesservice', ->
  fileTypes = null
  filemanagerConfigMock =
    mimeTypes:
      images: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/png']
      audio: ['audio/mpeg', 'audio/x-ms-wma', 'audio/vnd.rn-realaudio', 'audio/x-wav']
      video: ['video/mpeg', 'video/mp4', 'video/quicktime', 'video/x-ms-wmv']
      archive: ['application/zip']

  beforeEach ->
    fileTypes = new FileTypes filemanagerConfigMock

  describe 'getTypes', ->
    it 'should return config file types', ->
      expect(fileTypes.getTypes()).toEqual filemanagerConfigMock.mimeTypes

  describe 'getType', ->
    it 'should return correct type if type is known', ->
      expect(fileTypes.getType 'images').toEqual filemanagerConfigMock.mimeTypes.images

    it 'should return empty array if type is unknown', ->
      expect(fileTypes.getType('articles')).toEqual([])

  describe 'hasTypeGetMime', ->
    it 'should return true if mimetype exists in type', ->
      expect(fileTypes.hasTypeGetMime 'images', 'image/jpg').toBeTruthy()

    it 'should return false if mimetype dont exists in type', ->
      expect(fileTypes.hasTypeGetMime 'audio', 'image/jpg').toBeFalsy()

  describe 'isDefinedType', ->
    it 'should return true if type is defined', ->
      expect(fileTypes.isDefinedType 'images').toBeTruthy()

    it 'should return false if type is not defined', ->
      expect(fileTypes.isDefinedType 'articles').toBeFalsy()