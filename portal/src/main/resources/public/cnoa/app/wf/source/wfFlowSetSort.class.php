<?php

class wfFlowSetSort extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task", "" ) );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "submitOrder" :
            $this->_submitOrder( );
            break;
        case "submit" :
            $this->_submit( );
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "delete" :
            $this->_delete( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", getpar( $_POST, "from", "list" ) );
        switch ( $from )
        {
        case "list" :
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/sort.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $WHERE = "WHERE 1 ";
        $s_name = getpar( $_POST, "sname", "" );
        if ( !empty( $s_name ) )
        {
            $WHERE .= "AND `name` LIKE '%".$s_name."%' ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, $WHERE.( "ORDER BY `order` LIMIT ".$start.", {$rows} " ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        $ds->total = $CNOA_DB->db_getcount( $this->t_set_sort, $WHERE );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submitOrder( )
    {
        global $CNOA_DB;
        $sortId = getpar( $_POST, "sortId", 0 );
        $order = getpar( $_POST, "order", "" );
        $CNOA_DB->db_update( array(
            "order" => $order
        ), $this->t_set_sort, "WHERE `sortId` = '".$sortId."' " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        $data['name'] = getpar( $_POST, "name", "" );
        $data['note'] = getpar( $_POST, "note", "" );
        $faqiIds = getpar( $_POST, "faqiPermit", 0 );
        $chayueIds = getpar( $_POST, "chayuePermit", 0 );
        $guanliIds = getpar( $_POST, "guanliPermit", 0 );
        $sortId = getpar( $_POST, "sortId", 0 );
        $forbid = getpar( $_POST, "forbidFaqi" );
        $num = $CNOA_DB->db_getcount( $this->t_set_sort, "WHERE `name` = '".$data['name']."' AND `sortId` != '{$sortId}' " );
        if ( 0 < $num )
        {
            msg::callback( FALSE, lang( "beenAroundPleaseChange" ) );
        }
        if ( empty( $sortId ) )
        {
            $sortId = $CNOA_DB->db_insert( $data, $this->t_set_sort );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3661, $data['name'], lang( "flowClassification" ) );
        }
        else
        {
            $CNOA_DB->db_update( $data, $this->t_set_sort, "WHERE `sortId` = '".$sortId."' " );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3661, $data['name'], lang( "flowClassification" ) );
        }
        $CNOA_DB->db_delete( $this->t_set_sort_permit, "WHERE `sortId` = '".$sortId."' " );
        $CNOA_DB->db_delete( $this->t_set_sort_forbid, "WHERE `sortId` = '".$sortId."' " );
        $insert['sortId'] = $sortId;
        $insert['from'] = "f";
        if ( empty( $faqiIds ) )
        {
            $insert['type'] = "n";
            $insert['permitId'] = 0;
            $CNOA_DB->db_insert( $insert, $this->t_set_sort_permit, FALSE );
        }
        else
        {
            $faqiArr = explode( ",", $faqiIds );
            foreach ( $faqiArr as $v )
            {
                $ex_faqiArr = explode( "-", $v );
                if ( !empty( $ex_faqiArr[1] ) )
                {
                    $insert['type'] = $ex_faqiArr[0];
                    $insert['permitId'] = $ex_faqiArr[1];
                    $CNOA_DB->db_insert( $insert, $this->t_set_sort_permit, FALSE );
                }
            }
        }
        $insert['from'] = "c";
        if ( empty( $chayueIds ) )
        {
            $insert['type'] = "n";
            $insert['permitId'] = 0;
            $CNOA_DB->db_insert( $insert, $this->t_set_sort_permit, FALSE );
        }
        else
        {
            $chayueArr = explode( ",", $chayueIds );
            foreach ( $chayueArr as $v )
            {
                $ex_chayueArr = explode( "-", $v );
                if ( !empty( $ex_chayueArr[1] ) )
                {
                    $insert['type'] = $ex_chayueArr[0];
                    $insert['permitId'] = $ex_chayueArr[1];
                    $CNOA_DB->db_insert( $insert, $this->t_set_sort_permit, FALSE );
                }
            }
        }
        $guanliArr = explode( ",", $guanliIds );
        $insert['from'] = "g";
        foreach ( $guanliArr as $v )
        {
            $ex_guanliArr = explode( "-", $v );
            if ( !empty( $ex_guanliArr[1] ) )
            {
                $insert['type'] = $ex_guanliArr[0];
                $insert['permitId'] = $ex_guanliArr[1];
                $CNOA_DB->db_insert( $insert, $this->t_set_sort_permit, FALSE );
            }
        }
        if ( !empty( $forbid ) )
        {
            $insert2['sortId'] = $sortId;
            $forbid = explode( ",", $forbid );
            foreach ( $forbid as $v )
            {
                $ex_forbidArr = explode( "-", $v );
                if ( !empty( $ex_forbidArr[1] ) )
                {
                    $insert2['type'] = $ex_forbidArr[0];
                    $insert2['permitId'] = $ex_forbidArr[1];
                    $CNOA_DB->db_insert( $insert2, $this->t_set_sort_forbid, FALSE );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        $sortId = getpar( $_POST, "sortId", 0 );
        $data = $CNOA_DB->db_getone( "*", $this->t_set_sort, "WHERE `sortId` = '".$sortId."' " );
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort_permit, "WHERE `sortId` = '".$sortId."' ORDER BY `type` " );
        $forbidList = $CNOA_DB->db_select( "*", $this->t_set_sort_forbid, "WHERE `sortId` = '".$sortId."' ORDER BY `type` " );
        if ( !empty( $dblist ) )
        {
            $deptArr = array( 0 );
            $struArr = array( 0 );
            $peopArr = array( 0 );
            foreach ( $dblist as $k => $v )
            {
                if ( $v['type'] == "d" )
                {
                    $deptArr[] = $v['permitId'];
                }
                else if ( $v['type'] == "s" )
                {
                    $struArr[] = $v['permitId'];
                }
                else if ( $v['type'] == "p" )
                {
                    $peopArr[] = $v['permitId'];
                }
            }
            $deptDB = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptArr );
            $structDB = app::loadapp( "main", "station" )->api_getNamesByIds( $struArr );
            $truenameDB = app::loadapp( "main", "user" )->api_getUserNamesByUids( $peopArr );
            foreach ( $dblist as $k => $v )
            {
                if ( $v['from'] == "f" )
                {
                    if ( $v['type'] != "n" )
                    {
                        $data['faqiPermit'][] = $this->__loadForm_FormatPermit( $v, $deptDB, $structDB, $truenameDB );
                    }
                }
                else if ( $v['from'] == "c" )
                {
                    if ( $v['type'] != "n" )
                    {
                        $data['chayuePermit'][] = $this->__loadForm_FormatPermit( $v, $deptDB, $structDB, $truenameDB );
                    }
                }
                else if ( $v['from'] == "g" )
                {
                    $data['guanliPermit'][] = $this->__loadForm_FormatPermit( $v, $deptDB, $structDB, $truenameDB );
                }
            }
            if ( !is_array( $data['faqiPermit'] ) )
            {
                $data['faqiPermit'] = array( );
            }
            if ( !is_array( $data['chayuePermit'] ) )
            {
                $data['chayuePermit'] = array( );
            }
        }
        if ( !empty( $forbidList ) )
        {
            $forbidDept = array( 0 );
            $forbidStru = array( 0 );
            $forbidPeop = array( 0 );
            foreach ( $forbidList as $k => $v )
            {
                if ( $v['type'] == "d" )
                {
                    $forbidDept[] = $v['permitId'];
                }
                else if ( $v['type'] == "s" )
                {
                    $forbidStru[] = $v['permitId'];
                }
                else if ( $v['type'] == "p" )
                {
                    $forbidPeop[] = $v['permitId'];
                }
            }
            $deptDB2 = app::loadapp( "main", "struct" )->api_getNamesByIds( $forbidDept );
            $structDB2 = app::loadapp( "main", "station" )->api_getNamesByIds( $forbidStru );
            $truenameDB2 = app::loadapp( "main", "user" )->api_getUserNamesByUids( $forbidPeop );
            foreach ( $forbidList as $k => $v )
            {
                $data['forbidFaqi'][] = $this->__loadForm_FormatPermit( $v, $deptDB2, $structDB2, $truenameDB2 );
            }
            if ( !is_array( $data['forbidFaqi'] ) )
            {
                $data['faqiPermit'] = array( );
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function __loadForm_FormatPermit( $data, $deptDB, $structDB, $truenameDB )
    {
        switch ( $data['type'] )
        {
        case "d" :
            return array(
                "id" => "d-".$data['permitId'],
                "text" => "(部门)".$deptDB[$data['permitId']]
            );
        case "s" :
            return array(
                "id" => "s-".$data['permitId'],
                "text" => "(岗位)".$structDB[$data['permitId']]
            );
        case "p" :
            return array(
                "id" => "p-".$data['permitId'],
                "text" => "(人员)".$truenameDB[$data['permitId']]['truename']
            );
        }
    }

    private function _delete( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", 0 );
        $idArr = explode( ",", $ids );
        foreach ( $idArr as $v )
        {
            $sortName = $CNOA_DB->db_getfield( "name", $this->t_set_sort, "WHERE `sortId` = '".$v."' " );
            if ( !empty( $sortName ) )
            {
                app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3661, $sortName, lang( "flowClassification" ) );
            }
            $CNOA_DB->db_delete( $this->t_set_sort, "WHERE `sortId` = '".$v."' " );
            $flowDB = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_flow, "WHERE `sortId` = '".$v."' " );
            if ( !is_array( $flowDB ) )
            {
                $flowDB = array( );
            }
            foreach ( $flowDB as $flowId )
            {
                app::loadapp( "wf", "flowSetFlow" )->api_deleteDesignFlow( $flowId, TRUE );
            }
            $CNOA_DB->db_delete( $this->t_set_sort_permit, "WHERE `sortId` = '".$v."' " );
            $CNOA_DB->db_delete( $this->t_set_sort_forbid, "WHERE `sortId` = '".$v."' " );
            app::loadapp( "wf", "flowSetDesktop" )->api_deleteSort( $v );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getSortDB( $from, $uid = 0, $did = 0, $sid = 0 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( empty( $uid ) )
        {
            $uid = $CNOA_SESSION->get( "UID" );
        }
        if ( empty( $did ) )
        {
            $did = $CNOA_SESSION->get( "DID" );
        }
        if ( empty( $sid ) )
        {
            $sid = $CNOA_SESSION->get( "SID" );
        }
        if ( $from == "faqi" )
        {
            $WHERE = "WHERE `from` = 'f' ";
        }
        else if ( $from == "chayue" )
        {
            $WHERE = "WHERE `from` = 'c' ";
        }
        else if ( $from == "guanli" )
        {
            $WHERE = "WHERE `from` = 'g' ";
        }
        else
        {
            if ( $from == "chayue&guanli" )
            {
                $WHERE = "WHERE (`from` = 'g' OR `from` = 'c') ";
            }
            else if ( $from == "all" )
            {
                $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE 1 ORDER BY `order`" );
                if ( !is_array( $dblist ) )
                {
                    $dblist = array( );
                }
                return $dblist;
            }
            else
            {
                return array( );
            }
        }
        if ( $from == "faqi" )
        {
            $permit = $CNOA_DB->db_select( "*", $this->t_set_sort_permit, $WHERE.( "AND ((`type` = 'p' AND `permitId` = '".$uid."' ) OR (`type` = 's' AND `permitId` = '{$sid}' ) OR (`type` = 'd' AND `permitId` = '{$did}' ) OR (`type` = 'n')) GROUP BY `sortId` " ) );
        }
        else
        {
            $permit = $CNOA_DB->db_select( "*", $this->t_set_sort_permit, $WHERE.( "AND ((`type` = 'p' AND `permitId` = '".$uid."' ) OR (`type` = 's' AND `permitId` = '{$sid}' ) OR (`type` = 'd' AND `permitId` = '{$did}' )) GROUP BY `sortId` " ) );
        }
        if ( !is_array( $permit ) )
        {
            $permit = array( );
        }
        $sortIdArr = array( 0 );
        foreach ( $permit as $k => $v )
        {
            $forbidFaqi = $CNOA_DB->db_select( "*", $this->t_set_sort_forbid, "WHERE `sortId`=".$v['sortId']." AND ((`type` = 'p' AND `permitId` = '{$uid}' ) OR (`type` = 's' AND `permitId` = '{$sid}' ) OR (`type` = 'd' AND `permitId` = '{$did}' )) GROUP BY `sortId` " );
            if ( empty( $forbidFaqi ) )
            {
                $sortIdArr[] = $v['sortId'];
            }
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE `sortId` IN (".implode( ",", $sortIdArr ).") ORDER BY `order` " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getSortTree( $from, $returnArray = FALSE )
    {
        global $CNOA_DB;
        $dblist = $this->api_getSortDB( $from );
        $list = array( );
        foreach ( $dblist as $v )
        {
            $r = array( );
            $r['text'] = $v['name'];
            $r['type'] = $v['name'];
            $r['sortId'] = $v['sortId'];
            $r['iconCls'] = "icon-style-page-key";
            $r['leaf'] = TRUE;
            $r['href'] = "javascript:void(0);";
            $list[] = $r;
        }
        if ( $returnArray )
        {
            return $list;
        }
        echo json_encode( $list );
        exit( );
    }

    public function api_getSortData( $params )
    {
        global $CNOA_DB;
        if ( empty( $params['sortIdArr'] ) )
        {
            return array( );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE `sortId` IN (".implode( ",", $params['sortIdArr'] ).")" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['sortId']] = $v;
        }
        return $data;
    }

    public function api_getSortList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE 1" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['sortId']] = $v;
        }
        return $data;
    }

}

?>
