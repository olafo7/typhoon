<?php
//decode by qq2859470

class assetsBaseFix extends assetsBase
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadpage( );
            exit( );
        case "getFix" :
            $this->_getfix( );
            exit( );
        case "editFix" :
            $this->_editfix( );
            exit( );
        case "order" :
            $this->_order( );
        }
        exit( );
    }

    private function _loadpage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/fix.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    public function _getfix( )
    {
        global $CNOA_DB;
        $fieldid = getpar( $_POST, "fixid" );
        $fieldidname = getpar( $_POST, "fixname" );
        $valuefieldid = getpar( $_POST, "valuefieldid" );
        $add = getpar( $_POST, "add" );
        $show = getpar( $_POST, "show" );
        $result = $CNOA_DB->db_select( "*", $this->table_custom_fix, "WHERE 1 ORDER BY `fixid` ASC" );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        $temp = array( );
        $arr = array( "cnum", "price", "assetsNum", "cost", "useyear", "residuals", "depreciationgold" );
        foreach ( $result as $key => $value )
        {
            if ( in_array( $value['displayfname'], $arr ) )
            {
                unset( $value );
            }
            else
            {
                $temp[] = $value;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $temp;
        echo $ds->makeJsonData( );
    }

    private function _editfix( )
    {
        global $CNOA_DB;
        $fixname = getpar( $_POST, "fixname" );
        $fixid = getpar( $_POST, "fixid" );
        $value = getpar( $_POST, "value" );
        $sql = "UPDATE ".tname( $this->table_custom_fix )." SET `".$fixname."`='".$value."' WHERE `fixid` = ".$fixid;
        if ( $result = $CNOA_DB->query( $sql ) )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6004, "", "自定义字段" );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function _order( )
    {
        global $CNOA_DB;
        $fixid = getpar( $_POST, "fixid" );
        $value = getpar( $_POST, "value" );
        $sql = "UPDATE ".tname( $this->table_custom_fix )." SET `order`='".$value."' WHERE `fixid` = ".$fixid;
        if ( $result = $CNOA_DB->query( $sql ) )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6004, "", "自定义字段" );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

}

?>
