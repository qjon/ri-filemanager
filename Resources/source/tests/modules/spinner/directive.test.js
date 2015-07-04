describe('SpinnerDirective:', function () {
    var spinnerServiceMock = jasmine.createSpy(), scope;

    beforeEach(module('riSpinner'));

    beforeEach(module(function($provide){
        $provide.value('SpinnerService', spinnerServiceMock);
    }));

    beforeEach(inject(function ($httpBackend, _$compile_, _$rootScope_) {
        scope = _$rootScope_.$new();

        $httpBackend.expectGET('/templates/modules/spinner.html').respond("<div ng-show=\"spinnerService.isShow()\" class=\"spinner\"><i class=\"fa fa-spinner\"></i></div>");
        _$compile_('<div spinner></div>')(scope);
        $httpBackend.flush();
        scope.$digest();
    }));

    describe('link function', function () {
        it('should set scope.spinnerService', function () {
            expect(scope.spinnerService).toEqual(spinnerServiceMock);
        });
    });
});