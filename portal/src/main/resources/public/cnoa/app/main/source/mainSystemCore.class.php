<?php
//decode by qq2859470

class mainSystemCore extends model
{

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/system/core.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

}

?>
