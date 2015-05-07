/**
 * Created by liuhui on 15-5-7.
 */
define(function (require, exports) {
    var ck = require('ckeditor');

    CKEDITOR.basePath = '/statics/scripts/libs/ckeditor/';
    CKEDITOR.replace('wysiwyg');
});