var CLIPBOARD = null;
var maxPlaceHolder = 6;

var urls = {
    main: "/" + BN + "/src/main.php",
    survey_tpl_edit: "/" + BN + "/src/main.php?a=edit&id=",
    survey_tpl: "/" + BN + "/survey-tpl.php?id=",
    survey_edit: "/" + BN + "/src/main.php?a=e-survey&id=",
    survey: "/" + BN + "/survey.php?id="

};

function nodeRender($tdList, i, data) {}

function readTree(tree) {
    /* first: store all attributes in a map (accessible with the key) */
    window.mapKeytoAttr = {};

    tree.visit(function(node) {
        var tdList = $(">td", node.tr), opts = {}, id = makeid();
        if(node.key[0] == '_') {
            node.key = id;
        }
        for(var i=2;i<(maxPlaceHolder + 2);i++) {
            opts['opt_' + (i-1)] = tdList.eq(i).find("input").val();
        }
        data = {
            opts : opts,
            description : tdList.eq(i).find("textarea").val()
        };
        window.mapKeytoAttr[node.key] = data;
    });

    /* second: use treeToDict() as before, but read attributes from the map */
    var d = tree.toDict(true, function(node) {
        node["data"] = window.mapKeytoAttr[node.key];
    });
    return d;
}
function makeid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 5; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
function initTree(sFilePath) {
    $("#tree").fancytree({
        extensions: ["glyph", "wide"],
        debugLevel: 3,
        checkbox: true,
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
        extensions: ["glyph", "table", "wide", "dnd", "edit"],
        dnd: {
            preventVoidMoves: true,
            preventRecursiveMoves: true,
            autoExpandMS: 400,
            dragStart: function(node, data) {
                return true;
            },
            dragEnter: function(node, data) {
                // return ["before", "after"];
                return true;
            },
            dragDrop: function(node, data) {
                data.otherNode.moveTo(node, data.hitMode);
            }
        },
        edit: {
            triggerStart: ["f2", "shift+click", "mac+enter"],
            close: function(event, data) {
                if( data.save && data.isNew ){
                    // Quick-enter: add new nodes until we hit [enter] on an empty title
                    $("#tree").trigger("nodeCommand", {cmd: "addSibling"});
                }
            }
        },
        checkbox: true,
        selectMode: 3,
        glyph: {
            preset: "material",
            map: {}
        },
        table: {
            checkboxColumnIdx: 0,
            nodeColumnIdx: 1
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
                    $tdList.eq(i).find('input').val(data.opts['opt_' + (i-1)]);
                }
            }
        }
    }).on("nodeCommand", function(event, data){
        // Custom event handler that is triggered by keydown-handler and
        // context menu:
        var refNode, moveMode,
            tree = $(this).fancytree("getTree"),
            node = tree.getActiveNode();

        switch( data.cmd ) {
            case "moveUp":
                refNode = node.getPrevSibling();
                if( refNode ) {
                    node.moveTo(refNode, "before");
                    node.setActive();
                }
                break;
            case "moveDown":
                refNode = node.getNextSibling();
                if( refNode ) {
                    node.moveTo(refNode, "after");
                    node.setActive();
                }
                break;
            case "indent":
                refNode = node.getPrevSibling();
                if( refNode ) {
                    node.moveTo(refNode, "child");
                    refNode.setExpanded();
                    node.setActive();
                }
                break;
            case "outdent":
                if( !node.isTopLevel() ) {
                    node.moveTo(node.getParent(), "after");
                    node.setActive();
                }
                break;
            case "rename":
                node.editStart();
                break;
            case "remove":
                refNode = node.getNextSibling() || node.getPrevSibling() || node.getParent();
                node.remove();
                if( refNode ) {
                    refNode.setActive();
                }
                break;
            case "addChild":
                node.editCreateNode("child", "");
                break;
            case "addSibling":
                node.editCreateNode("after", "");
                break;
            case "cut":
                CLIPBOARD = {mode: data.cmd, data: node};
                break;
            case "copy":
                CLIPBOARD = {
                    mode: data.cmd,
                    data: node.toDict(function(n){
                        delete n.key;
                    })
                };
                break;
            case "clear":
                CLIPBOARD = null;
                break;
            case "paste":
                if( CLIPBOARD.mode === "cut" ) {
                    // refNode = node.getPrevSibling();
                    CLIPBOARD.data.moveTo(node, "child");
                    CLIPBOARD.data.setActive();
                } else if( CLIPBOARD.mode === "copy" ) {
                    node.addChildren(CLIPBOARD.data).setActive();
                }
                break;
            default:
                alert("Unhandled command: " + data.cmd);
                return;
        }

        // }).on("click dblclick", function(e){
        // 	console.log( e, $.ui.fancytree.eventToString(e) );

    }).on("keydown", function(e){
        var cmd = null;

        // console.log(e.type, $.ui.fancytree.eventToString(e));
        switch( $.ui.fancytree.eventToString(e) ) {
            case "ctrl+shift+n":
            case "meta+shift+n": // mac: cmd+shift+n
                cmd = "addChild";
                break;
            case "ctrl+c":
            case "meta+c": // mac
                cmd = "copy";
                break;
            case "ctrl+v":
            case "meta+v": // mac
                cmd = "paste";
                break;
            case "ctrl+x":
            case "meta+x": // mac
                cmd = "cut";
                break;
            case "ctrl+n":
            case "meta+n": // mac
                cmd = "addSibling";
                break;
            case "del":
            case "meta+backspace": // mac
                cmd = "remove";
                break;
            // case "f2":  // already triggered by ext-edit pluging
            // 	cmd = "rename";
            // 	break;
            case "ctrl+up":
                cmd = "moveUp";
                break;
            case "ctrl+down":
                cmd = "moveDown";
                break;
            case "ctrl+right":
            case "ctrl+shift+right": // mac
                cmd = "indent";
                break;
            case "ctrl+left":
            case "ctrl+shift+left": // mac
                cmd = "outdent";
        }
        if( cmd ){
            $(this).trigger("nodeCommand", {cmd: cmd});
            // e.preventDefault();
            // e.stopPropagation();
            return false;
        }
    });

    /*
	 * Context menu (https://github.com/mar10/jquery-ui-contextmenu)
	 */
    $("#treegrid").contextmenu({
        delegate: "span.fancytree-node",
        menu: [
            {title: "Edit <kbd>[F2]</kbd>", cmd: "rename", uiIcon: "ui-icon-pencil" },
            {title: "Delete <kbd>[Del]</kbd>", cmd: "remove", uiIcon: "ui-icon-trash" },
            {title: "----"},
            {title: "New sibling <kbd>[Ctrl+N]</kbd>", cmd: "addSibling", uiIcon: "ui-icon-plus" },
            {title: "New child <kbd>[Ctrl+Shift+N]</kbd>", cmd: "addChild", uiIcon: "ui-icon-arrowreturn-1-e" },
            {title: "----"},
            {title: "Cut <kbd>Ctrl+X</kbd>", cmd: "cut", uiIcon: "ui-icon-scissors"},
            {title: "Copy <kbd>Ctrl-C</kbd>", cmd: "copy", uiIcon: "ui-icon-copy"},
            {title: "Paste as child<kbd>Ctrl+V</kbd>", cmd: "paste", uiIcon: "ui-icon-clipboard", disabled: true }
        ],
        beforeOpen: function(event, ui) {
            var node = $.ui.fancytree.getNode(ui.target);
            $("#treegrid").contextmenu("enableEntry", "paste", !!CLIPBOARD);
            node.setActive();
        },
        select: function(event, ui) {
            var that = this;
            // delay the event, so the menu can close and the click event does
            // not interfere with the edit control
            setTimeout(function(){
                $(that).trigger("nodeCommand", {cmd: ui.cmd});
            }, 100);
        }
    });
}