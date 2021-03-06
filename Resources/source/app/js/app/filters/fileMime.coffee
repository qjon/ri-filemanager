###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class fileMime extends Filter
  constructor: ->
    return (filesList, fileMimeTypesList) ->
      files = []
      if typeof fileMimeTypesList == 'undefined' or fileMimeTypesList.length == 0
        return filesList

      filesList.forEach (file) ->
        if fileMimeTypesList.indexOf(file.mime) > -1
          files.push(file);

      return files;