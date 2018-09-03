<?php
//decode by qq2859470

class portalsPortalsMy extends portalsPortals
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/my.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            break;
        case "getUserPortalsList" :
            $this->_getUserPortalsList( );
            break;
        case "getUserPortals" :
            $this->_getUserPortals( );
        }
    }

    private function _getUserPortalsList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $user = $CNOA_DB->db_getone( array( "jobId", "deptId" ), "main_user", "WHERE `uid`=".$uid );
        $portalsList = $CNOA_DB->db_select( "*", $this->table_portals, "WHERE 1   ORDER BY `order` DESC" );
        $userPortals = array( );
        if ( !is_array( $portalsList ) )
        {
            $portalsList = array( );
        }
        foreach ( $portalsList as $k => $v )
        {
            $uids = explode( ",", $v['uids'] );
            $deptIds = explode( ",", $v['deptIds'] );
            $jobIds = explode( ",", $v['jobIds'] );
            if ( !in_array( $user['deptId'], $deptIds ) || !in_array( $user['jobId'], $jobIds ) || !in_array( $uid, $uids ) )
            {
                $userPortals[] = $v;
            }
        }
        foreach ( $userPortals as $k => $v )
        {
            if ( !empty( $v['mids'] ) )
            {
                $userPortals[$k]['desktop'] = $CNOA_DB->db_select( "*", $this->table_desktop, "WHERE `id` IN(".$v['mids'].")" );
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $userPortals;
        echo $ds->makeJsonData( );
    }

    private function _getUserPortals( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $mids = getpar( $_GET, "mids" );
        $portalsID = getpar( $_GET, "portalsID" );
        $result = $CNOA_DB->db_select( array( "code" ), $this->table_desktop, "WHERE `id` IN(".$mids.")" );
        $code = array( );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        foreach ( $result as $k => $v )
        {
            $code[$k] = $v['code'];
        }
        $GLOBALS['GLOBALS']['portals']['code'] = json_encode( $code );
        $GLOBALS['GLOBALS']['portals']['portalsID'] = $portalsID;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/portalsDesktop.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

}

?>
