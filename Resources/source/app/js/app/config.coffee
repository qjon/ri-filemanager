###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Configuration extends Config
  constructor: ($provide, flowFactoryProvider, $routeProvider, $translateProvider, initDirProviderProvider) ->

    $translateProvider.useStaticFilesLoader(
      prefix: '/bundles/rifilemanager/translations/lang_'
      suffix: '.json'
    )

    $translateProvider.useSanitizeValueStrategy null

    $routeProvider
      .when(
        '/dir/:dirId'
        {
          templateUrl: '/templates/main.html'
          controller: 'mainController as mainCtrl'
          resolve:
            dir: ['dirStructureService', '$route', (dirStructureService, $route) ->
              dirStructureService.load $route.current.params.dirId
            ]
        }
      )
      .otherwise(
        redirectTo: ->
          if initDirProviderProvider.getFilePath()
            return
          else
            return '/dir/0'
      )

    flowFactoryProvider.defaults =
      target: Routing.generate 'ri_filemanager_api_file_upload'
      uploadMethod: 'POST'
      permanentErrors: [404, 500, 501]
      testChunks: false
      progressCallbacksInterval: 0
      speedSmoothingFactor: 1
      chunkSize: 64 * 1024 * 1024
      forceChunkSize: false

    $provide.decorator('flowImgDirective', ($delegate) ->
      directive = $delegate[0]
      directive.require = ''

      $delegate
    )

    return