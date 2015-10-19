describe 'SelectionService', ->

  fileOneMock =
    id: 1
    name: 'First file'

  fileTwoMock =
    id: 12
    name: 'Second file'


  folderOneMock =
    id: 7
    name: 'First folder'

  folderTwoMock =
    id: 10
    name: 'Second folder'

  beforeEach ->
    @previewService = new Selection

  describe 'constructor', ->
    it 'files should be empty array by default', ->
      expect(@previewService.getFiles()).toEqual []

    it 'folders should be empty array by default', ->
      expect(@previewService.getFolders()).toEqual []


  describe 'files functions', ->
    beforeEach ->
      @previewService
      .clear()
      .addFile fileTwoMock
      .addFile fileOneMock


    describe 'addFiles', ->
      it 'should add file and return itself', ->
        files = @previewService.getFiles()

        expect(files[0]).toEqual fileTwoMock
        expect(files[1]).toEqual fileOneMock


    describe 'getFileById', ->
      it 'should return firstFileMock', ->
        expect(@previewService.getFileById 1).toEqual fileOneMock

      it 'should return false if file is not exist', ->
        expect(@previewService.getFileById 5).toBeFalsy()


    describe 'getFiles', ->
      it 'should return array of two eleemnts', ->
        expect(@previewService.getFiles().length).toBe 2


    describe 'getFileIds', ->
      it 'should return proper array', ->
        expect(@previewService.getFilesIds()).toEqual [12, 1]

      it 'should return empty array if no file is added', ->
        @previewService.clear();
        expect(@previewService.getFilesIds()).toEqual []


    describe 'isSelectedFile', ->
      it 'should return true if file exist', ->
        expect(@previewService.isSelectedFile 12).toBeTruthy()

      it 'should return false if file not exist', ->
        expect(@previewService.isSelectedFile 21).toBeFalsy()


    describe 'removeFile', ->
      it 'should remove file from list', ->
        expect(@previewService.getFiles().length).toBe 2
        @previewService.removeFile 1

        expect(@previewService.getFiles().length).toBe 1

      it 'should not remove file from list if the file not exists', ->
        expect(@previewService.getFiles().length).toBe 2
        @previewService.removeFile 123

        expect(@previewService.getFiles().length).toBe 2

      it 'should return it self', ->
        expect(@previewService.removeFile 123).toEqual @previewService


    describe 'toggleFile', ->
      event = {}
      beforeEach ->
        event.preventDefault = jasmine.createSpy()

      it 'should prevent event default action', ->
        @previewService.toggleFile event, fileOneMock

        expect(event.preventDefault).toHaveBeenCalled()

      it 'should not change files selection', ->
        expect(@previewService.getFiles().length).toBe 2
        @previewService.toggleFile event, fileOneMock

        expect(@previewService.getFiles().length).toBe 2

      it 'should add file to selection', ->
        event.ctrlKey = true
        newFile =
          id: 17
          name: 'New file'

        expect(@previewService.getFiles().length).toBe 2

        @previewService.toggleFile event, newFile

        expect(@previewService.getFiles().length).toBe 3

      it 'should remove file from selection', ->
        event.ctrlKey = true

        expect(@previewService.getFiles().length).toBe 2

        @previewService.toggleFile event, fileOneMock

        expect(@previewService.getFiles().length).toBe 1


  describe 'folders functions', ->
    beforeEach ->
      @previewService
      .clear()
      .addFolder folderOneMock
      .addFolder folderTwoMock

    describe 'addFolder', ->
      it 'should add folder', ->
        expect(@previewService.getFolders().length).toBe 2

      it 'should return itself', ->
        expect(@previewService.addFolder folderOneMock).toEqual @previewService


    describe 'getFolders', ->
      it 'should return proper array', ->
        expect(@previewService.getFolders()).toEqual [folderOneMock, folderTwoMock]


    describe 'getFoldersIds', ->
      it 'should return array [7,10]', ->
        expect(@previewService.getFoldersIds()).toEqual [7,10]

      it 'should return empty array', ->
        @previewService.clear()

        expect(@previewService.getFoldersIds()).toEqual []


    describe 'isSelectedFolder', ->
      it 'should return true if folder with givven id is add to selecetion', ->
        expect(@previewService.isSelectedFolder 10).toBeTruthy()

      it 'should return false if folder with givven id is not added to selecetion', ->
        expect(@previewService.isSelectedFolder 21).toBeFalsy()


    describe 'removeFolder', ->
      it 'should reduce selected folders list', ->
        expect(@previewService.getFolders().length).toBe 2
        @previewService.deleteFolder 7

        expect(@previewService.getFolders().length).toBe 1

      it 'should not reduce selected folders list if folder not found', ->
        expect(@previewService.getFolders().length).toBe 2
        @previewService.deleteFolder 17

        expect(@previewService.getFolders().length).toBe 2

      it 'should return itself', ->
        expect(@previewService.deleteFolder 7).toEqual @previewService


    describe 'toggleFolder', ->
      event = {}

      beforeEach ->
        event.stopPropagation = jasmine.createSpy()

      it 'should prevent event propagation', ->
        @previewService.toggleFolder event, folderOneMock

        expect(event.stopPropagation).toHaveBeenCalled()

      it 'should add folder to selection', ->
        event.ctrlKey = true
        newFolder =
          id: 31
          name: 'New folder name'

        expect(@previewService.getFolders().length).toBe 2

        @previewService.toggleFolder event, newFolder

        expect(@previewService.getFolders().length).toBe 3

      it 'should reduce folders list', ->
        event.ctrlKey = true

        expect(@previewService.getFolders().length).toBe 2

        @previewService.toggleFolder event, folderOneMock

        expect(@previewService.getFolders().length).toBe 1


    describe 'isEmptySelection', ->

      beforeEach ->
        @previewService.clear()

      it 'should return true', ->
        expect(@previewService.isEmptySelection()).toBeTruthy()

      it 'should return false if is any file', ->
        @previewService.addFile fileOneMock

        expect(@previewService.isEmptySelection()).toBeFalsy()

      it 'should return false if is any folder', ->
        @previewService.addFolder folderOneMock

        expect(@previewService.isEmptySelection()).toBeFalsy()

