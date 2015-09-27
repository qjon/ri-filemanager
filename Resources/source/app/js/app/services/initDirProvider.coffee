###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class InitDir extends Provider
  ###
  Function check if TinyMCE is active and has set some URL
  ###
  getFilePath: () ->
    if top and top.tinymce and top.tinymce.activeEditor
      params = top.tinymce.activeEditor.windowManager.getParams()
      if params
        return params.url
      else
        return false
    else
      return false

  $get: =>
    getFilePath: @getFilePath