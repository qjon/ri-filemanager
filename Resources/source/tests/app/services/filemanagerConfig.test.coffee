describe 'filemanagerConfig', ->
  filemanagerConfig = null
  filemanagerConfigProvider = null

  onInsertMock =
    oninsert: jasmine.createSpy()

  configDataMock =
    allowChangeLanguage: true
    defaultLanguage: 'en_EN'
    standAlone: true
    fileIconTypesDir: '/abc'
    blankIconType: '_blank.png'
    mimeTypes:
      images: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/png']
      audio: ['audio/mpeg', 'audio/x-ms-wma', 'audio/vnd.rn-realaudio', 'audio/x-wav']
      video: ['video/mpeg', 'video/mp4', 'video/quicktime', 'video/x-ms-wmv']
      archive: ['application/zip']
    filesSelectCallback: null
    dirSelectCallback: null
    availableDimensions: [
      {
        name: 'Artykuł'
        width: 750
        height: 300
      }
      {
        name: 'Slider'
        width: 1140
        height: 350
      }
    ]

  beforeEach ->
    filemanagerConfigProvider = new Config()
    filemanagerConfig = filemanagerConfigProvider.$get()
    return


  describe 'provider', ->
    describe 'setConfig', ->
      it 'should extend data', ->
        config =
          abc: 'xyz'

        spyOn(angular, 'extend')
        filemanagerConfigProvider.setConfig config

        expect(angular.extend).toHaveBeenCalled()

  describe 'service', ->
    it 'should return correct service', ->
      filemanagerConfigProvider.setConfig configDataMock

      expect(filemanagerConfig).toEqual configDataMock

    describe 'fileSelectCallback', ->
      it 'should call window manager functions', ->
        file =
          src: 'path/to/file'
          name: 'filename'
          width: 100
          height: 50

        top.tinymce.activeEditor.windowManager.getParams = jasmine.createSpy().and.returnValue onInsertMock
        top.tinymce.activeEditor.windowManager.close = jasmine.createSpy()

        filemanagerConfig.filesSelectCallback [file]

        expect(top.tinymce.activeEditor.windowManager.getParams).toHaveBeenCalled()
        expect(top.tinymce.activeEditor.windowManager.close).toHaveBeenCalled()
        expect(onInsertMock.oninsert).toHaveBeenCalledWith file