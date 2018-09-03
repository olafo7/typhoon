<?php
//decode by qq2859470

class contractSettingNotice extends model
{

    private $tableConfig = "contract_business_setting";
    private $tableTarget = "contract_business_setting_noticeTarget";

    public function run( )
    {
        $task = getpar( $_GET, "task", "notice" );
        switch ( $task )
        {
        case "getTargetList" :
            $this->_getTargetList( );
            break;
        case "editLoadFormDataInfo" :
            $this->_editLoadFormDataInfo( );
            break;
        case "submitFormDataInfo" :
            $this->_submitFormDataInfo( );
            break;
        case "getAllUserListsInPermitDeptTreeAll" :
            $this->_getAllUserListsInPermitDeptTreeAll( );
            break;
        case "getAllJobListForTree" :
            echo app::loadapp( "main", "job" )->api_getAllListForTree( );
            exit( );
        case "addTarget" :
            $this->_addTarget( );
            break;
        case "deleteTarget" :
            $this->_deleteTarget( );
        }
    }

    private function _getTargetList( )
    {
        global $CNOA_DB;
        $dblist = array( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableTarget, "WHERE 1 ORDER BY `type` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = $jids = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( empty( $v['uid'] ) )
            {
                $dblist[$k]['uid'] = "";
            }
            if ( empty( $v['jid'] ) )
            {
                $dblist[$k]['jid'] = "";
            }
            if ( $v['type'] == "u" )
            {
                $uids[] = $v['uid'];
            }
            else
            {
                $jids[] = $v['jid'];
            }
        }
        $userList = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        $jobList = app::loadapp( "main", "job" )->api_getListByJids( $jids );
        foreach ( $dblist as $k => $v )
        {
            if ( $v['type'] == "u" )
            {
                $dblist[$k]['uid'] = $userList[$v['uid']]['truename'];
            }
            else
            {
                $dblist[$k]['jid'] = $jobList[$v['jid']]['name'];
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _editLoadFormDataInfo( )
    {
        global $CNOA_DB;
        $formData = array( );
        $systemInfo = $CNOA_DB->db_getone( "*", $this->tableConfig, "WHERE 1" );
        $formData['notice_enable'] = $systemInfo['notice_enable'];
        $formData['notice_forward'] = $systemInfo['notice_forward'];
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $formData;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _submitFormDataInfo( )
    {
        global $CNOA_DB;
        $tableContract = "contract_business";
        $systemInfo = array( );
        $systemInfo['notice_enable'] = getpar( $_POST, "notice_enable", "" ) == "on" ? 1 : 0;
        $systemInfo['notice_forward'] = intval( getpar( $_POST, "notice_forward", 0 ) );
        $CNOA_DB->db_update( $systemInfo, $this->tableConfig, "WHERE 1" );
        if ( $systemInfo['notice_enable'] == 0 )
        {
            notice::deletenoticebyfrom( 9 );
        }
        else
        {
            $forwardTime = $systemInfo['notice_forward'] * 3600 * 24;
            notice::deletenoticebyfrom( 9 );
            $noticeContractList = $CNOA_DB->db_select( "*", $tableContract, "WHERE `endTime`>=".$GLOBALS['CNOA_TIMESTAMP']." AND `notice`=1" );
            if ( !is_array( $noticeContractList ) )
            {
                $noticeContractList = array( );
            }
            $targetList = $CNOA_DB->db_select( "*", $this->tableTarget, "WHERE `status`!=2" );
            if ( !is_array( $targetList ) )
            {
                $targetList = array( );
            }
            foreach ( $targetList as $tv )
            {
                foreach ( $noticeContractList as $nv )
                {
                    $noticeT = lang( "contractDQreminder" );
                    $noticeC = $nv['name'].lang( "contractExpireNote" );
                    $noticeH = "index.php?app=contract&func=business&action=manage&task=getList&type=viewDetails&ID=".$nv['id'];
                    if ( $tv['type'] == "u" )
                    {
                        notice::add( intval( $tv['uid'] ), $noticeT, $noticeC, $noticeH, $nv['endTime'] - $forwardTime, 9, $nv['id'], 2 );
                    }
                    if ( $tv['type'] == "j" )
                    {
                        notice::add_notice_for_job( intval( $tv['jid'] ), $noticeT, $noticeC, $noticeH, $nv['endTime'] - $forwardTime, 9, $nv['id'], 2 );
                    }
                }
            }
            $CNOA_DB->db_delete( $this->tableTarget, "WHERE `status`=2" );
            $CNOA_DB->db_update( array( "status" => 1 ), $this->tableTarget, "WHERE `status`=0" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getAllUserListsInPermitDeptTreeAll( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _addTarget( )
    {
        global $CNOA_DB;
        $type = getpar( $_POST, "type", "" );
        $uid = getpar( $_POST, "uid", 0 );
        $jid = getpar( $_POST, "jid", 0 );
        if ( $type == "u" && $uid != 0 )
        {
            $info = $CNOA_DB->db_getone( "*", $this->tableTarget, "WHERE `uid`='".$uid."' AND `type`='u'" );
            if ( $info !== FALSE )
            {
                if ( $info['status'] == 2 )
                {
                    $CNOA_DB->db_update( array( "status" => 0 ), $this->tableTarget, "WHERE `uid`='".$uid."' AND `type`='u'" );
                    msg::callback( TRUE, lang( "successopt" ) );
                }
                else
                {
                    msg::callback( FALSE, lang( "isAddUser" ) );
                }
            }
            $CNOA_DB->db_insert( array(
                "uid" => $uid,
                "type" => "u"
            ), $this->tableTarget );
        }
        if ( $type == "j" && $jid != 0 )
        {
            $info = $CNOA_DB->db_getone( "*", $this->tableTarget, "WHERE `jid`='".$jid."' AND `type`='j'" );
            if ( $info !== FALSE )
            {
                if ( $info['status'] == 2 )
                {
                    $CNOA_DB->db_update( array( "status" => 0 ), $this->tableTarget, "WHERE `jid`='".$jid."' AND `type`='j'" );
                    msg::callback( TRUE, lang( "successopt" ) );
                }
                else
                {
                    msg::callback( FALSE, lang( "addPosition" ) );
                }
            }
            $CNOA_DB->db_insert( array(
                "jid" => $jid,
                "type" => "j"
            ), $this->tableTarget );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteTarget( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", NULL );
        $CNOA_DB->db_update( array( "status" => 2 ), $this->tableTarget, "WHERE `id`='".$id."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_setContractNotice( $data, $type )
    {
        global $CNOA_DB;
        if ( $type == "delete" )
        {
            notice::deletenoticebysourceid( $data['id'], 9 );
        }
        else
        {
            $systemInfo = $CNOA_DB->db_getone( "*", $this->tableConfig, "WHERE 1" );
            if ( $systemInfo['notice_enable'] == 1 && $GLOBALS['CNOA_TIMESTAMP'] < $data['endTime'] )
            {
                notice::deletenoticebysourceid( $data['id'], 9 );
                $forwardTime = $systemInfo['notice_forward'] * 3600 * 24;
                $targetList = $CNOA_DB->db_select( "*", $this->tableTarget, "WHERE `status`=1" );
                if ( !is_array( $targetList ) )
                {
                    $targetList = array( );
                }
                foreach ( $targetList as $tv )
                {
                    $noticeT = lang( "contractDQreminder" );
                    $noticeC = $data['name'];
                    $noticeH = "index.php?app=contract&func=business&action=manage&task=getList&type=viewDetails&ID=".$data['id'];
                    if ( $tv['type'] == "u" )
                    {
                        notice::add( intval( $tv['uid'] ), $noticeT, $noticeC, $noticeH, $data['endTime'] - $forwardTime, 9, $data['id'], 2 );
                    }
                    if ( $tv['type'] == "j" )
                    {
                        notice::add_notice_for_job( intval( $tv['jid'] ), $noticeT, $noticeC, $noticeH, $data['endTime'] - $forwardTime, 9, $data['id'], 2 );
                    }
                }
            }
        }
    }

}

?>
