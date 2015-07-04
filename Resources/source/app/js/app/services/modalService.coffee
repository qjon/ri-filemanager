###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Modal extends Service
  constructor: ($rootScope, $modal) ->
    @$rootScope = $rootScope
    @$modal = $modal

  open: (event, template, scopeData) ->
    scope = @$rootScope.$new true

    scopeData = scopeData || {};

    event.stopPropagation();
    angular.extend(scope, scopeData);

    return @$modal(
      template: template,
      placement: 'center'
      container: '#toppage'
      backdrop: false
      keyboard: false
      show: true
      scope: scope
    )
