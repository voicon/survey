<?php
require_once 'config.php';
require_once ROOT . '/src/tpl/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$activedTplId = 0;
if($id == 0) {
    $aActiveTpl = CF::getActivedSurvey();
    if(isset($aActiveTpl['id']) && $aActiveTpl['id'] > 0) {
        $activedTplId = $aActiveTpl['id'];
    }
}
$users = CF::getAll(CF::TBL_USERS, 'id, `name`');
?>
<body class="survey">

<div class="container">
    <h2>THANG LƯỢNG GIÁ HÀNH VI THÍCH NGHI XÃ HỘI</h2>
    <form id="form">
        <div class="group">
            <label>Ngày</label> <input type="text" id="created_at" name="created_at" placeholder="Ngày tạo" />
        </div>
        <div class="group">
            <label>Họ và tên</label> <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" />
        </div>
        <div class="group">
            <label>Giới tính</label> <input type="radio" id="gender_male" name="gender" value="Nam" /> Nam <input type="radio" id="gender_female" name="gender" value="Nữ" /> Nữ
        </div>
        <div class="group">
            <label>Sinh ngày</label> <input type="text" id="birthday" name="birthday" placeholder="Ngày sinh" />
        </div>
        <div class="group">
            <label>Tuổi</label> <input type="text" id="age" name="age" readonly="readonly" />
        </div>
        <div class="group">
            <label>Địa chỉ</label> <textarea id="address" name="address" placeholder="Địa chỉ"> </textarea>
        </div>
        <div class="group">
            <label>Trường hay cơ quan</label> <input type="text" id="company" name="company" placeholder="Trường hay cơ quan" />
        </div>
        <div class="group">
            <label>Thông tin khác</label> <input type="text" id="information" name="information" placeholder="Thông tin khác" />
        </div>
        <div class="group">
            <label>Người được hỏi</label> <input type="text" id="reporter" name="reporter" placeholder="Người được hỏi" />
        </div>
        <div class="group">
            <label>Quan hệ với người được lượng giá</label> <input type="text" id="relationship" name="relationship" placeholder="Quan hệ với người được lượng giá" />
        </div>
        <div class="group">
            <label>Quan sát viên</label>
            <select id="user_id" name="user_id" >
                <option value=""> -- Select --</option>
                <?php foreach ($users as $user): ?>
                    <?php echo '<option value="' . $user['id'] . '" '.($user['id'] == $id ? ' selected="selected" ' : '').'>' . $user['name'] . '</option>'; ?>
                <?php endforeach;?>
            </select>
        </div>
        <div class="group">
            <label>Trường hợp số</label> <input type="text" id="no" name="no" placeholder="Trường hợp số" />
        </div>
    </form>
</div>


<!-- <div id="tree" class="demo-card-wide mdl-card mdl-shadow--2dp"> -->
<div id="tree" style="display: none"></div>
<hr>
<button id="btnGridExpandAll"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Expand all</button>
<button id="btnGridCollapseAll"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Collapse all</button>

<button id="btnSurveySave" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Save</button>
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
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
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
    var id = <?php echo $id ?>;
    var activedTplId = <?php echo $activedTplId ?>;
    var item = <?php echo $id > 0 ? json_encode($item) : '{}' ?>
</script>
<script src="assets/src/source-survey.js"></script>
<!-- Start_Exclude: This block is not part of the sample code -->
<link href="assets/lib/prettify.css" rel="stylesheet">
<script src="assets/lib/prettify.js"></script>
</body>
</html>