<?php
//decode by qq2859470

class portalsPortalsSetting extends portalsPortals
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/setting.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "deletePortals" :
            $this->_deletePortals( );
            break;
        case "getImagesList" :
            $this->_getImagesList( );
            break;
        case "getDesktopList" :
            $this->_getDesktopList( );
            break;
        case "getBackgroundColor" :
            $this->_getBackgroundColor( );
            break;
        case "updateOrder" :
            $this->_updateOrder( );
        }
    }

    private function _submit( )
    {
        global $CNOA_DB;
        $data = array( );
        $data['jobIds'] = getpar( $_POST, "jobIds" );
        $data['uids'] = getpar( $_POST, "uids" );
        $data['deptIds'] = getpar( $_POST, "deptIds" );
        $data['portalsName'] = getpar( $_POST, "portalsName" );
        $data['imageID'] = getpar( $_POST, "imageID" );
        $data['bgColor'] = getpar( $_POST, "bgColor", "993300" );
        $data['mids'] = getpar( $_POST, "mids" );
        $edit = getpar( $_POST, "edit" );
        if ( $edit == 1 )
        {
            $id = getpar( $_POST, "id" );
            $CNOA_DB->db_update( $data, $this->table_portals, "WHERE `id`=".$id );
        }
        else
        {
            $CNOA_DB->db_insert( $data, $this->table_portals );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_select( "*", $this->table_portals, "WHERE 1  ORDER BY `order` DESC" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        foreach ( $data as $k => $v )
        {
            if ( !empty( $v['uids'] ) )
            {
                $uids = explode( ",", $v['uids'] );
                $truename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
                $temp = array( );
                foreach ( $truename as $k2 => $v2 )
                {
                    $temp[] = $truename[$v2['uid']]['truename'];
                }
                $data[$k]['people'] = implode( ",", $temp );
            }
            if ( !empty( $v['deptIds'] ) )
            {
                $deptIds = explode( ",", $v['deptIds'] );
                $temp = array( );
                $deptName = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptIds );
                foreach ( $deptName as $k2 => $v2 )
                {
                    $temp[] = $v2;
                }
                $data[$k]['dept'] = implode( ",", $temp );
            }
            if ( !empty( $v['jobIds'] ) )
            {
                $jobIds = explode( ",", $v['jobIds'] );
                $temp = array( );
                $jobName = app::loadapp( "main", "job" )->api_getNamesByIds( $jobIds );
                $temp = array( );
                foreach ( $jobName as $k2 => $v2 )
                {
                    $temp[] = $v2;
                }
                $data[$k]['job'] = implode( ",", $temp );
            }
        }
        ( );
        $ds = new DataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _deletePortals( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids" );
        $CNOA_DB->db_delete( $this->table_portals, "WHERE `id` IN (".$ids.")" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getImagesList( )
    {
        $imagesList = include( CNOA_PATH."/app/portals/source/portalsImagesList.php" );
        ( );
        $ds = new dataStore( );
        $ds->total = count( $imagesList );
        $ds->data = $imagesList;
        echo $ds->makeJsonData( );
    }

    private function _getBackgroundColor( )
    {
        $bgColorList = include( CNOA_PATH."/app/portals/source/portalsBackgroundColor.php" );
        ( );
        $ds = new dataStore( );
        $ds->total = count( $bgColorList );
        $ds->data = $bgColorList;
        echo $ds->makeJsonData( );
    }

    private function _getDesktopList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $where1 = "WHERE `status`=1 AND (`type`='1' OR (`status`=1 AND `type`='0' AND FIND_IN_SET(".$uid.", `users`))) ";
        $where2 = "WHERE `status`=1 ";
        $order = "ORDER BY `posttime` ASC";
        if ( isset( $_GET['portalsID'] ) )
        {
            $mids = $CNOA_DB->db_getfield( "mids", $this->table_portals, "WHERE `id`=".$_GET['portalsID'] );
            if ( !empty( $mids ) )
            {
                $where1 .= "AND (NOT `id` IN(".$mids.")) ";
                $where2 .= "AND `id` IN(".$mids.") ";
            }
        }
        $where1 .= $order;
        $where2 .= $order;
        $step = getpar( $_GET, "step", "2" );
        if ( $step == "1" )
        {
            $desktopList = $CNOA_DB->db_select( array( "id", "name" ), $this->table_desktop, $where1 );
        }
        else
        {
            $desktopList = !empty( $mids ) ? $CNOA_DB->db_select( array( "id", "name" ), $this->table_desktop, $where2 ) : array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $desktopList;
        echo $ds->makeJsonData( );
    }

    private function _updateOrder( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $value = getpar( $_POST, "value" );
        $field = getpar( $_POST, "field" );
        $CNOA_DB->db_update( array(
            $field => $value
        ), $this->table_portals, "WHERE `id`=".$id );
        msg::callback( TRUE, "成功" );
    }

}

?>
