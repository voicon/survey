<?php
require_once 'config.php';
require_once ROOT . '/src/tpl/header.php';

list ($aCols, $aItems) = CF::db();
?>
<body class="admin">
<ul>
    <li><a href="survey.php" target="_blank">Do survey</a></li>
    <li><a href="survey-tpl.php" target="_blank">Add survey template</a></li>
</ul>
<div class="container">
    <div class="filter">
        <form>
            <p>Choose data want to display
                <select name="tbl">
                    <option value="users">Users</option>
                    <option value="surveys">Survey data</option>
                    <option value="survey_templates">Survey templates</option>
                </select>
            </p>
        </form>
    </div>

    <table id="data" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <?php foreach ($aCols as $oCol):?>
            <th><?php echo $oCol['Field']?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <?php foreach ($aCols as $oCol):?>
                <th><?php echo $oCol['Field']?></th>
            <?php endforeach ?>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($aItems as $aItem):?>
        <tr>
            <?php foreach ($aCols as $oCol):?>
                <td><?php echo CF::v($aItem, $oCol['Field'])?></td>
            <?php endforeach ?>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<link href="assets/src/jquery.dataTables.css" rel="stylesheet">
<script src="assets/src/jquery.dataTables.min.js"></script>
<script src="assets/src/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#data').dataTable().columnFilter({
            "initComplete": function( settings, json ) {}
        });
        jQuery('.filter select').change(function() {
            jQuery('.filter form').submit();
        })
        jQuery(".filter select").val('<?php echo CF::v($_REQUEST, 'tbl')?>');
    });
</script>
</body>
</html>