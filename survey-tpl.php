<?php
require_once 'config.php';
require_once ROOT . '/src/tpl/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
if($id > 0) {
    $item = CF::getAllTemplates($id);
    $item = $item[0];
}
?>
<style type="text/css">
    ul.fancytree-container {
        width: 315px;
        border: 0;
        outline: 0;  /* No focus frame */
    }
    table.fancytree-container {
        table-layout: fixed;
        outline: 0;  /* No focus frame */
    }
    input, select {
        line-height: 25px;
        width: 200px;
        height: 25px;
    }
    input[type=text] {
        width: 20px;
    }
</style>
<body class="example">
<ul>
    <li><a href="survey.php" target="_blank">Do survey</a></li>
    <li><a href="surveys.php" target="_blank">Admin page</a></li>
</ul>
<!-- <div id="tree" class="demo-card-wide mdl-card mdl-shadow--2dp"> -->
<div id="tree" style="display: none"></div>

<hr>

<!-- Accent-colored raised button with ripple -->
<?php $aTpls = CF::getAllTemplates();?>
<select id="template">
    <option value=""> -- Select an existing template to edit --</option>
<?php foreach ($aTpls as $tpl): ?>
<?php echo '<option actived="' . $tpl['actived'] . '" value="' . $tpl['id'] . '" '.($tpl['id'] == $id ? ' selected="selected" ' : '').'>' . $tpl['name'] . '</option>'; ?>
<?php endforeach;?>
</select>
<br />
<input type="text" id="name" style="width: 300px" placeholder="Create new survey template name" value="<?php echo $id > 0 ? $item['name'] : '' ?>"/>
<input type="checkbox" id="actived" <?php echo ($id > 0 && $item['actived'] == 1) ? ' checked="checked" ' : '' ?>/> Is actived?
<button id="btnGridSave"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
    Save
</button>
<br /><br />
<button id="btnGridExpandAll"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
    Expand all
</button>
<button id="btnGridCollapseAll"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
    Collapse all
</button>
<table id="treegrid" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
    <!-- <table id="treegrid" class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"> -->
    <colgroup>
        <col style="width: 40px;">
        <col style="width: 400px;">
        <col style="width: 80px;">
        <col style="width: 80px;">
        <col style="width: 80px;">
    </colgroup>
    <thead>
    <tr>
        <!-- NOTE: the first column will be inserted by MDL if mdl-data-table--selectable is set. -->
        <th class="mdl-data-table__cell--non-numeric"></th>
        <th class="mdl-data-table__cell--non-numeric">Item</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="mdl-data-table__cell--non-numeric"></td>
        <td class="mdl-data-table__cell--non-numeric"></td>
        <td><input name="cb1" type="text"></td>
        <td><input name="cb2" type="text"></td>
        <td><input name="cb3" type="text"></td>
        <td><input name="cb4" type="text"></td>
        <td><input name="cb5" type="text"></td>
        <td><input name="cb6" type="text"></td>
    </tr>
    </tbody>
</table>
<script src="assets/src/common.js"></script>
<script src="assets/src/jquery.fancytree.js"></script>
<script src="assets/src/jquery.fancytree.glyph.js"></script>
<script src="assets/src/jquery.fancytree.dnd.js"></script>
<script src="assets/src/jquery.fancytree.edit.js"></script>
<script src="assets/src/jquery.fancytree.table.js"></script>
<script src="assets/src/jquery.fancytree.wide.js"></script>
<script type="text/javascript">
    var BN = "<?php echo BN ?>";
    var id = <?php echo $id ?>;
    var item = <?php echo $id > 0 ? json_encode($item) : '{}' ?>
</script>
<script src="assets/src/source.js"></script>

<!-- Start_Exclude: This block is not part of the sample code -->
<link href="assets/lib/prettify.css" rel="stylesheet">
<script src="assets/lib/prettify.js"></script>
</body>
</html>