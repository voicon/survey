<?php
require_once __DIR__ . '/../config.php';
$aParams = $_REQUEST;
if(!isset($aParams['a'])) {
    die ('ST Wrong!');
}

switch ($aParams['a']) {
    case 'save':
        $sFile = $aParams['f'];
        $sData = $aParams['data'];
        $sFilePath = ROOT . '/data/' . $sFile;
        if(file_exists($sFilePath)) {
            @copy($sFilePath, ROOT . '/data/bk/' . time() . '-' . $sFile);
        }
        @file_put_contents(ROOT . '/data/' . $sFile, $sData);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode(
            array(
                'status' => true,
                'message' => 'Save!'
            )
        );
}
