function getEle(data, i) {
    var val = '';
    var options = [
        {key: 'DK', val: "Không biết"},
        {key: 2, val: "Có, thường thường"},
        {key: 1, val: "Đôi khi hay một phần"},
        {key: 0, val: "Không, không bao giờ"},
        {key: 'N', val: "Không áp dụng"}

    ];
    if(data.opts_data != undefined && data.opts_data['opt_' + (i-1)] != undefined) {
        val = data.opts_data['opt_' + (i-1)];
    }
    var select = ['<select>'];
    options.forEach(function(option) {
        select.push('<option value="'+option.key+'" '+(option.key == val ? 'selected="selected"' : '')+'>' + option.val + '</option>');
    });
    select.push('</select>')
    return select.join('');
}

function nodeRender($tdList, i, data) {
    switch (data.opts['opt_' + (i-1)]) {
        case '0':case undefined:
            $tdList.eq(i)[0].innerHTML = '';
            break;
        case '1':
            $tdList.eq(i)[0].innerHTML = getEle(data, i);
            break;
        default:
            $tdList.eq(i)[0].innerHTML = data.opts['opt_' + (i-1)];

    }
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

    $("#btnSurveySave").click(function(){
        jQuery.ajax({
            type: 'POST',
            url: urls.main,
            data: {a: 's-survey', f: jQuery('#name').val(), id: id,
                data: JSON.stringify(readTree($("#treegrid").fancytree("getTree"))),
                form: jQuery('#form').serialize()},
            success: function(resultData) {
                alert("Save Complete!");
                location.href = urls.survey + resultData.id;
            }
        });
    });

    if(id == 0 && activedTplId > 0) {
        initTree(urls.survey_tpl_edit + activedTplId);
    } else if(id > 0) {
        initTree(urls.survey_edit + id);
    }
});

function readTree(tree) {
    /* first: store all attributes in a map (accessible with the key) */
    window.mapKeytoAttr = {};

    tree.visit(function(node) {
        var tdList = $(">td", node.tr), opts_data = {}, id = makeid();
        if(node.key[0] == '_') {
            node.key = id;
        }
        for(var i=2;i<(maxPlaceHolder + 2);i++) {
            var input = tdList.eq(i).find("select");
            if(input.length > 0) {
                opts_data['opt_' + (i-1)] = input.val();
            } else {
                opts_data['opt_' + (i-1)] = '';
            }

        }
        data = {
            opts_data : opts_data,
            opts: node.data.opts,
            description: node.data.description
        };
        window.mapKeytoAttr[node.key] = data;
    });

    /* second: use treeToDict() as before, but read attributes from the map */
    var d = tree.toDict(true, function(node) {
        node["data"] = window.mapKeytoAttr[node.key];
    });
    return d;
}
function initTree(sFilePath) {
    $("#tree").fancytree({
        extensions: ["glyph", "wide"],
        selectMode: 3,
        tooltip: function(event, data) {
            var node = data.node,
                data = node.data;

            if( data.opts ) {
                return node.title + ", " + data.opts.opt_1 + ", " + data.opts.opt_2;
            }
        },
        glyph: {
            preset: "material",
            map: {}
        },
        source: {url: sFilePath, debugDelay: 1000},
        lazyLoad: function(event, data) {
            data.result = {url: "assets/demo/ajax-sub2.json", debugDelay: 1000};
        }
    });
    $("#btnReloadTree").click(function(){
        $.ui.fancytree.getTree(0).reload();
    });

    // --- Initialize Fancytree Grid -------------------------------------------
    $("#treegrid").fancytree({
        extensions: ["glyph", "table", "wide"],
        selectMode: 3,
        glyph: {
            preset: "material",
            map: {}
        },
        table: {
            nodeColumnIdx: 0
        },
        source: {url: sFilePath, debugDelay: 1000},
        lazyLoad: function(event, data) {
            data.result = {url: "assets/demo/ajax-sub2.json", debugDelay: 1000};
        },
        createNode: function(event, data) {
            var node = data.node,
                $tdList = $(node.tr).find(">td");

            // Span the remaining columns if it's a folder.
            // We can do this in createNode instead of renderColumns, because
            // the `isFolder` status is unlikely to change later
            if( node.isFolder() ) {
                /*$tdList.eq(2)
                    .prop("colspan", 6)
                    .nextAll().remove();*/
            }
        },
        renderColumns: function(event, data) {
            var node = data.node,
                data = node.data,
                $tdList = $(node.tr).find(">td");
            if( data.opts ) {
                if(data.description === undefined) data.description = '';
                $tdList.eq(1).append('<p>' + data.description + '</p>');
                for(var i=2;i<(maxPlaceHolder + 2);i++) {
                    nodeRender($tdList, i, data);
                }
            }
        }
    });
}