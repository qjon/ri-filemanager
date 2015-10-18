describe 'PreviewService', ->
  dirStructureMock = {}
  fileMock = {}
  nextFile = {}
  prevFile = {}

  beforeEach ->
    @previewService = new Preview

    nextFile = {
      id: 8
    }

    prevFile = {
      id: 5
    }

    dirStructureMock = {
      getNextFile: jasmine.createSpy().and.returnValue nextFile
      getPrevFile: jasmine.createSpy().and.returnValue prevFile
    }

    fileMock = {
      id: 7
      name: 'Abc.pdf'
      getDirStructure: jasmine.createSpy().and.returnValue dirStructureMock
    }

  describe 'constructor', ->
    it 'file should be equal false', ->
      expect(@previewService.file).toBeFalsy()

  describe 'open', ->
    it 'should set file', ->
      @previewService.open fileMock
      expect(@previewService.file).toEqual fileMock;

  describe 'close', ->
    it 'should unset file', ->
      @previewService.open fileMock
      expect(@previewService.file).toEqual fileMock;


      @previewService.close()
      expect(@previewService.file).toBeFalsy();

  describe 'isOpen', ->
    it 'should return false if file is not set', ->
      expect(@previewService.isOpen()).toBeFalsy()


    it 'should return true if file is set', ->
      @previewService.open fileMock
      expect(@previewService.isOpen()).toBeTruthy()


  describe 'nextFile', ->
    it 'should set file equal nextFile', ->
      @previewService.open fileMock
      @previewService.nextFile()

      expect(fileMock.getDirStructure).toHaveBeenCalled()
      expect(dirStructureMock.getNextFile).toHaveBeenCalledWith fileMock
      expect(@previewService.file).toEqual nextFile


  describe 'prevFile', ->
    it 'should set file equal prevFile', ->
      @previewService.open fileMock
      @previewService.prevFile()

      expect(fileMock.getDirStructure).toHaveBeenCalled()
      expect(dirStructureMock.getPrevFile).toHaveBeenCalledWith fileMock
      expect(@previewService.file).toEqual prevFile