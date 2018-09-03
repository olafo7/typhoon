<?php
//decode by qq2859470

class odocSendCheck extends model
{

    private $t_send_list = "odoc_send_list";
    private $t_tpl_list = "odoc_setting_template_list";
    private $t_setting_word_level = "odoc_setting_word_level";
    private $t_setting_word_hurry = "odoc_setting_word_hurry";
    private $t_flow = "odoc_setting_flow";
    private $t_flow_step = "odoc_setting_flow_step";
    private $t_step = "odoc_step";
    private $t_step_temp = "odoc_step_temp";

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadpage" );
        switch ( $task )
        {
        case "loadpage" :
            $this->_loadpage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getTemplateList" :
            $typeId = getpar( $_POST, "id", 0 );
            app::loadapp( "odoc", "settingTemplate" )->api_getTemplateList( $typeId, 2 );
            break;
        case "getTypeList" :
            app::loadapp( "odoc", "settingWord" )->api_getTypeList( );
            break;
        case "getLevelList" :
            app::loadapp( "odoc", "settingWord" )->api_getLevelList( );
            break;
        case "getHurryList" :
            app::loadapp( "odoc", "settingWord" )->api_getHurryList( );
            break;
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "submitFormData" :
            $this->_submitFormData( );
            break;
        case "submitFileData" :
            $this->_submitFileData( );
            break;
        case "getOdocFawenForm" :
            $this->_getOdocFawenForm( );
            break;
        case "loadTemplateFile" :
            $this->_loadTemplateFile( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "editOdocTemplate" :
            global $CNOA_DB;
            global $CNOA_CONTROLLER;
            global $CNOA_SESSION;
            $id = getpar( $_GET, "id", "" );
            $GLOBALS['GLOBALS']['id'] = $id;
            $GLOBALS['GLOBALS']['CNOA_SYSTEM_NAME'] = "发文审批";
            $GLOBALS['GLOBALS']['CNOA_USERNAME'] = $CNOA_SESSION->get( "TRUENAME" );
            $CNOA_CONTROLLER->loadViewCustom( $CNOA_CONTROLLER->appPath."/tpl/default/send/checkOdocTemplate.htm", TRUE, TRUE );
            exit( );
        case "getStepList" :
            $this->_getStepList( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            break;
        case "getHuiQianInfo" :
            $this->_getHuiQianInfo( );
            break;
        case "setHuiQianInfo" :
            $this->_setHuiQianInfo( );
            break;
        case "reStep" :
            $this->_reStep( );
            break;
        case "saveSay" :
            $this->_saveSay( );
            break;
        case "toPrevious" :
            $this->_toPrevious( );
            break;
        case "view" :
            $GLOBALS['_POST']['func'] = "send";
            app::loadapp( "odoc", "commonView" )->run( "send" );
        }
    }

    private function _toPrevious( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $fromId = getpar( $_GET, "fromId" );
        $id = getpar( $_GET, "id" );
        $reason = getpar( $_POST, "reason" );
        $where = "WHERE `id`=".$id." AND `fromType`=1";
        $data = array( );
        $data['status'] = 1;
        $data['etime'] = 0;
        $data['say'] = "";
        $CNOA_DB->db_update( $data, $this->t_step, $where );
        $info = $CNOA_DB->db_getone( "*", $this->t_step, "WHERE `fromId`=".$fromId." AND `id`={$id}" );
        $stepid = $info['stepid'];
        $stepuid = $info['uid'];
        $where = "WHERE `fromId`=".$fromId." AND `fromType`=1 AND `stepid`>{$stepid}";
        $data = array( );
        $data['status'] = 0;
        $data['say'] = "";
        $data['stime'] = 0;
        $data['etime'] = 0;
        $CNOA_DB->db_update( $data, $this->t_step, $where );
        $sendInfo = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`=".$fromId );
        $send_title = $sendInfo['title'];
        $this->__sendNotice( $stepuid, $send_title, 1, 8, $reason );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitFileData( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id", 0 ) );
        $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, "WHERE `fromType`='1' AND `fromId`='".$id."' AND `stepType`=1 AND `status`=2" );
        $odoc_ext = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $odoc_ext ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        $filePath = CNOA_PATH_FILE.( "/common/odoc/send/".$id."/doc.history.{$maxid}.php" );
        mkdirs( dirname( $filePath ) );
        if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $filePath ) )
        {
            echo "0";
            exit( );
        }
        echo "1";
        exit( );
    }

    private function _saveSay( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $fid = getpar( $_POST, "id" );
        $say = getpar( $_POST, "say" );
        $where = "WHERE `fromId`=".$fid." AND `uid`={$uid} AND `fromType`=1 AND `status`=1";
        $stepInfo = $CNOA_DB->db_getone( "*", $this->t_step, $where );
        $stepid = $stepInfo['stepid'];
        $step_id = $stepInfo['id'];
        $sendInfo = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`=".$stepInfo['fromId'] );
        $send_title = $sendInfo['title'];
        $where2 = "WHERE `fromId`=".$fid." AND `fromType`=1 AND `stepid`='{$stepid}'";
        $data = array( );
        $data['status'] = 2;
        $data['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['say'] = $say;
        $CNOA_DB->db_update( $data, $this->t_step, $where2.( "AND `uid`='".$uid."'" ) );
        $where = "WHERE `fromId`=".$fid." AND `fromType`=1 AND `stepid`=".intval( $stepid + 1 );
        $huiqianNum = $CNOA_DB->db_getCount( $this->t_step, "WHERE `fromId`=".$fid." AND `fromType`=1 AND `status`<2 AND `stepid`='{$stepid}' " );
        if ( $huiqianNum == 0 )
        {
            $data = array( );
            $data['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $data['status'] = 1;
            $CNOA_DB->db_update( $data, $this->t_step, $where );
        }
        switch ( intval( $stepInfo['stepType'] ) )
        {
        case 1 :
            $info = $CNOA_DB->db_getone( "*", $this->t_step, $where );
            if ( !( $info !== FALSE ) )
            {
                break;
            }
            $this->__sendNotice( intval( $info['uid'] ), $send_title, 1 );
            break;
        case 2 :
            $where = "WHERE `fromType`=1 AND `stepid`=".$stepid." AND `fromId`={$fid} AND `stepType`=1";
            $info = $CNOA_DB->db_getone( "*", $this->t_step, $where );
            $touid = $info['uid'];
            $noticeT = "{$uname}已经会签完毕。他/她的意见是：";
            $noticeC = $say;
            $noticeH = "index.php?app=odoc&func=send&action=check";
            $alarmid = notice::add( $touid, $noticeT, $noticeC, $noticeH, 0, 17, $uid );
        }
        $id = $step_id;
        $path = CNOA_PATH_FILE."/common/odoc/send/".$fid."/";
        @rename( $path."form.history.0.php", $path."form.history.".$id.".php" );
        @rename( $path."doc.history.0.php", $path."doc.history.".$id.".php" );
        $isOver = $this->__isOver( $fid );
        if ( $isOver )
        {
            $where = "WHERE `id`=".$fid;
            $data = array( );
            $data['status'] = 2;
            $data['senddate'] = $GLOBALS['CNOA_TIMESTAMP'];
            $CNOA_DB->db_update( $data, $this->t_send_list, $where );
            $where = "WHERE `fromId`=".$fid." AND `stepid`=1 AND `fromType`=1 AND `stepType`=1 AND `status`=2";
            $info = $CNOA_DB->db_getone( "*", $this->t_step, $where );
            $uid = $info['uid'];
            $this->__sendNotice( $uid, $send_title, 1, 6 );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`='".$id."'" );
        $formdata = addslashes( json_encode( $_POST ) );
        $data = array( );
        $data['formdata'] = $formdata;
        $data['createuid'] = $CNOA_SESSION->get( "UID" );
        $data['createtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        foreach ( $GLOBALS['_POST'] as $k => $v )
        {
            $name = preg_replace( "/id_[0-9]{1,}__(.*)/is", "\\1", $k );
            $in_array = array( "number", "title", "sign", "createdept", "createname_send", "level", "hurry", "page", "many", "range", "regdate", "senddate" );
            if ( in_array( $name, $in_array ) )
            {
                if ( $name == "createname_send" )
                {
                    $name = "createname";
                }
                $data[$name] = $v;
            }
        }
        $CNOA_DB->db_update( $data, $this->t_send_list, "WHERE `id`='".$id."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3003, $info['title'], "发文" );
        $formHtml = app::loadapp( "odoc", "common" )->getHtmlWithValue( $info['form'], $_POST );
        app::loadapp( "odoc", "common" )->saveHistory( $id, $formHtml, 0, "send" );
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _setHuiQianInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $unames = getpar( $_POST, "allUserNames" );
        $uids = getpar( $_POST, "allUids" );
        $fid = getpar( $_POST, "fid" );
        $arrName = explode( ",", $unames );
        $arrUid = explode( ",", $uids );
        $where = "WHERE `uid` NOT IN (".$uids.") AND `status`=1 AND `fromId`={$fid} AND `stepType`=2 AND `fromType`=1";
        $CNOA_DB->db_delete( $this->t_step, $where );
        $where = "WHERE `fromId`=".$fid." AND `status`=1  AND `fromType`=1";
        $stepInfo = $CNOA_DB->db_getone( array( "stepname", "stepid", "fromId" ), $this->t_step, $where );
        $stepid = $stepInfo['stepid'];
        $stepname = $stepInfo['stepname'];
        $sendInfo = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`=".$stepInfo['fromId'] );
        $send_title = $sendInfo['title'];
        foreach ( $arrUid as $key => $uid )
        {
            $name = $arrName[$key];
            if ( !( $uid == $cuid ) )
            {
                $deptInfo = app::loadapp( "main", "struct" )->api_getDeptByUid( $uid );
                $deptid = $deptInfo['id'];
                $deptname = $deptInfo['name'];
                $data = array( );
                $data['uid'] = $uid;
                $data['uname'] = $name;
                $data['fromId'] = $fid;
                $data['fromType'] = 1;
                $data['stepid'] = $stepid;
                $data['stepname'] = $stepname."[会签]";
                $data['detpId'] = $deptid;
                $data['deptName'] = $deptname;
                $data['stepType'] = 2;
                $data['status'] = 1;
                $data['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $CNOA_DB->db_insert( $data, $this->t_step );
                $this->__sendNotice( $uid, $send_title, 1, 2 );
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3003, "发文（".$send_title."）会签成员" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getHuiQianInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $fid = getpar( $_POST, "fid" );
        $uid = $CNOA_SESSION->get( "UID" );
        $where = "WHERE `fromId`=".$fid." AND `status`=1";
        $stepInfo = $CNOA_DB->db_getone( "*", $this->t_step, $where );
        $stepName = $stepInfo['stepname'];
        $stepid = $stepInfo['stepid'];
        $data = array( );
        $where = "WHERE `fromId`=".$fid." AND `stepid`={$stepid} AND `uid`!={$uid}  AND `stepType`=2";
        $dblist = $CNOA_DB->db_select( "*", $this->t_step, $where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        $unames = array( );
        foreach ( $dblist as $info )
        {
            $uids[] = $info['uid'];
            $unames[] = $info['uname'];
        }
        $data = array( );
        $data['allUserNames'] = implode( ",", $unames );
        $data['allUids'] = implode( ",", $uids );
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->stepname = $stepName;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function _reStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $fid = getpar( $_POST, "fid" );
        $reason = getpar( $_POST, "reason" );
        $where = "WHERE `stepid`>1 AND `fromId`=".$fid." AND `fromType`=1";
        $dblist = $CNOA_DB->db_select( "*", $this->t_step, $where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $dbinfo )
        {
            $step_id = $dbinfo['id'];
            $path = CNOA_PATH_FILE."/common/odoc/send/".$fid."/";
            @unlink( $path."form.history.".$step_id.".php" );
            @unlink( $path."doc.history.".$step_id.".php" );
        }
        $data = array( );
        $data['status'] = 0;
        $data['stime'] = 0;
        $data['etime'] = 0;
        $where = "WHERE `stepid`>1 AND `fromId`=".$fid." AND `fromType`=1";
        $CNOA_DB->db_update( $data, $this->t_step, $where );
        $data = array( );
        $data['say'] = "<b>[退件]：</b>".nl2br( $reason );
        $where = "WHERE `status`=1 AND `fromId`=".$fid." AND `stepType`=1 AND `fromType`=1 AND `uid`={$uid}";
        $CNOA_DB->db_update( $data, $this->t_step, $where );
        $data = array( );
        $data['status'] = 1;
        $data['say'] = $reason;
        $CNOA_DB->db_update( $data, $this->t_step, "WHERE `stepid`=1 AND `fromId`=".$fid." AND `fromType`=1" );
        $data = array( );
        $data['status'] = 0;
        $CNOA_DB->db_update( $data, $this->t_send_list, "WHERE `id`=".$fid );
        $where = "WHERE `id`='".$fid."'";
        $send_info = $CNOA_DB->db_getone( array( "title", "number", "createuid" ), $this->t_send_list, $where );
        $send_title = $send_info['title'];
        $send_num = $send_info['number'];
        $where = "WHERE `fromId`=".$fid." AND `stepid`=1 AND `fromType`=1 AND `stepType`=1 AND `status`=1";
        $step_info = $CNOA_DB->db_getone( "*", $this->t_step, $where );
        $step_uid = $step_info['uid'];
        $this->__sendNotice( $step_uid, $send_title, 1, 7, $reason );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3003, $reason, "退件发文（".$send_title."）" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getStepList( )
    {
        global $CNOA_DB;
        $where = "WHERE 1";
        $fid = getpar( $_POST, "fid" );
        $where .= " AND `fromId`=".$fid;
        $prev = getpar( $_POST, "prev" );
        if ( !empty( $prev ) )
        {
            $stepInfo = $CNOA_DB->db_getone( array( "stepid" ), $this->t_step, "WHERE `fromId`=".$fid." AND `status`=2 AND `stepType`=1 ORDER BY `stepid` DESC" );
            $stepid = $stepInfo['stepid'];
            $where .= " AND `stepid`<=".$stepid." AND `stepid`>1 AND `status`>0";
        }
        $where .= " AND `fromType`=1";
        $dblist = $CNOA_DB->db_select( "*", $this->t_step, $where." ORDER BY `stepid` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $info )
        {
            $db =& $data[];
            $db = $info;
            $db['status'] = app::loadapp( "odoc", "sendApply" )->api_getStepStatus( $db['status'] );
            $db['from'] = $db['deptName']." / ".$db['uname'];
            if ( intval( $db['etime'] ) <= 0 )
            {
                $db['stime'] = formatdate( $db['stime'], "Y-m-d H:i" );
                $db['etime'] = "";
            }
            else
            {
                $db['stime'] = formatdate( $db['stime'], "Y-m-d H:i" );
                $db['etime'] = formatdate( $db['etime'], "Y-m-d H:i" );
            }
            $db['say'] = nl2br( $db['say'] );
            if ( $db['stepType'] == 2 )
            {
                $db['stepname'] = $db['stepname']." <b>[会签]</b>";
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function __isOver( $fid )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $where = "WHERE `status`<2 AND `fromId`=".$fid." AND `fromType`=1";
        $data = $CNOA_DB->db_getone( "*", $this->t_step, $where );
        if ( $data === FALSE )
        {
            return TRUE;
        }
        return FALSE;
    }

    private function __sendNotice( $uid, $title = "", $type = 1, $to = 1, $say = "" )
    {
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $where = "WHERE `touid`=".$uid." AND `sourceid`={$cuid}";
        $notice = app::loadapp( "notice", "notice" )->api_getData( $where );
        if ( $notice !== FALSE )
        {
            return FALSE;
        }
        $href = "index.php?app=odoc&func=send&action=check";
        $noticeType = 17;
        $say = nl2br( $say );
        $sType = "";
        if ( intval( $type ) == 1 )
        {
            $sType = "发文稿";
        }
        else if ( intval( $type ) == 2 )
        {
            $sType = "收文稿";
            $noticeType = 18;
            $href = "index.php?app=odoc&func=receive&action=check";
        }
        $sDo = "";
        switch ( intval( $to ) )
        {
        case 1 :
            $sDo = "审批";
            break;
        case 2 :
            $sDo = "进行会签";
            break;
        case 3 :
            $sDo = "到“发文阅读”查看";
            $href = "index.php?app=odoc&func=read&action=send";
            $noticeType = 19;
            break;
        case 4 :
            $sDo = "审批";
            $sType = "";
            break;
        case 5 :
            $sDo = "到“收文登记”查收";
            $sType = "新的公文";
            $href = "index.php?app=odoc&func=receive&action=apply";
            $noticeType = 22;
            break;
        case 7 :
            $sDo = "重新拟稿";
            $sType = "";
        }
        $noticeT = "公文管理";
        $noticeC = "“".$title."”{$sType}需要您{$sDo}.";
        $noticeH = $href;
        switch ( intval( $to ) )
        {
        case 6 :
            $sDo = "";
            $sType = "发文稿已经审批结束";
            $href = "";
            $noticeT = "您的发文稿已经审批结束";
            $noticeC = "“".$title."”{$sType}";
            $noticeH = "index.php?app=odoc&func=send&action=list";
            $noticeType = 17;
            break;
        case 7 :
            $sDo = "";
            $sType = "的发文稿被退回. <br /> <b>原因：</b>".$say;
            $href = "";
            $noticeT = "您的发文稿被退回.";
            $noticeC = "“".$title."”{$sType}";
            $noticeH = "index.php?app=odoc&func=send&action=apply";
            $noticeType = 17;
            break;
        case 8 :
            $sDo = "";
            $sType = "发文稿被退回给您重新审批. <br /> <b>原因：</b>".$say;
            $href = "";
            $noticeT = "{$uname}将文稿退给您重新审批.";
            $noticeC = "“".$title."”{$sType}";
        }
        $alarmid = notice::add( $uid, $noticeT, $noticeC, $noticeH, 0, $noticeType, $cuid );
    }

    public function api_sendNotice( $uid, $title, $type = 1, $to = 1, $say = "" )
    {
        return $this->__sendNotice( $uid, $title, $type, $to, $say );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", 0 );
        $data = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id` = '".$id."'" );
        $userInfo = app::loadapp( "main", "user" )->api_getUserDataByUid( $uid );
        $deptmentInfo = app::loadapp( "main", "struct" )->api_getInfoById( $userInfo['deptId'] );
        $data['createpeople'] = $userInfo['truename'];
        $data['createdept'] = $deptmentInfo['name'];
        ( );
        $fs = new fs( );
        $data['attach'] = json_decode( $data['attach'], TRUE );
        $data['attachCount'] = !$data['attach'] ? 0 : count( $data['attach'] );
        $data['attach'] = $fs->getDownLoadItems4normal( $data['attach'], TRUE );
        $stepInfo = $CNOA_DB->db_getone( "*", $this->t_step, "WHERE `fromId`=".$id." AND `status`=1 AND `uid`={$uid}" );
        $data['stepData'] = $stepInfo;
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _loadTemplateFile( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_GET, "id", 0 ) );
        $info = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`='".$id."'" );
        $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, "WHERE `fromType`=1 AND `fromId`='".$id."' AND `stepType`=1 AND `status`=2" );
        $formPath = CNOA_PATH_FILE.( "/common/odoc/send/".$id."/doc.history.{$maxid}.php" );
        if ( file_exists( $formPath ) )
        {
            $form = file_get_contents( $formPath );
        }
        else
        {
            $form = "无正文内容";
        }
        echo $form;
        exit( );
    }

    private function _getOdocFawenForm( )
    {
        $GLOBALS['_GET']['OdocFawenForm_from'] = "check";
        app::loadapp( "odoc", "sendApply" )->api_getOdocFawenForm( );
    }

    private function _loadpage( )
    {
        $task = getpar( $_GET, "task", "loadpage" );
    }

    private function _getJsonData( )
    {
        $js = app::loadapp( "odoc", "sendApply" )->api_getSendList( );
        echo $js;
        exit( );
    }

    public function api_getStepList( )
    {
        $this->_getStepList( );
    }

}

?>
