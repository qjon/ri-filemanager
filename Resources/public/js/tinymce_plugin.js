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
        file: '/app_dev.php/filemanager/page',
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
        oninsert: function(file) {
            var inputs = win.document.getElementsByTagName('input');
            if (type === 'image') {
                inputs[0].setAttribute('value', file.src);
                inputs[1].setAttribute('value', file.name);
                inputs[2].setAttribute('value', file.width);
                inputs[3].setAttribute('value', file.height);
            } else {
                inputs[0].setAttribute('value', file.src);
                if (!inputs[1].getAttribute('value')) {
                    inputs[1].setAttribute('value', file.name);
                }
            }
        }
    });
    return false;
}