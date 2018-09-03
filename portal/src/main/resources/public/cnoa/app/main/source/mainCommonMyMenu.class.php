<?php
//decode by qq2859470

class mainCommonMyMenu extends model
{

    private $table_user_menu = "main_user_menu";

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "loadMenuAll" :
            $this->_loadMenuAll( );
            break;
        case "getMyMenu" :
            $this->_getMyMenu( );
            break;
        case "setMyMenu" :
            $this->_setMyMenu( );
            break;
        case "getMyMenuGrid" :
            $this->_getMyMenuGrid( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/common/setMyMenu.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _loadMenuAll( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $data = app::loadapp( "main", "config" )->api_getMenu( TRUE );
        foreach ( $data as $k1 => $v1 )
        {
            $data[$k1]['allowDrop'] = FALSE;
            $data[$k1]['draggable'] = FALSE;
            $data[$k1]['expanded'] = FALSE;
            unset( $Var_24['checked'] );
            if ( is_array( $v1['children'] ) )
            {
                foreach ( $v1['children'] as $k2 => $v2 )
                {
                    $data[$k1]['children'][$k2]['allowDrop'] = FALSE;
                    $data[$k1]['children'][$k2]['expanded'] = TRUE;
                    if ( $v2['leaf'] )
                    {
                        $data[$k1]['children'][$k2]['draggable'] = TRUE;
                    }
                    else
                    {
                        $data[$k1]['children'][$k2]['draggable'] = FALSE;
                    }
                    if ( is_array( $v2['children'] ) )
                    {
                        foreach ( $v2['children'] as $k3 => $v3 )
                        {
                            $data[$k1]['children'][$k2]['children'][$k3]['allowDrop'] = FALSE;
                            if ( $v3['leaf'] )
                            {
                                $data[$k1]['children'][$k2]['children'][$k3]['draggable'] = TRUE;
                            }
                            else
                            {
                                $data[$k1]['children'][$k2]['children'][$k3]['draggable'] = FALSE;
                            }
                        }
                    }
                }
            }
        }
        echo json_encode( $data );
        exit( );
    }

    private function _setMyMenu( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = json_decode( $_POST['data'], TRUE );
        if ( is_array( $data ) )
        {
            $CNOA_DB->db_delete( $this->table_user_menu, "WHERE `uid`='".$uid."'" );
            foreach ( $data as $k => $v )
            {
                $dv = array(
                    "uid" => $uid,
                    "name" => $v['name'],
                    "language" => $v['language'],
                    "newName" => $v['newName'],
                    "id" => $v['id'],
                    "iconCls" => $v['iconCls'],
                    "autoLoadUrl" => $v['autoLoadUrl'],
                    "clickEvent" => $v['clickEvent'],
                    "order" => $k
                );
                $CNOA_DB->db_insert( $dv, $this->table_user_menu );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _getMyMenu( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = $CNOA_DB->db_select( "*", $this->table_user_menu, "WHERE `uid`='".$uid."' ORDER BY `order` ASC" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        foreach ( $data as $k => $v )
        {
            if ( !empty( $v['newName'] ) )
            {
                $data[$k]['name'] = $v['newName'];
            }
        }
        echo json_encode( $data );
        exit( );
    }

    private function _getMyMenuGrid( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = $CNOA_DB->db_select( "*", $this->table_user_menu, "WHERE `uid`='".$uid."' ORDER BY `order` ASC" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

}

?>
