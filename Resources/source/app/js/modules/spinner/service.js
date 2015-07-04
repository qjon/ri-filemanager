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
    .service('SpinnerService', function() {
        var isActive = false;

        return {
            show: function () {
                isActive = true;
            },
            hide: function () {
                isActive = false;
            },
            isShow: function () {
                return isActive;
            }
        }
    });