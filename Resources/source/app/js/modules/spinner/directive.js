/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
'use strict';
angular.module('riSpinner')
    .directive('spinner', ['SpinnerService', function(SpinnerService){
        return {
            restrict: 'A',
            replace: true,
            templateUrl: '/templates/modules/spinner.html',
            link: function (scope) {
                scope.spinnerService = SpinnerService
            }
        }
    }]);