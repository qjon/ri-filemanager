###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class FileTypeFilter extends Service

  constructor: (fileTypesService) ->
    @fileTypeFilter = false;
    @fileTypeFilterServiceMock = fileTypesService

  clearFilter: ->
    @fileTypeFilter = false

  getFilterName: ->
    @fileTypeFilter

  ###
   * Return current selected file type mime list
   *
   * @returns {Array}
  ###
  getCurrentFilterMimeList: ->
    @fileTypeFilterServiceMock.getType @fileTypeFilter

  ###
   * Check if "name" filter is selected
   *
   * @param {String} name
   * @returns {boolean}
  ###
  isActiveFilter: (name) ->
    name == @fileTypeFilter;

  ###
   * Set filter name
   *
   * @param {String} filterName
  ###
  setFilterName: (filterName) ->
    if filterName and filterName != false and @fileTypeFilterServiceMock.isDefinedType filterName
      @fileTypeFilter = filterName;
    else
      @.clearFilter()

    @