###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Main extends Controller
  constructor: ($scope, dirStructureService, fileTypesService, fileTypeFilterService, routingChangeService, selectionService, copyPasteService, uploadService, callbackService, $translate) ->
    @$scope = $scope
    @dirStructure = dirStructureService
    @fileTypeFilter = fileTypeFilterService
    @fileTypes = fileTypesService
    @routingChangeService = routingChangeService
    @$translate = $translate
    @selection = selectionService
    @copyPaste = copyPasteService
    @fileUploadService = uploadService
    @callbackService = callbackService
    @search = ''

  setLanguage: (lang) ->
    @$translate.use lang

  getLanguageSymbol: ->
    lang = @$translate.use()

    return 'PL' if lang == 'pl_PL'
    return 'EN'
