###
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
###
class FileIcons extends Service
  constructor: (configProvider) ->
    @configProvider = configProvider;
    @imagesExtensions = ['aac', 'ai', 'aiff', 'avi', '_blank', 'bmp', 'c', 'cpp', 'css', 'dat', 'dmg', 'doc', 'dotx', 'dwg', 'dxf', 'eps', 'exe', 'flv', 'gif', 'h', 'hpp', 'html', 'ics', 'iso', 'java', 'jpg', 'js', 'key', 'less', 'mid', 'mp3', 'mp4', 'mpg', 'odf', 'ods', 'odt', 'otp', 'ots', 'ott', '_page', 'pdf', 'php', 'png', 'ppt', 'psd', 'py', 'qt', 'rar', 'rb', 'rtf', 'sass', 'scss', 'sql', 'tga', 'tgz', 'tiff', 'txt', 'wav', 'xls', 'xlsx', 'xml', 'yml', 'zip']

  getIconPath: (fileName) ->
    ext = fileName.substr(_.lastIndexOf(fileName, '.') + 1)
    if @imagesExtensions.indexOf(ext) > -1
      return @configProvider.fileIconTypesDir + ext + '.png'
    else
      return @configProvider.fileIconTypesDir + @configProvider.blankIconType
