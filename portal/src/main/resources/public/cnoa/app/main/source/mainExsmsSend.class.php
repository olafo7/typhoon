<?php
//decode by qq2859470

class mainExsmsSend extends model
{

    private $table_setting = "main_exsms_setting";
    private $table_outbox = "main_exsms_outbox";
    private $table_status = "main_exsms_status";
    private $table_lsort = "main_exsms_lsort";
    private $table_linkman = "main_exsms_linkman";

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "getLinkmanList" :
            $this->_getLinkmanList( );
            break;
        case "getSortList" :
            $this->_getSortList( );
            break;
        case "submitLinkman" :
            $this->_submitLinkman( );
            break;
        case "deleteLinkman" :
            $this->_deleteLinkman( );
            break;
        case "getGroupList" :
            $this->_getGroupList( );
            break;
        case "updateGroup" :
            $this->_updateGroup( );
            break;
        case "deleteGroup" :
            $this->_deleteGroup( );
            break;
        case "getLinkmanTree" :
            $this->_getLinkmanTree( );
            break;
        case "send" :
            $this->_send( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "send" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/exsms/send.htm";
        }
        else if ( $from == "linkman" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/exsms/linkman.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _submit( )
    {
        $insideUids = getpar( $_POST, "insideUids", "" );
        if ( $insideUids[strlen( $insideUids ) - 1] == "," )
        {
            $insideUids = substr( $uids, 0, -1 );
        }
        $arrUid = explode( ",", $insideUids );
        $uids = getpar( $_POST, "receiverUids", "" );
        $uids = explode( ",", $uids );
        $time = getpar( $_POST, "time", 0 );
        if ( $time != 0 )
        {
            $time = strtotime( $time );
            if ( $time < $GLOBALS['CNOA_TIMESTAMP'] )
            {
                $time = $GLOBALS['CNOA_TIMESTAMP'];
            }
        }
        else
        {
            $time = $GLOBALS['CNOA_TIMESTAMP'];
        }
        $text = getpar( $_POST, "text", "" );
        $from = "hand";
        if ( !is_array( $uids ) && count( $uids ) <= 0 || empty( $text ) || !isset( $text ) )
        {
            msg::callback( FALSE, lang( "notChoiceReceiveUser" ) );
        }
        ( );
        $sms = new sms( );
        $sms->sendByUids( $uids, $text, $time, $from );
        if ( !is_array( $arrUid ) )
        {
            $arrUid = array( );
        }
        if ( !empty( $uids ) )
        {
        }
        msg::callback( TRUE, lang( "SMSbeenSuccess" ) );
    }

    private function _getLinkmanList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $s_name = getpar( $_POST, "name", "" );
        $s_moblie = getpar( $_POST, "mobile", 0 );
        $rows = 15;
        $where = "WHERE 1 ";
        $type = getpar( $_POST, "type", 0 );
        if ( !empty( $type ) )
        {
            $where .= "AND `sid`='".$type."' ";
        }
        if ( !empty( $s_name ) )
        {
            $where .= "AND `name` LIKE '%".$s_name."%' ";
        }
        if ( !empty( $s_moblie ) )
        {
            $where .= "AND `mobile` LIKE '%".$s_moblie."%' ";
        }
        $dbList = $CNOA_DB->db_select( "*", $this->table_linkman, $where );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = count( $dbList );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getSortList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $where = "WHERE 1 ";
        $dbList = $CNOA_DB->db_select( "*", $this->table_lsort, $where );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = count( $dbList );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _submitLinkman( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $data = array( );
        $data['sid'] = getpar( $_POST, "sid", 0 );
        $data['mobile'] = getpar( $_POST, "mobile", 0 );
        $data['name'] = getpar( $_POST, "name", 0 );
        if ( $id != 0 )
        {
            $CNOA_DB->db_update( $data, $this->table_linkman, "WHERE `id`='".$id."'" );
        }
        else
        {
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $CNOA_DB->db_insert( $data, $this->table_linkman );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteLinkman( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", NULL );
        if ( $ids )
        {
            $ids = explode( ",", substr( $ids, 0, -1 ) );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $CNOA_DB->db_delete( $this->table_linkman, "WHERE `id`='".$v."'" );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getGroupList( )
    {
        global $CNOA_DB;
        $dblist = array( );
        $dblist = $CNOA_DB->db_select( "*", $this->table_lsort, "WHERE 1" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _updateGroup( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", "0" );
        $name = getpar( $_POST, "name", "" );
        if ( $id == 0 )
        {
            $CNOA_DB->db_insert( array(
                "name" => $name,
                "posttime" => $GLOBALS['CNOA_TIMESTAMP']
            ), $this->table_lsort );
        }
        else
        {
            $CNOA_DB->db_update( array(
                "name" => $name
            ), $this->table_lsort, "WHERE `id`='".$id."'" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteGroup( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", NULL );
        $CNOA_DB->db_delete( $this->table_linkman, "WHERE `sid`='".$ids."'" );
        $CNOA_DB->db_delete( $this->table_lsort, "WHERE `id`='".$ids."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getLinkmanTree( )
    {
        global $CNOA_DB;
        $sortList = $CNOA_DB->db_select( "*", $this->table_lsort, "WHERE 1" );
        if ( !is_array( $sortList ) )
        {
            $sortList = array( );
        }
        $sortList2 = array( );
        foreach ( $sortList as $sv )
        {
            $sortList2[$sv['id']] = $sv;
        }
        $dbList = array( );
        $linkmanList = $CNOA_DB->db_select( "*", $this->table_linkman, "WHERE 1" );
        if ( !$linkmanList )
        {
            $dbList[] = array(
                "text" => "暂无数据",
                "disabled" => TRUE,
                "iconCls" => "icon-tree-root-cnoa",
                "href" => "javascript:void(0);",
                "leaf" => FALSE,
                "children" => array( )
            );
        }
        else
        {
            if ( !is_array( $linkmanList ) )
            {
                $linkmanList = array( );
            }
            $listArray = array( );
            foreach ( $linkmanList as $v )
            {
                $listArray[$v['sid']]['text'] = $sortList2[$v['sid']]['name'];
                $listArray[$v['sid']]['iconCls'] = "icon-tree-root-cnoa";
                $listArray[$v['sid']]['href'] = "javascript:void(0);";
                $listArray[$v['sid']]['leaf'] = FALSE;
                $listArray[$v['sid']]['children'][] = array(
                    "text" => "(".$v['mobile'].") ".$v['name'],
                    "lid" => $v['id'],
                    "iconCls" => "icon-tree-im-online",
                    "href" => "javascript:void(0);",
                    "leaf" => TRUE
                );
            }
            $dbList = array_merge( $listArray );
        }
        echo json_encode( $dbList );
        exit( );
    }

    private function _send( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = array( );
        $text = getpar( $_POST, "text", "", 1 );
        if ( empty( $text ) || !isset( $text ) )
        {
            msg::callback( FALSE, lang( "contentNotEmpty" ) );
        }
        $insideUids = getpar( $_POST, "insideUids", "" );
        $insideArr = explode( ",", $insideUids );
        $lids = getpar( $_POST, "receiverLids", "" );
        $lids = explode( ",", $lids );
        array_pop( &$lids );
        if ( !is_array( $lids ) && count( $lids ) <= 0 )
        {
            if ( count( $insideArr ) && $insideArr[0] == "" )
            {
                msg::callback( FALSE, lang( "notChoiceReceiveUser" ) );
            }
            else
            {
                $insideName = $CNOA_DB->db_select( array( "truename", "mobile" ), "main_user", "WHERE `uid` IN (".$insideUids.")" );
            }
        }
        else
        {
            if ( count( $insideArr ) && $insideArr[0] != "" )
            {
                $insideName = $CNOA_DB->db_select( array( "truename", "mobile" ), "main_user", "WHERE `uid` IN (".$insideUids.")" );
            }
            $linkmanDb = $CNOA_DB->db_select( "*", $this->table_linkman, "WHERE `id` IN (".implode( ",", $lids ).")" );
            if ( !$linkmanDb )
            {
                msg::callback( FALSE, lang( "notSendCellNum" ) );
            }
        }
        $config = app::loadapp( "main", "exsmsSetting" )->api_getSetting( );
        if ( empty( $config['api_send_url'] ) )
        {
            msg::callback( FALSE, lang( "SMSinterfaceNotSet" ) );
        }
        $data['text'] = $text;
        if ( $config['charset'] != "UTF-8" && $config['charset'] != "" )
        {
            $text = iconv( "UTF-8", $config['charset'], $text );
        }
        $mobiless = array( );
        $dbto = array( );
        if ( is_array( $linkmanDb ) )
        {
            foreach ( $linkmanDb as $v )
            {
                $mobiless[] = $v['mobile'];
                $dbto[] = array(
                    "n" => $v['name'],
                    "m" => $v['mobile']
                );
            }
        }
        if ( is_array( $insideName ) )
        {
            foreach ( $insideName as $v )
            {
                if ( !empty( $v['mobile'] ) )
                {
                    $mobiless[] = $v['mobile'];
                    $dbto[] = array(
                        "n" => $v['truename'],
                        "m" => $v['mobile']
                    );
                }
            }
        }
        $mobiles = implode( $config['mobilesplite'], $mobiless );
        $time = getpar( $_POST, "time", 0 );
        if ( $time != 0 )
        {
            $time = strtotime( $time );
            if ( $time < $GLOBALS['CNOA_TIMESTAMP'] )
            {
                $data['sendtime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $time = "";
            }
            else
            {
                $data['sendtime'] = $time;
                $time = date( $config['timeformat'], $time );
            }
        }
        else
        {
            $data['sendtime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $time = "";
        }
        $mobilesSplite = array_chunk( $mobiless, 100 );
        foreach ( $mobilesSplite as $spv )
        {
            $mobiles = implode( $config['mobilesplite'], $spv );
            $config['api_send_url'] = str_replace( array(
                "@@SENDTIME@@",
                "@@MOBILES@@",
                "@@TEXT@@",
                "@@MKTIME@@"
            ), array(
                urlencode( $time ),
                urlencode( $mobiles ),
                urlencode( $text ),
                urlencode( date( $config['timeformat'], $GLOBALS['CNOA_TIMESTAMP'] ) )
            ), $config['api_send_url'] );
            $return = @file_get_contents( $config['api_send_url'] );
            if ( !$return )
            {
                msg::callback( FALSE, lang( "interfaceConnetFail" ) );
            }
        }
        $return = preg_replace( $config['callbackregex'], "\\1", $return );
        $rtrue = FALSE;
        $rmsg = "";
        $statusInfo = $CNOA_DB->db_getone( "*", $this->table_status, "WHERE `code`='".addslashes( $return )."'" );
        if ( !$statusInfo )
        {
            $statusInfo = $CNOA_DB->db_getone( "*", $this->table_status, "WHERE `code`='*'" );
            if ( !$statusInfo )
            {
                $rtrue = FALSE;
                $rmsg = lang( "unKnownReturnStatus" ).( "：[".$return."]" );
            }
            else
            {
                $rtrue = TRUE;
                $rmsg = $statusInfo['name'].( "[".$return."]" );
            }
        }
        else
        {
            $rtrue = TRUE;
            $rmsg = $statusInfo['name'].( "[".$return."]" );
        }
        $data['fromuid'] = $CNOA_SESSION->get( "UID" );
        $data['fromname'] = $CNOA_SESSION->get( "TRUENAME" );
        $data['to'] = addslashes( json_encode( $dbto ) );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['status'] = 1;
        $data['statusText'] = addslashes( $rmsg );
        $CNOA_DB->db_insert( $data, $this->table_outbox );
        $content = "";
        foreach ( $dbto as $v )
        {
            $content .= $v['n'].( "(".$v['m']."), " );
        }
        $content = substr( $content, 0, -2 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 97, $content, lang( "sendSms" ) );
        msg::callback( $rtrue, $rmsg );
    }

    private function _import_linkman_upload( )
    {
        global $CNOA_XXTEA;
        global $CNOA_DB;
        set_time_limit( 0 );
        if ( $CNOA_DB )
        {
            $CNOA_DB->close( );
        }
        $ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $name = "exsms_linkman_import_".$GLOBALS['CNOA_TIMESTAMP']."_".string::rands( 10, 1 );
        $dst = CNOA_PATH_FILE."/common/temp/".$name.$ext;
        $extArray = array( ".xls", ".xlsx" );
        if ( !in_array( $ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "importFormatNotice" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $dst ) )
        {
            msg::callback( TRUE, $CNOA_XXTEA->encrypt( $name.$ext ) );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function _import_linkman_todb( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        global $CNOA_XXTEA;
        global $CNOA_DB;
        $from = getpar( $_GET, "from", "" );
        $excel_file = CNOA_PATH_FILE."/common/temp/".$CNOA_XXTEA->decrypt( $from );
        $GLOBALS['GLOBALS']['data'] = array( );
        if ( !file_exists( $excel_file ) )
        {
            echo "文件不存在[0x0030010]";
            exit( );
        }
        include( CNOA_PATH_CLASS."/PHPExcel/IOFactory.php" );
        try
        {
            $objReader = PHPExcel_IOFactory::createreader( "Excel5" );
            try
            {
                $objPHPExcel = $objReader->load( $excel_file );
            }
            catch ( Exception $e1 )
            {
                $objReader = PHPExcel_IOFactory::createreader( "excel2007" );
                try
                {
                    $objPHPExcel = $objReader->load( $excel_file );
                }
                catch ( Exception $e1 )
                {
                    echo lang( "wrongExcelFile" )."[0x0030023]";
                    exit( );
                }
            }
        }
        catch ( Exception $e )
        {
            $objReader = PHPExcel_IOFactory::createreader( "excel2007" );
            try
            {
                $objPHPExcel = $objReader->load( $excel_file );
            }
            catch ( Exception $e1 )
            {
                echo lang( "wrongExcelFile" )."[0x0030023]";
                exit( );
            }
        }
        if ( !empty( $objPHPExcel ) )
        {
            try
            {
                $sheet = $objPHPExcel->getSheet( 0 );
                $highestRow = $sheet->getHighestRow( );
                $highestColumn = $sheet->getHighestColumn( );
                $str = "";
                $j = 1;
                for ( ; $j <= $highestRow; ++$j )
                {
                    $GLOBALS['GLOBALS']['totalColum'] = 1;
                    $k = "A";
                    for ( ; $k <= $highestColumn; ++$k )
                    {
                        ++$GLOBALS['totalColum'];
                        $dt = $objPHPExcel->getActiveSheet( )->getCell( "{$k}{$j}" )->getValue( );
                        if ( gettype( $dt ) == "object" )
                        {
                            $GLOBALS['GLOBALS']['data'][$j][$k] = $dt->__toString( );
                        }
                        else
                        {
                            $GLOBALS['GLOBALS']['data'][$j][$k] = $dt;
                        }
                    }
                    echo $str;
                    $str = "";
                    continue;
                }
                catch ( Exception $e )
                {
                    echo lang( "wrongExcelFile" )."[0x0030021]";
                    exit( );
                }
            }
        }
        else
        {
            echo lang( "wrongExcelFile" )."[0x0030022]";
            exit( );
        }
        $GLOBALS['GLOBALS']['groupList'] = $CNOA_DB->db_select( "*", $this->table_lsort, "WHERE 1" );
        if ( !is_array( $GLOBALS['groupList'] ) )
        {
            $GLOBALS['GLOBALS']['groupList'] = array( );
        }
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/exsms/linkman_import_todb.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        @unlink( $excel_file );
        exit( );
    }

    private function _import_linkman_insert( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $uid = $CNOA_SESSION->get( "UID" );
        $flowmanname = $CNOA_SESSION->get( "TRUENAME" );
        $select = getpar( $_POST, "select", array( ) );
        $colum = getpar( $_POST, "colum", array( ) );
        $ck = getpar( $_POST, "ck", array( ) );
        $sid = getpar( $_POST, "sid", 0 );
        $repeats = "已忽略以下手机号码重复项：";
        foreach ( $colum as $k1 => $v1 )
        {
            if ( $ck[$k1] == "on" )
            {
                $data = array( );
                $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $data['sid'] = $sid;
                foreach ( $v1 as $k2 => $v2 )
                {
                    if ( in_array( $select[$k2], array( "name", "mobile" ) ) )
                    {
                        $data[$select[$k2]] = $v2;
                    }
                }
                if ( !empty( $data['mobile'] ) )
                {
                    $isMobileE = $CNOA_DB->db_getfield( "mobile", $this->table_linkman, "WHERE `mobile`='".$data['mobile']."'" );
                    if ( !$isMobileE )
                    {
                        $CNOA_DB->db_insert( $data, $this->table_linkman );
                    }
                    else
                    {
                        $repeats .= addslashes( $data['name']."(".$data['mobile'].")，" );
                    }
                }
            }
        }
        echo "<script>alert('".lang( "importSucess" )."{$repeats}');try{ try{CNOA_communication_email_linkman.store.reload();}catch(e){}parent.CNOA_communication_email_linkman.closeImportToDbWindow();}catch(e){} window.close();</script>";
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    public function api_sendExsms( $entity, $content )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $config = app::loadapp( "main", "exsmsSetting" )->api_getSetting( );
        if ( empty( $config['api_send_url'] ) )
        {
            msg::callback( FALSE, lang( "SMSinterfaceNotSet" ) );
        }
        if ( $config['charset'] != "UTF-8" && $config['charset'] != "" )
        {
            $text = iconv( "UTF-8", $config['charset'], $text );
        }
        $mobiles = $to = array( );
        foreach ( $entity as $v )
        {
            $mobiles[] = $v['mobile'];
            $to[] = array(
                "n" => $v['name'],
                "m" => $v['mobile']
            );
        }
        $mobilesSplite = array_chunk( $mobiles, 100 );
        foreach ( $mobilesSplite as $spv )
        {
            $mobiles = implode( $config['mobilesplite'], $spv );
            $config['api_send_url'] = str_replace( array(
                "@@SENDTIME@@",
                "@@MOBILES@@",
                "@@TEXT@@",
                "@@MKTIME@@"
            ), array(
                "",
                urlencode( $mobiles ),
                urlencode( $content ),
                urlencode( date( $config['timeformat'], $GLOBALS['CNOA_TIMESTAMP'] ) )
            ), $config['api_send_url'] );
            $return = @file_get_contents( $config['api_send_url'] );
            if ( !$return )
            {
                msg::callback( FALSE, lang( "interfaceConnetFail" ) );
            }
        }
        $return = preg_replace( $config['callbackregex'], "\\1", $return );
        $rtrue = FALSE;
        $rmsg = "";
        $statusInfo = $CNOA_DB->db_getone( "*", $this->table_status, "WHERE `code`='".addslashes( $return )."'" );
        if ( !$statusInfo )
        {
            $statusInfo = $CNOA_DB->db_getone( "*", $this->table_status, "WHERE `code`='*'" );
            if ( !$statusInfo )
            {
                $rtrue = FALSE;
                $rmsg = "未知返回状态：[".$return."]";
            }
            else
            {
                $rtrue = TRUE;
                $rmsg = $statusInfo['name'].( "[".$return."]" );
            }
        }
        else
        {
            $rtrue = TRUE;
            $rmsg = $statusInfo['name'].( "[".$return."]" );
        }
        $data['text'] = $content;
        $data['fromuid'] = $CNOA_SESSION->get( "UID" );
        $data['fromname'] = $CNOA_SESSION->get( "TRUENAME" );
        $data['to'] = addslashes( json_encode( $to ) );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['status'] = 1;
        $data['statusText'] = addslashes( $rmsg );
        $CNOA_DB->db_insert( $data, $this->table_outbox );
        return array(
            "status" => $rtrue,
            "msg" => $rmsg
        );
    }

}

?>
