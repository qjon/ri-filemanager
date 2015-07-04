###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class FileTypes extends Service
  constructor: (configProvider) ->
    @types = configProvider.mimeTypes

  ###*
   * Return object of file types
   * @returns {{images: string[], audio: string[], video: string[], archive: string[]}}
  ###
  getTypes: ->
    @types

  ###*
   * Return mime list names for file type
   *
   * @param {String} name
   *
   * @returns Array
   ###
  getType: (name) ->
    @types[name] || []

  ###*
   *
   * @param {String} name
   * @param {String} mime
   * @returns {boolean}
   ###
  hasTypeGetMime: (name, mime) ->
      mimesList = @getType name

      mimesList.indexOf(mime) > -1;

  ###*
   * @param  {String} name
   * @returns {boolean}
   ###
  isDefinedType: (name) ->
    typeof @types[name] != 'undefined'
