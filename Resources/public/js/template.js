/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
angular.module('templates', []).run(['$templateCache', function($templateCache) {
  'use strict';

  $templateCache.put('/templates/dir_add.html',
    "\n" +
    "<div data-ng-controller=\"addDirController as addDirCtrl\" class=\"modal\">\n" +
    "  <div class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"$hide()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h4>{{'CREATE_DIR' | translate}}</h4>\n" +
    "      </div>\n" +
    "      <div class=\"modal-body\">\n" +
    "        <div ng-show=\"folder_add.$invalid &amp;&amp; folder_add.$dirty\" class=\"alert alert-danger\">\n" +
    "          <p>{{'VALID_DIR_NAME_CAN_NOT_BE_EMPTY' | translate}}</p>\n" +
    "        </div>\n" +
    "        <form name=\"folder_add\" novalidate=\"novalidate\" style=\"margin: 10px;\" class=\"form form-horizontal\">\n" +
    "          <div class=\"form-group\">\n" +
    "            <input type=\"text\" name=\"folder_name\" ng-model=\"addDirCtrl.folderName\" placeholder=\"{{'DIR_NAME' | translate}}\" required=\"required\" class=\"form-control\"/>\n" +
    "          </div>\n" +
    "        </form>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <button ng-disabled=\"folder_add.$invalid\" ng-click=\"addDirCtrl.addFolder()\" class=\"btn btn-success\"><i class=\"fa fa-check\">{{'CREATE' | translate}}</i></button>\n" +
    "        <button ng-click=\"$hide()\" class=\"btn btn-danger\"><i class=\"fa fa-times\">{{'CANCEL' | translate}}</i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/dir_edit.html',
    "\n" +
    "<div data-ng-controller=\"editDirController as de\" class=\"modal\">\n" +
    "  <div class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"$hide()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h4>{{'DIR_NAME_CHANGE' | translate}} \"{{ de.orgName }}\"</h4>\n" +
    "      </div>\n" +
    "      <div class=\"modal-body\">\n" +
    "        <div ng-show=\"folder_add.$invalid &amp;&amp; folder_add.$dirty\" class=\"alert alert-danger\">\n" +
    "          <p>{{'VALID_DIR_NAME_CAN_NOT_BE_EMPTY' | translate}}</p>\n" +
    "        </div>\n" +
    "        <form name=\"folder_add\" novalidate=\"novalidate\" style=\"margin: 10px;\" class=\"form form-horizontal\">\n" +
    "          <div class=\"form-group\">\n" +
    "            <input type=\"text\" name=\"folder_name\" ng-model=\"de.folderName\" placeholder=\"{{'DIR_NAME' | translate}}\" required=\"required\" class=\"form-control\"/>\n" +
    "          </div>\n" +
    "        </form>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <button ng-disabled=\"folder_add.$invalid || de.folderName == de.orgName\" ng-click=\"de.dir.save(de.folderName, $hide, $hide)\" class=\"btn btn-success\"><i class=\"fa fa-check\">{{'SAVE' | translate}}</i></button>\n" +
    "        <button ng-click=\"$hide()\" class=\"btn btn-danger\"><i class=\"fa fa-times\">{{'CANCEL' | translate}}</i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/dir_remove.html',
    "\n" +
    "<div data-ng-controller=\"removeDirController as rd\" class=\"modal\">\n" +
    "  <div class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"$hide()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h4>{{'DIR_REMOVE' | translate}}</h4>\n" +
    "      </div>\n" +
    "      <div class=\"modal-body\">\n" +
    "        <div ng-if=\"rd.errorString\" class=\"alert alert-danger\">\n" +
    "          <p>DIR_HAS_NOT_BEEN_REMOVED. {{ rd.errorString }}</p>\n" +
    "        </div>\n" +
    "        <div ng-if=\"!rd.errorString\" class=\"alert alert-warning\">\n" +
    "          <p translate=\"{{'DIR_REMOVE_QUESTION'}}\" translate-values=\"{folderName: rd.removedDir.name}\"></p>\n" +
    "        </div>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <button ng-if=\"!rd.errorString\" ng-click=\"rd.removedDir.remove($hide, rd.showAlert)\" class=\"btn btn-success\"><i class=\"fa fa-check\">{{'YES' | translate}}, {{'DELETE' | translate}}</i></button>\n" +
    "        <button ng-click=\"$hide()\" class=\"btn btn-danger\"><i class=\"fa fa-times\">{{'CANCEL' | translate}}</i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/file_edit.html',
    "\n" +
    "<div data-ng-controller=\"editFileController as efCtrl\" class=\"modal image-crop-window\">\n" +
    "  <div class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"$hide()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h4 class=\"modal-title\">{{ 'IMAGE_EDIT'| translate }}</h4>\n" +
    "      </div>\n" +
    "      <div class=\"modal-body\">\n" +
    "        <div style=\"max-height: 400px; overflow: hidden; scroll: auto;\"><img ng-src=\"{{ file.src }}\" crop=\"\" file=\"file\"/></div>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <div class=\"btn-group pull-left\">\n" +
    "          <button ng-repeat=\"s in efCtrl.sizeList\" ng-class=\"{'active': efCtrl.isSize(s)}\" ng-click=\"efCtrl.setSize(s)\" class=\"btn btn-primary\">{{ s.name }}</button>\n" +
    "        </div>\n" +
    "        <button ng-click=\"file.crop(); $hide()\" class=\"btn btn-primary\">{{ 'SAVE'| translate }}</button>\n" +
    "        <button ng-click=\"$hide()\" class=\"btn btn-danger\">{{ 'CANCEL'| translate }}</button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/file_remove.html',
    "\n" +
    "<div data-ng-controller=\"removeFileController as rf\" class=\"modal\">\n" +
    "  <div class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"$hide()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h4>{{ 'FILE_REMOVE'| translate }}</h4>\n" +
    "      </div>\n" +
    "      <div class=\"modal-body\">\n" +
    "        <div ng-if=\"rf.errorString\" class=\"alert alert-danger\">\n" +
    "          <p>{{ rf.errorString }}</p>\n" +
    "        </div>\n" +
    "        <div ng-if=\"!rf.errorString\" class=\"alert alert-warning\">\n" +
    "          <p translate=\"FILE_REMOVE_QUESTION\" translate-values=\"{fileName: rf.file.name}\"></p>\n" +
    "        </div>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <button ng-if=\"!rf.errorString\" ng-click=\"rf.file.remove($hide, rf.showAlert)\" class=\"btn btn-success\"><i class=\"fa fa-check\">{{ 'YES'| translate }}, {{ 'DELETE'| translate }}</i></button>\n" +
    "        <button ng-click=\"$hide()\" class=\"btn btn-danger\"><i class=\"fa fa-times\">{{ 'CANCEL'| translate }}</i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/files_upload.html',
    "\n" +
    "<div data-ng-controller=\"fileUploadController as fu\" class=\"modal upload-modal\">\n" +
    "  <div style=\"max-width: 858px;\" class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"fu.fileUploadService.hideAndClear()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h3 class=\"modal-title\">{{'FILE_UPLOAD' | translate}}</h3>\n" +
    "      </div>\n" +
    "      <div style=\"max-height: 400px; overflow: auto\" class=\"modal-body\">\n" +
    "        <div ng-repeat=\"file in fu.fileUploadService.getFlow().files | orderBy:'name'\" class=\"thumb thumb-file img-thumbnail\"><img ng-if=\"fu.fileUploadService.isImage(file)\" flow-img=\"file\" class=\"thumb-image\"/><img ng-if=\"!fu.fileUploadService.isImage(file)\" ng-src=\"{{ fu.fileUploadService.getThumbnail(file) }}\" class=\"thumb-image thumb-icon\"/>\n" +
    "          <div data-ng-bind=\"file.name\" class=\"thumb-name\"></div>\n" +
    "          <div role=\"progressbar\" style=\"width: {{file.percent}}%; height: 20px;\" class=\"progress-bar progress-bar-primary\">\n" +
    "            <div class=\"sr-only\">{{file.percent}}%</div>\n" +
    "          </div>\n" +
    "        </div>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <button ng-click=\"fu.fileUploadService.uploadFiles()\" class=\"btn btn-success\"><i class=\"fa fa-upload fa-button-icon\">{{'UPLOAD_FILES' | translate}}</i></button>\n" +
    "        <button ng-click=\"fu.fileUploadService.hideAndClear()\" class=\"btn btn-danger\"><i class=\"fa fa-times\">{{'CANCEL' | translate}}</i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/main.html',
    "\n" +
    "<div id=\"toppage\" flow-init=\"{query: {dirId: mainCtrl.dirStructure.currentDir.id}}\" flow-file-progress=\"mainCtrl.fileUploadService.uploadProgress($flow, $file)\" flow-file-success=\"mainCtrl.fileUploadService.fileUploadComplete($flow, $file, $message)\" flow-files-submitted=\"mainCtrl.fileUploadService.openUploadFileDialog($event, $flow)\" flow-complete=\"mainCtrl.fileUploadService.hideAndClear()\">\n" +
    "  <div spinner=\"\" class=\"spinner\"></div>\n" +
    "  <div class=\"row nav-row\">\n" +
    "    <div class=\"col-sm-12 col-md-4 text-left\">\n" +
    "      <div class=\"btn-group\">\n" +
    "        <button data-template=\"/templates/dir_add.html\" data-placement=\"center\" bs-modal=\"modal\" container=\"body\" backdrop=\"false\" title=\"{{ 'CREATE_DIR' | translate }}\" class=\"btn btn-default\"><i class=\"fa fa-plus\"></i><i class=\"fa fa-folder-o\"></i></button>\n" +
    "        <button type=\"file\" flow-btn=\"flow-btn\" title=\"{{ 'UPLOAD_FILES' | translate }}\" class=\"btn btn-default\"><i class=\"fa fa-plus\"></i><i class=\"fa fa-files-o\"></i></button>\n" +
    "      </div>\n" +
    "      <div ng-if=\"!mainCtrl.selection.isEmptySelection()\" dropdown=\"\" class=\"btn-group\">\n" +
    "        <button type=\"button\" ng-class=\"{active: mainCtrl.copyPaste.isCutSelected()}\" ng-click=\"mainCtrl.copyPaste.setCut()\" class=\"btn btn-default\"><i class=\"fa fa-cut\"></i></button>\n" +
    "        <button type=\"button\" ng-class=\"{active: mainCtrl.copyPaste.isCopySelected()}\" ng-click=\"mainCtrl.copyPaste.setCopy()\" class=\"btn btn-default\"><i class=\"fa fa-copy\"></i></button>\n" +
    "        <button type=\"button\" ng-disabled=\"mainCtrl.copyPaste.isNotSelected()\" ng-click=\"mainCtrl.copyPaste.doAction(mainCtrl.dirStructure.currentDir.id)\" class=\"btn btn-default\"><i class=\"fa fa-paste\"></i></button>\n" +
    "        <button ng-show=\"mainCtrl.callbackService.isFileCallback()\" ng-click=\"mainCtrl.callbackService.fileCallback($event, mainCtrl.selection.getFiles())\" title=\"{{'USE_SELECTED_FILES' | translate}}\" class=\"btn btn-primary\"><i class=\"fa fa-image\"></i></button>\n" +
    "        <button ng-click=\"mainCtrl.copyPaste.openRemoveDialog($event)\" class=\"btn btn-danger\"><i class=\"fa fa-trash-o\"></i></button>\n" +
    "        <button ng-click=\"mainCtrl.selection.clear()\" class=\"btn btn-warning\"><i class=\"fa fa-eraser\"></i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "    <div class=\"col-sm-12 col-md-7\">\n" +
    "      <div class=\"btn-group\">\n" +
    "        <button ng-click=\"mainCtrl.fileTypeFilter.setFilterName(false)\" ng-class=\"{'active': mainCtrl.fileTypeFilter.isActiveFilter(false)}\" title=\"{{'ALL_FILE_TYPES'| translate}}\" class=\"btn btn-default\"><i class=\"fa fa-file-o\"></i></button>\n" +
    "        <button ng-click=\"mainCtrl.fileTypeFilter.setFilterName('images')\" ng-class=\"{'active': mainCtrl.fileTypeFilter.isActiveFilter('images')}\" title=\"{{'TYPE_IMAGE'| translate}}\" class=\"btn btn-default\"><i class=\"fa fa-picture-o\"></i></button>\n" +
    "        <button ng-click=\"mainCtrl.fileTypeFilter.setFilterName('audio')\" ng-class=\"{'active': mainCtrl.fileTypeFilter.isActiveFilter('audio')}\" title=\"{{'TYPE_AUDIO'| translate}}\" class=\"btn btn-default\"><i class=\"fa fa-music\"></i></button>\n" +
    "        <button ng-click=\"mainCtrl.fileTypeFilter.setFilterName('video')\" ng-class=\"{'active': mainCtrl.fileTypeFilter.isActiveFilter('video')}\" title=\"{{'TYPE_WIDEO'| translate}}\" class=\"btn btn-default\"><i class=\"fa fa-video-camera\"></i></button>\n" +
    "        <button ng-click=\"mainCtrl.fileTypeFilter.setFilterName('archive')\" ng-class=\"{'active': mainCtrl.fileTypeFilter.isActiveFilter('archive')}\" title=\"{{'TYPE_ZIP'| translate}}\" class=\"btn btn-default\"><i class=\"fa fa-archive\"></i></button>\n" +
    "        <div class=\"input-group\">\n" +
    "          <input ng-model=\"mainCtrl.search\" type=\"text\" placeholder=\"{{'FILTER' | translate}}\" class=\"form-control\"/><span class=\"btn btn-default input-group-addon\"><i class=\"fa fa-search\"></i></span>\n" +
    "        </div>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "    <div class=\"col-sm-12 col-md-1\">\n" +
    "      <div class=\"btn-group pull-right\">\n" +
    "        <button type=\"button\" data-toggle=\"dropdown\" class=\"btn btn-default dropdown-toggle\">{{mainCtrl.getLanguageSymbol()}}\n" +
    "          <div class=\"caret\"></div>\n" +
    "        </button>\n" +
    "        <ul class=\"dropdown-menu\">\n" +
    "          <li><a ng-click=\"mainCtrl.setLanguage('pl_PL')\">{{'LANG_PL' | translate}} ({{'LANG_PL_SYMBOL' | translate}})</a></li>\n" +
    "          <li><a ng-click=\"mainCtrl.setLanguage('en_EN')\">{{'LANG_EN' | translate}} ({{'LANG_EN_SYMBOL' | translate}})</a></li>\n" +
    "        </ul>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div><div class=\"sub-view\" ui-view></div>\n" +
    "  <div class=\"panel panel-default main-panel\">\n" +
    "    <div class=\"panel-body\">\n" +
    "      <ul class=\"breadcrumb\">\n" +
    "        <li ng-if=\"mainCtrl.dirStructure.currentDir.id &gt; 0\" ng-click=\"mainCtrl.routingChangeService.goToFolder($event, 0)\" class=\"link\">{{'HOME' | translate }}</li>\n" +
    "        <li ng-repeat=\"parent in mainCtrl.dirStructure.currentDir.parentsList\" ng-click=\"mainCtrl.routingChangeService.goToFolder($event, parent.id)\" class=\"link\">{{ parent.name }}</li>\n" +
    "        <li class=\"current\">{{ mainCtrl.dirStructure.currentDir.name }}</li>\n" +
    "      </ul>\n" +
    "      <div ng-repeat=\"dir in mainCtrl.dirStructure.currentDir.dirs | filter:{'name': mainCtrl.search } | orderBy:'name'\" ng-click=\"$event.stopPropagation();mainCtrl.routingChangeService.goToFolder($event, dir.id);mainCtrl.selection.toggleFolder($event, dir)\" ng-class=\"{selected: mainCtrl.selection.isSelectedFolder(dir.id)}\" class=\"thumb thumb-folder img-thumbnail\"><i class=\"thumb-image fa fa-folder-o\"></i>\n" +
    "        <div data-ng-bind=\"dir.name\" class=\"thumb-name\"></div>\n" +
    "        <div class=\"menu folder-menu\"><i ng-click=\"dir.openDialogEditFolder($event, dir)\" title=\"{{'EDIT' | translate}}\" class=\"fa fa-edit\"></i><i ng-click=\"dir.openDialogRemoveFolder($event)\" title=\"{{'DELETE' | translate}}\" class=\"fa fa-trash-o\"></i></div><i ng-show=\"mainCtrl.selection.isSelectedFolder(dir.id)\" class=\"fa fa-check selection-mark\"></i>\n" +
    "      </div>\n" +
    "      <div ng-repeat=\"file in mainCtrl.dirStructure.currentDir.files | fileMime:mainCtrl.fileTypeFilter.getCurrentFilterMimeList() | filter:{'name': mainCtrl.search } | orderBy:'name'\" ng-click=\"mainCtrl.selection.toggleFile($event, file)\" ng-class=\"{selected: mainCtrl.selection.isSelectedFile(file.id)}\" class=\"thumb thumb-file img-thumbnail\">\n" +
    "        <div ng-if=\"file.isImage()\" class=\"thumb-image\"><img ng-src=\"{{ file.src }}\"/></div><img ng-if=\"!file.isImage()\" ng-src=\"{{ file.icon }}\" class=\"thumb-image thumb-icon\"/>\n" +
    "        <div data-ng-bind=\"file.name\" class=\"thumb-name\"></div>\n" +
    "        <div class=\"menu file-menu\"><i ng-click=\"mainCtrl.routingChangeService.downloadFile(file, $event)\" class=\"fa fa-download\"></i><i ng-show=\"file.isImage()\" ng-click=\"file.openEditDialog($event)\" class=\"fa fa-edit\"></i><i ng-show=\"file.isImage() &amp;&amp; mainCtrl.callbackService.isFileCallback() &amp;&amp; mainCtrl.selection.isEmptySelection()\" ng-click=\"mainCtrl.callbackService.fileCallback($event, file)\" class=\"fa fa-image\"></i><i ng-show=\"!file.isImage() &amp;&amp; mainCtrl.callbackService.isFileCallback() &amp;&amp; mainCtrl.selection.isEmptySelection()\" ng-click=\"mainCtrl.callbackService.fileCallback($event, file)\" class=\"fa fa-link\"></i><i ng-click=\"file.openRemoveDialog($event)\" class=\"fa fa-trash-o\"></i></div><i ng-show=\"mainCtrl.selection.isSelectedFile(file.id)\" class=\"fa fa-check selection-mark\"></i>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/selection_remove.html',
    "\n" +
    "<div data-ng-controller=\"selectionRemoveController as sr\" class=\"modal\">\n" +
    "  <div class=\"modal-dialog\">\n" +
    "    <div class=\"modal-content\">\n" +
    "      <div class=\"modal-header\">\n" +
    "        <button type=\"button\" ng-click=\"$hide()\" class=\"btn close\"><i class=\"fa fa-times\"></i></button>\n" +
    "        <h4>{{'SELECTION_REMOVE' | translate}}</h4>\n" +
    "      </div>\n" +
    "      <div class=\"modal-body\">\n" +
    "        <div ng-if=\"!sr.errorString\" class=\"alert alert-warning\">\n" +
    "          <p>{{'SELECTION_REMOVE_QUESTION_PART_1' | translate}}\n" +
    "            <ul>\n" +
    "              <li ng-repeat=\"dir in sr.dirs\"><strong>{{ dir.name }}</strong></li>\n" +
    "              <li ng-repeat=\"file in sr.files\"><strong>{{ file.name }}</strong></li>\n" +
    "            </ul>\n" +
    "          </p> {{'SELECTION_REMOVE_QUESTION_PART_2' | translate}}\n" +
    "        </div>\n" +
    "      </div>\n" +
    "      <div class=\"modal-footer\">\n" +
    "        <button ng-if=\"!rd.errorString\" ng-click=\"sr.copyPasteService.remove($hide)\" class=\"btn btn-success\"><i class=\"fa fa-check\">{{'YES' | translate}}, {{'DELETE' | translate}}</i></button>\n" +
    "        <button ng-click=\"$hide()\" class=\"btn btn-danger\"><i class=\"fa fa-times\">{{'CANCEL' | translate}}</i></button>\n" +
    "      </div>\n" +
    "    </div>\n" +
    "  </div>\n" +
    "</div>"
  );


  $templateCache.put('/templates/modules/spinner.html',
    "\n" +
    "<div ng-show=\"spinnerService.isShow()\" class=\"spinner\"><i class=\"fa fa-spinner\"></i></div>"
  );

}]);
