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
    @selectionService = new Selection

  describe 'constructor', ->
    it 'files should be empty array by default', ->
      expect(@selectionService.getFiles()).toEqual []

    it 'folders should be empty array by default', ->
      expect(@selectionService.getFolders()).toEqual []


  describe 'files functions', ->
    beforeEach ->
      @selectionService
        .clear()
        .addFile fileTwoMock
        .addFile fileOneMock


    describe 'addFiles', ->
      it 'should add file and return itself', ->
        files = @selectionService.getFiles()

        expect(files[0]).toEqual fileTwoMock
        expect(files[1]).toEqual fileOneMock


    describe 'getFileById', ->
      it 'should return firstFileMock', ->
        expect(@selectionService.getFileById 1).toEqual fileOneMock

      it 'should return false if file is not exist', ->
        expect(@selectionService.getFileById 5).toBeFalsy()


    describe 'getFiles', ->
      it 'should return array of two eleemnts', ->
        expect(@selectionService.getFiles().length).toBe 2


    describe 'getFileIds', ->
      it 'should return proper array', ->
        expect(@selectionService.getFilesIds()).toEqual [12, 1]

      it 'should return empty array if no file is added', ->
        @selectionService.clear();
        expect(@selectionService.getFilesIds()).toEqual []


    describe 'isSelectedFile', ->
      it 'should return true if file exist', ->
        expect(@selectionService.isSelectedFile 12).toBeTruthy()

      it 'should return false if file not exist', ->
        expect(@selectionService.isSelectedFile 21).toBeFalsy()


    describe 'removeFile', ->
      it 'should remove file from list', ->
        expect(@selectionService.getFiles().length).toBe 2
        @selectionService.removeFile 1

        expect(@selectionService.getFiles().length).toBe 1

      it 'should not remove file from list if the file not exists', ->
        expect(@selectionService.getFiles().length).toBe 2
        @selectionService.removeFile 123

        expect(@selectionService.getFiles().length).toBe 2

      it 'should return it self', ->
        expect(@selectionService.removeFile 123).toEqual @selectionService


    describe 'toggleFile', ->
      event = {}
      beforeEach ->
        event.preventDefault = jasmine.createSpy()

      it 'should prevent event default action', ->
        @selectionService.toggleFile event, fileOneMock

        expect(event.preventDefault).toHaveBeenCalled()

      it 'should not change files selection', ->
        expect(@selectionService.getFiles().length).toBe 2
        @selectionService.toggleFile event, fileOneMock

        expect(@selectionService.getFiles().length).toBe 2

      it 'should add file to selection', ->
        event.ctrlKey = true
        newFile =
          id: 17
          name: 'New file'

        expect(@selectionService.getFiles().length).toBe 2

        @selectionService.toggleFile event, newFile

        expect(@selectionService.getFiles().length).toBe 3

      it 'should remove file from selection', ->
        event.ctrlKey = true

        expect(@selectionService.getFiles().length).toBe 2

        @selectionService.toggleFile event, fileOneMock

        expect(@selectionService.getFiles().length).toBe 1


  describe 'folders functions', ->
    beforeEach ->
      @selectionService
        .clear()
        .addFolder folderOneMock
        .addFolder folderTwoMock

    describe 'addFolder', ->
      it 'should add folder', ->
        expect(@selectionService.getFolders().length).toBe 2

      it 'should return itself', ->
        expect(@selectionService.addFolder folderOneMock).toEqual @selectionService


    describe 'getFolders', ->
      it 'should return proper array', ->
        expect(@selectionService.getFolders()).toEqual [folderOneMock, folderTwoMock]


    describe 'getFoldersIds', ->
      it 'should return array [7,10]', ->
        expect(@selectionService.getFoldersIds()).toEqual [7,10]

      it 'should return empty array', ->
        @selectionService.clear()

        expect(@selectionService.getFoldersIds()).toEqual []


    describe 'isSelectedFolder', ->
      it 'should return true if folder with givven id is add to selecetion', ->
        expect(@selectionService.isSelectedFolder 10).toBeTruthy()

      it 'should return false if folder with givven id is not added to selecetion', ->
        expect(@selectionService.isSelectedFolder 21).toBeFalsy()


    describe 'removeFolder', ->
      it 'should reduce selected folders list', ->
        expect(@selectionService.getFolders().length).toBe 2
        @selectionService.deleteFolder 7

        expect(@selectionService.getFolders().length).toBe 1

      it 'should not reduce selected folders list if folder not found', ->
        expect(@selectionService.getFolders().length).toBe 2
        @selectionService.deleteFolder 17

        expect(@selectionService.getFolders().length).toBe 2

      it 'should return itself', ->
        expect(@selectionService.deleteFolder 7).toEqual @selectionService


    describe 'toggleFolder', ->
      event = {}

      beforeEach ->
        event.stopPropagation = jasmine.createSpy()

      it 'should prevent event propagation', ->
        @selectionService.toggleFolder event, folderOneMock

        expect(event.stopPropagation).toHaveBeenCalled()

      it 'should add folder to selection', ->
        event.ctrlKey = true
        newFolder =
          id: 31
          name: 'New folder name'

        expect(@selectionService.getFolders().length).toBe 2

        @selectionService.toggleFolder event, newFolder

        expect(@selectionService.getFolders().length).toBe 3

      it 'should reduce folders list', ->
        event.ctrlKey = true

        expect(@selectionService.getFolders().length).toBe 2

        @selectionService.toggleFolder event, folderOneMock

        expect(@selectionService.getFolders().length).toBe 1


    describe 'isEmptySelection', ->

      beforeEach ->
        @selectionService.clear()

      it 'should return true', ->
        expect(@selectionService.isEmptySelection()).toBeTruthy()

      it 'should return false if is any file', ->
        @selectionService.addFile fileOneMock

        expect(@selectionService.isEmptySelection()).toBeFalsy()

      it 'should return false if is any folder', ->
        @selectionService.addFolder folderOneMock

        expect(@selectionService.isEmptySelection()).toBeFalsy()

