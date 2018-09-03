<?php
//decode by qq2859470

class salaryBasicBingding extends salaryBasic
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getSalaryKey" :
            $this->_getSalaryKey( );
            exit( );
        case "updateSalaryKey" :
            $this->_updateSalaryKey( );
            exit( );
        case "synchronization" :
            $this->_synchronization( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/basic/bingding.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getSalaryKey( )
    {
        global $CNOA_DB;
        $start = ( integer )getpar( $_POST, "start" );
        $row = getpagesize( "salary_basic_key" );
        $truename = getpar( $_POST, "truename" );
        $deptId = ( integer )getpar( $_POST, "deptId" );
        $WHERE[] = "WHERE 1";
        if ( !empty( $truename ) )
        {
            $WHERE[] = "u.truename LIKE '%".$truename."%'";
        }
        if ( $deptId !== 0 )
        {
            $WHERE[] = "u.deptId = ".$deptId;
        }
        $WHERE = implode( " AND ", $WHERE );
        $data = array( );
        $sql = "SELECT u.uid, u.truename, d.name AS deptName, s.salaryKey, bs.salary AS basicSalary FROM ".tname( $this->table_main_user )." AS u LEFT JOIN ".tname( $this->table_main_struct )." AS d ON d.id = u.deptId LEFT JOIN ".tname( $this->table_basic_key )." AS s ON s.uid = u.uid LEFT JOIN ".tname( $this->table_basic_basicSalary )." AS bs ON bs.uid = u.uid ".$WHERE.( " ORDER BY d.id LIMIT ".$start.",{$row}" );
        $result = $CNOA_DB->query( $sql );
        while ( $rows = $CNOA_DB->get_array( $result ) )
        {
            $data[] = $rows;
        }
        $WHERE = str_replace( "u.", "", $WHERE );
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->total = $CNOA_DB->db_getcount( $this->table_main_user, $WHERE );
        echo $ds->makeJsonData( );
    }

    private function _updateSalaryKey( )
    {
        global $CNOA_DB;
        $uid = ( integer )getpar( $_POST, "uid" );
        $field = getpar( $_POST, "field" );
        $value = getpar( $_POST, "value" );
        if ( $uid === 0 || $field != "salaryKey" && $field != "basicSalary" )
        {
            msg::callback( FALSE, "数据出错，请刷新页面后重新操作" );
        }
        if ( $field == "salaryKey" )
        {
            $regex = "/^[a-zA-Z0-9]+\$/";
            $msgText = "唯一标识符只能为英文字母和数字的组合，且不能为0";
        }
        if ( $field == "basicSalary" )
        {
            $regex = "/^[0-9]+\\.*[0-9]*\$/";
            $msgText = "请正确输入基本工资";
        }
        if ( !preg_match( $regex, $value ) || $value != 0 && $value != "" )
        {
            msg::callback( FALSE, $msgText );
        }
        if ( !empty( $value ) || $field == "salaryKey" )
        {
            $count = $CNOA_DB->db_getcount( $this->table_basic_key, "WHERE `salaryKey`='".$value."'" );
            if ( 0 < $count )
            {
                msg::callback( FALSE, "标识符不能相同，请重新操作" );
            }
        }
        $data = array( );
        $data['uid'] = $uid;
        if ( $field == "salaryKey" )
        {
            $data['deptId'] = app::loadapp( "main", "user" )->api_getUserDeptIdByUid( $uid );
            $data['salaryKey'] = $value;
            $table = $this->table_basic_key;
        }
        if ( $field == "basicSalary" )
        {
            $data['salary'] = $value;
            $table = $this->table_basic_basicSalary;
        }
        $result = $CNOA_DB->db_getcount( $table, "WHERE `uid`='".$uid."'" );
        if ( 0 < $result )
        {
            $CNOA_DB->db_update( $data, $table, "WHERE uid = ".$uid );
        }
        else
        {
            $CNOA_DB->db_insert( $data, $table );
        }
        msg::callback( TRUE, "操作成功" );
    }

    private function _synchronization( )
    {
        global $CNOA_DB;
        $attResult = $CNOA_DB->db_select( array( "uid", "deptId", "machineId", "importKey" ), "att_mgr_joinatt" );
        if ( !is_array( $attResult ) )
        {
            msg::callback( FALSE, "没有绑定考勤标识符，请绑定后再进行同步" );
        }
        foreach ( $attResult as $attOne )
        {
            $temp = array( );
            $temp['uid'] = $attOne['uid'];
            $temp['deptId'] = $attOne['deptId'];
            if ( empty( $attOne['machineId'] ) )
            {
                $temp['salaryKey'] = 0;
            }
            else
            {
                $temp['salaryKey'] = $attOne['machineId'].$attOne['importKey'];
            }
            $fields = "(`".implode( "`,`", array_keys( $temp ) )."`)";
            $data[] = "('".implode( "','", $temp )."')";
        }
        $sql = "TRUNCATE TABLE ".tname( $this->table_basic_key );
        $CNOA_DB->query( $sql );
        $data = array_chunk( $data, 200 );
        foreach ( $data as $value )
        {
            $sql = "INSERT INTO ".tname( $this->table_basic_key ).$fields." VALUES ".implode( ",", $value );
            $CNOA_DB->query( $sql );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 5000, "薪酬标识符", "同步" );
        msg::callback( TRUE, "同步成功" );
    }

}

?>
