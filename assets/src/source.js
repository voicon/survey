function nodeRender($tdList, i, data) {
    $tdList.eq(i).find('input').val(data.opts['opt_' + (i-1)]);
}
$(function(){
    $("#btnGridExpandAll").click(function(){
        $.ui.fancytree.getTree(1).visit(function(node){
            node.setExpanded();
        });
    });
    $("#btnGridCollapseAll").click(function(){
        $.ui.fancytree.getTree(1).visit(function(node){
            node.setExpanded(false);
        });
    });

    $("#btnGridSave").click(function(){
        jQuery.ajax({
            type: 'POST',
            url: urls.main,
            data: {a: 'save', f: jQuery('#name').val(), id: jQuery('#template').val(),
                data: JSON.stringify(readTree($("#treegrid").fancytree("getTree")))},
            success: function(resultData) {
                alert("Save Complete!");
                location.href = urls.survey_tpl + resultData.id;
            }
        });
    });
    $("#btnGridLoad").click(function(){
        initTree(urls.survey_tpl_edit + jQuery('#template').val());
    });

    jQuery('#template').change(function() {
        location.href = urls.survey_tpl + jQuery('#template').val();
    });

    if(id > 0) {
        initTree(urls.survey_tpl_edit + id);
    }
});