<?php
//decode by qq2859470

class assetsBaseNumberset extends assetsBase
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadpage( );
            exit( );
        case "edit" :
            $this->_edit( );
            exit( );
        case "getSortList" :
            $this->_getSortList( );
        }
        exit( );
    }

    private function _loadpage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/numberset.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _edit( )
    {
        global $CNOA_DB;
        $data['num'] = getpar( $_POST, "num" );
        $data['zimu'] = getpar( $_POST, "zimu" );
        $data['fuhao'] = getpar( $_POST, "fuhao" );
        $data['nowNum'] = getpar( $_POST, "nowNum" );
        $data['status'] = getpar( $_POST, "status" );
        $data['zimu_check'] = getpar( $_POST, "zimu_check" );
        $data['fuhao_check'] = getpar( $_POST, "fuhao_check" );
        $data['zimu_check'] == on ? $data['zimu_check'] = 1 : $data['zimu_check'] = 0;
        $data['fuhao_check'] == on ? $data['fuhao_check'] = 1 : $data['fuhao_check'] = 0;
        $numlist = $CNOA_DB->db_getone( "*", $this->table_number );
        $zimu = $numlist['zimu'] == $data['zimu'];
        $fuhao = $numlist['fuhao'] == $data['fuhao'];
        $nowNum = $data['nowNum'] < $numlist['nowNum'];
        if ( $zimu && $fuhao && $nowNum )
        {
            msg::callback( FALSE, lang( "curNumLessThan" ) );
        }
        $result = $CNOA_DB->db_getcount( $this->table_number );
        if ( 0 < $result )
        {
            $data = $CNOA_DB->db_update( $data, $this->table_number, "where 1" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6003, "", "编号设置" );
        }
        else
        {
            $data = $CNOA_DB->db_insert( $data, $this->table_number );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 6003, "", "编号设置" );
        }
    }

    private function _getSortList( )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( "*", $this->table_number, "where 1" );
        if ( $data['status'] == 1 )
        {
            if ( $data['nowNum'] != $data['nowNum'] )
            {
                $data['nowNum'] = $data['nowNum'] + 1;
            }
            $nowleng = strlen( $data['nowNum'] );
            $numleng = strlen( $data['num'] );
            $num = substr( $data['num'], $nowleng, $numleng );
            $shownum = $num.$data['nowNum'];
            if ( $data['fuhao_check'] == 0 && $data['zimu_check'] == 0 )
            {
                $data['numShow'] = $shownum;
            }
            else if ( $data['fuhao_check'] == 0 )
            {
                $data['numShow'] = $data['zimu'].$shownum;
            }
            else if ( $data['zimu_check'] == 0 )
            {
                $data['numShow'] = $data['fuhao'].$shownum;
            }
            else
            {
                $data['numShow'] = $data['zimu'].$data['fuhao'].$shownum;
            }
        }
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
