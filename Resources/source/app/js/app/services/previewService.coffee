###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class Preview extends Service
  constructor: () ->
    @file = false

  open: (file) ->
    @file = file
    @

  close: () ->
    @file = false;
    @

  isOpen: () ->
    return @file != false

  nextFile: () ->
    @open(@file.getDirStructure().getNextFile(@file))

  prevFile: () ->
    @open(@file.getDirStructure().getPrevFile(@file))






