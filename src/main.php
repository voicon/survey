<?php
require_once __DIR__ . '/../config.php';
$aParams = $_REQUEST;
if(!isset($aParams['a'])) {
    die ('ST Wrong!');
}

$aData = array('status' => true, 'message' => 'Save!');

switch ($aParams['a']) {
    case 'save':
        $id = isset($aParams['id']) ? $aParams['id'] : 0;
        $sFile = isset($aParams['f']) && $aParams['f'] ? $aParams['f'] : time();
        $sData = $aParams['data'];

        /*if(file_exists($sFilePath)) {
            @copy($sFilePath, ROOT . '/data/bk/' . time() . '-' . $sFile);
        }*/
        /* @var $dbConn mysqli */
        $sNow = date("Y-m-d H:i:s");
        if($id > 0) {
            $sSql = "UPDATE " . CF::TBL_SURVEY_TEMPLATE . " SET name = ?, content = ?, updated_at = ?; ";
            $stmt = $dbConn->prepare($sSql);
            $stmt->bind_param('sss', $sFile, $sData, $sNow);
        } else {
            $sSql = "INSERT INTO " . CF::TBL_SURVEY_TEMPLATE . " (name, content, created_at, updated_at) VALUES (?, ?, ?, ?);";
            $stmt = $dbConn->prepare($sSql);
            $stmt->bind_param('ssss', $sFile, $sData, $sNow, $sNow);
        }
        if ($stmt === false) {
            trigger_error($dbConn->error, E_USER_ERROR);
            $aData['message'] = "Error: " . $sSql . "<br>" . $dbConn->error;
        }
        $status = $stmt->execute();
        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
            $aData['message'] = "Error: " . $sSql . "<br>" . $stmt->error;
        } else {
            if($id <= 0) {
                $id = $dbConn->insert_id;
            }
        }
        $aData['id'] = $id;
        $stmt->close();

        //@file_put_contents(ROOT . '/data/' . $sFile, $sData);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($aData);
        break;
    case 'edit':
        $id = $aParams['id'];
        $sData = '';
        if($id > 0) {
            if ($result = $dbConn->query("SELECT * FROM " . CF::TBL_SURVEY_TEMPLATE . " WHERE id = $id")) {
                while ($row = $result->fetch_assoc()) {
                    $sData = $row['content'];
                }
                /* free result set */
                $result->free();
            }
        }
        header("Content-type: application/json; charset=utf-8");
        echo $sData;
        break;
    case 'e-survey':
        $id = $aParams['id'];
        $sData = '';
        if($id > 0) {
            if ($result = $dbConn->query("SELECT * FROM " . CF::TBL_SURVEYS . " WHERE id = $id")) {
                while ($row = $result->fetch_assoc()) {
                    $sData = $row['content'];
                }
                /* free result set */
                $result->free();
            }
        }
        header("Content-type: application/json; charset=utf-8");
        echo $sData;
        break;
    case 's-survey':
        $id = isset($aParams['id']) ? $aParams['id'] : 0;
        $sFile = isset($aParams['f']) && $aParams['f'] ? $aParams['f'] : time();
        $sData = $aParams['data'];
        parse_str($aParams['form'], $aForm);
        /* @var $dbConn mysqli */
        $sNow = date("Y-m-d H:i:s");
        $age = DateTime::createFromFormat('Y-m-d', $aForm['birthday'], CF::tz())
            ->diff(new DateTime('now', CF::tz()))
            ->y;
        try {
            if($id > 0) {
                $sSql = "UPDATE " . CF::TBL_SURVEYS . " SET 
                fullname = ?, 
                birthday = ?,
                gender = ?,
                age = ?,
                address = ?,
                company = ?,
                information = ?,
                reporter = ?,
                relationship = ?,
                user_id = ?,
                no = ?, 
                updated_at = ?,
                content = ?; ";
                $stmt = $dbConn->prepare($sSql);
                $stmt->bind_param('sssssssssdsss',
                    $aForm['fullname'],
                    $aForm['birthday'],
                    $aForm['gender'],
                    $age,
                    $aForm['address'],
                    $aForm['company'],
                    $aForm['information'],
                    $aForm['reporter'],
                    $aForm['relationship'],
                    $aForm['user_id'],
                    $aForm['no'],
                    $sNow,
                    $sData);
            } else {
                $sSql = "INSERT INTO " . CF::TBL_SURVEYS . " (created_at, fullname, birthday, gender, age, address, company, information, reporter, relationship, user_id, `no`, updated_at, content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $stmt = $dbConn->prepare($sSql);
                $stmt->bind_param('ssssssssssdsss',
                    $sNow,
                    $aForm['fullname'],
                    $aForm['birthday'],
                    $aForm['gender'],
                    $age,
                    $aForm['address'],
                    $aForm['company'],
                    $aForm['information'],
                    $aForm['reporter'],
                    $aForm['relationship'],
                    $aForm['user_id'],
                    $aForm['no'],
                    $sNow,
                    $sData);
            }

            if ($stmt === false) {
                trigger_error($dbConn->error, E_USER_ERROR);
                $aData['message'] = "Error: " . $sSql . "<br>" . $dbConn->error;
            }
            $status = $stmt->execute();
            if ($status === false) {
                trigger_error($stmt->error, E_USER_ERROR);
                $aData['message'] = "Error: " . $sSql . "<br>" . $stmt->error;
            } else {
                if($id <= 0) {
                    $id = $dbConn->insert_id;
                }
            }
            $aData['id'] = $id;
            $stmt->close();
        } catch (Exception $e) {
            print_r($e);
        }
        //@file_put_contents(ROOT . '/data/' . $sFile, $sData);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($aData);
        break;
}

function f($aForm, $sKey) {
    return isset($aForm[$sKey]) ? $aForm[$sKey] : '';
}
