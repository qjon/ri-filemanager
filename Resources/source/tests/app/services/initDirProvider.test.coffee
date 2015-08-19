describe 'initDirProvider', ->
  initDirProvider = null

  beforeEach ->
    initDirProvider = new InitDir()

  describe 'getFilePath', ->
    it 'should return cprrect path', ->
      path = '/some/path'
      top.tinymce.activeEditor.windowManager.getParams.and.returnValue
        url: path

      url = initDirProvider.getFilePath()

      expect(url).toEqual path

    it 'should return false', ->
      delete top
      url = initDirProvider.getFilePath()

      expect(url).toBeFalsy();
