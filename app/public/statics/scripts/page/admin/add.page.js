/**
 * Created by huiliu on 14-9-18.
 * @Des:
 */
define(function(require){
    var $ = require('$'), core = require('core'), kind = require('/scripts/page/admin/initKind.page.js');

    main();

    function main()
    {
        kind.initKindEditor();
        kind.initSubmitEvent();

        //initBindEvent();
        $('#image1').bind('change', function(){
            kind.initPreview(this, 'showImage', 'showImagePreview', 'showImageTip');
        });
        $('#image2').bind('change', function(){
            kind.initPreview(this, 'showImage2', 'showImagePreview2', 'showImageTip2');
        });
    };
});