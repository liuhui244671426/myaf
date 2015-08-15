/**
 * Created by liuhui on 15-5-12.
 */
define(function(require, exports){
    var jQuery = require('jquery');
    require('jqueryDatatables');

    initDataTable();
    function initDataTable(){
        jQuery('#table1').dataTable();
        jQuery('#table2').dataTable({
            "sPaginationType": "full_numbers"
        });

        //// Select2
        //jQuery('select').select2({
        //    minimumResultsForSearch: -1
        //});

        jQuery('select').removeClass('form-control');

        // Delete row in a table
        jQuery('.delete-row').click(function(){
            var c = confirm("Continue delete?");
            if(c)
                jQuery(this).closest('tr').fadeOut(function(){
                    jQuery(this).remove();
                });

            return false;
        });

        // Show aciton upon row hover
        jQuery('.table-hidaction tbody tr').hover(function(){
            jQuery(this).find('.table-action-hide a').animate({opacity: 1});
        },function(){
            jQuery(this).find('.table-action-hide a').animate({opacity: 0});
        });
    }
});