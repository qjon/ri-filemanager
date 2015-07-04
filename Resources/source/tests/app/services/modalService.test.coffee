describe 'modalService', ->
  modalService = null
  rootScopeMock = null
  scope = null
  modalMock = null
  eventMock = null

  beforeEach ->
    eventMock =
      stopPropagation: jasmine.createSpy()
    scope = {}
    modalMock = jasmine.createSpy()
    rootScopeMock =
      $new: jasmine.createSpy().and.returnValue(scope)

    module 'filemanager'

    modalService = new Modal rootScopeMock, modalMock

  describe 'open', ->
    it 'should create modal', ->
      template = '/path_to_template'
      scopeData =
        some: 'data'

      modalService.open eventMock, template, scopeData

      expect(eventMock.stopPropagation).toHaveBeenCalled()
      expect(rootScopeMock.$new).toHaveBeenCalledWith true
      expect(modalMock).toHaveBeenCalledWith(
        template: template
        placement: 'center'
        container: '#toppage'
        backdrop: false
        keyboard: false
        show: true
        scope: scope
      )

    it 'should create modal if empty data', ->
      template = '/path_to_template'
      scopeData = undefined

      modalService.open eventMock, template, scopeData

      expect(eventMock.stopPropagation).toHaveBeenCalled()
      expect(rootScopeMock.$new).toHaveBeenCalledWith true
      expect(modalMock).toHaveBeenCalledWith(
        template: template
        placement: 'center'
        container: '#toppage'
        backdrop: false
        keyboard: false
        show: true
        scope: scope
      )
