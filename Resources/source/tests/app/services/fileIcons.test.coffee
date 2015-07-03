describe 'fileIcons', ->
  fileIcons = null
  filemanagerConfigMock =
    fileIconTypesDir: '/path/'
    blankIconType: 'blank.png'

  beforeEach ->
    fileIcons = new FileIcons filemanagerConfigMock

  describe 'getIconPath', ->
    it 'should return path to gif icon', ->
      expect(fileIcons.getIconPath 'plik.gif').toEqual '/path/gif.png'

    it 'should return path to blank icon', ->
      expect(fileIcons.getIconPath 'plik.non').toEqual '/path/blank.png'
