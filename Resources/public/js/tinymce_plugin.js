/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
function myFileBrowser(field_name, url, type, win) {
    tinyMCE.activeEditor.windowManager.open({
        file: Routing.generate('ri_filemanager_page'),
        title: 'RI FileManager',
        width: 1100,
        height: 450,
        resizable: "yes",
        plugins: "media",
        inline: "yes",
        close_previous: "no"
    }, {
        window: win,
        input: field_name,
        type: type,
        url: url,
        oninsert: function(file) {
            var inputs = win.document.getElementsByTagName('input');
            if (type === 'image') {
                inputs[0].value = file.src;
                inputs[1].value = file.name;
                inputs[2].value = file.width;
                inputs[3].value = file.height;
            } else {
                inputs[0].value = file.src;
                if (!inputs[1].value) {
                    inputs[1].value = file.name;
                }
            }
        }
    });
    return false;
}