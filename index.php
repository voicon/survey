<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Fancytree - Example: ext-glyph - material design</title>

  <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
	<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <!-- jquery-contextmenu (https://github.com/mar10/jquery-ui-contextmenu/) -->
	<script src="//cdn.jsdelivr.net/npm/ui-contextmenu/jquery.ui-contextmenu.min.js"></script>

	<link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="//code.getmdl.io/1.3.0/material.indigo-pink.min.css">
	<!-- <script defer src="//code.getmdl.io/1.3.0/material.min.js"></script> -->

	<link href="assets/src/skin-material/ui.fancytree.css" rel="stylesheet" class="skinswitcher">
<!-- End_Exclude -->

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
	input[type=text] {
		width: 20px;
	}
</style>
</head>

<body class="example">

	<!-- <div id="tree" class="demo-card-wide mdl-card mdl-shadow--2dp"> -->
	<div id="tree" style="display: none">
	</div>

	<hr>

	<!-- Accent-colored raised button with ripple -->
    <input type="text" id="filename" style="width: 200px" />
    <button id="btnGridLoad"
            class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
        Load
    </button>
	<button id="btnGridExpandAll"
		class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
	  Expand all
	</button>
	<button id="btnGridCollapseAll"
		class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
	  Collapse all
	</button>
	<button id="btnGridSave"
			class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
		Save
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
    <script src="assets/src/jquery.fancytree.js"></script>
    <script src="assets/src/jquery.fancytree.glyph.js"></script>
    <script src="assets/src/jquery.fancytree.dnd.js"></script>
    <script src="assets/src/jquery.fancytree.edit.js"></script>
    <script src="assets/src/jquery.fancytree.table.js"></script>
    <script src="assets/src/jquery.fancytree.wide.js"></script>
    <script type="text/javascript">
        var BN = "<?php echo BN ?>";
    </script>
    <script src="assets/src/source.js"></script>

    <!-- Start_Exclude: This block is not part of the sample code -->
    <link href="assets/lib/prettify.css" rel="stylesheet">
    <script src="assets/lib/prettify.js"></script>
</body>
</html>
