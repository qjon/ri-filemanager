describe('SpinnerService:', function () {
    var spinnerService;

    beforeEach(module('riSpinner'));

    beforeEach(inject(function (_SpinnerService_) {
        spinnerService = _SpinnerService_;
    }));

    describe('isShow', function () {
        it('should return default value false', function () {
            expect(spinnerService.isShow()).toBeFalsy();
        });
    });

    describe('show', function () {
        it('should change isActive value to true', function () {
            expect(spinnerService.isShow()).toBeFalsy();

            spinnerService.show();

            expect(spinnerService.isShow()).toBeTruthy();
        });
    });

    describe('hide', function () {
        it('should change isActive value to false', function () {
            expect(spinnerService.isShow()).toBeFalsy();

            spinnerService.show();
            expect(spinnerService.isShow()).toBeTruthy();

            spinnerService.hide();
            expect(spinnerService.isShow()).toBeFalsy();
        });
    });
});