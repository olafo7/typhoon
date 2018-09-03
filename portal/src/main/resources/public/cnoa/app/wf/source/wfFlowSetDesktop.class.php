<?php

class wfFlowSetDesktop extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "editShow" :
            $this->_editShow( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "set" );
        if ( $from == "set" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/desktop.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $sortList = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "faqi", TRUE );
        $sortDesktopList = $CNOA_DB->db_select( "*", $this->t_u_desktop, "WHERE `uid`='".$uid."'" );
        if ( !is_array( $sortDesktopList ) )
        {
            $sortDesktopList = array( );
        }
        $sortDesktopData = array( );
        foreach ( $sortDesktopList as $v )
        {
            $sortDesktopData[$v['sortId']] = $v['show'];
        }
        $sortDataList = array( );
        foreach ( $sortList as $k => $v )
        {
            $sortList[$k]['show'] = $sortDesktopData[$v['sortId']];
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $sortList;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _editShow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $sortId = getpar( $_POST, "sortId", "0" );
        $sortInfo = $CNOA_DB->db_getone( "*", $this->t_set_sort, "WHERE `sortId`='".$sortId."'" );
        $show = $CNOA_DB->db_getfield( "show", $this->t_u_desktop, "WHERE `sortId`='".$sortId."' AND `uid`='{$uid}'" );
        if ( $show === FALSE )
        {
            $CNOA_DB->db_insert( array(
                "show" => 1,
                "uid" => $uid,
                "sortId" => $sortId
            ), $this->t_u_desktop );
        }
        else if ( $show == "0" )
        {
            $CNOA_DB->db_update( array( "show" => 1 ), $this->t_u_desktop, "WHERE `sortId`='".$sortId."' AND `uid`='{$uid}'" );
        }
        else
        {
            $CNOA_DB->db_update( array( "show" => 0 ), $this->t_u_desktop, "WHERE `sortId`='".$sortId."' AND `uid`='{$uid}'" );
        }
        $this->__updateDesktop( );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_deleteSort( $sortId )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $CNOA_DB->db_delete( $this->t_set_sort, "WHERE `sortId`='".$sortId."'" );
        $this->__updateDesktop( );
    }

    private function __updateDesktop( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $app = "wf";
        $js = "desktop_wf_todo_".$sortId.".js";
        $code = "CNOA_MAIN_DESKTOP_WF_SORT_".$sortId;
        desktop::updatedesktopwfapp( );
        $this->api_updateAllDesktopJS( );
        app::loadapp( "main", "common" )->api_clearDesktopCache( );
    }

    public function api_updateAllDesktopJS( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $dList = $CNOA_DB->db_select( "*", $this->t_u_desktop, "WHERE 1" );
        if ( !is_array( $dList ) )
        {
            $dList = array( );
        }
        $jsSourcePath = CNOA_PATH."/app/wf/scripts/";
        $jsTargetPath = CNOA_PATH_FILE."/webcache/";
        if ( $handle = opendir( $jsTargetPath ) )
        {
            while ( FALSE !== ( $file = readdir( $handle ) ) )
            {
                if ( preg_match( "/^desktop_wf_todo_/", $file ) )
                {
                    @unlink( $jsTargetPath.$file );
                }
            }
            closedir( $handle );
        }
        $sortIds = array( );
        foreach ( $dList as $v )
        {
            $sortIds[$v['sortId']] = $v['sortId'];
        }
        if ( 0 < count( $sortIds ) )
        {
            $sortInfos = $CNOA_DB->db_select( array( "sortId", "name" ), $this->t_set_sort, "WHERE `sortId` IN (".implode( ",", $sortIds ).")" );
            if ( !is_array( $sortInfos ) )
            {
                $sortInfos = array( );
            }
            $sortArray = array( );
            foreach ( $sortInfos as $v )
            {
                $sortArray[$v['sortId']] = $v;
            }
            foreach ( $dList as $v )
            {
                $jsFile = file_get_contents( $jsSourcePath."desktop_wf_todo_tpl.js" );
                $js = "desktop_wf_todo_".$v['sortId'].".js";
                $jsFile = str_replace( array(
                    "{SORTID}",
                    "{SORTNAME}"
                ), array(
                    $v['sortId'],
                    $sortArray[$v['sortId']]['name']
                ), $jsFile );
                @file_put_contents( $jsTargetPath.$js, $jsFile );
            }
        }
    }

}

?>
