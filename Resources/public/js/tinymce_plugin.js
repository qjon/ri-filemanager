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
        file: 'index.html',
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
        type: type
    });
    return false;
}