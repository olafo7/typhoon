<?php

class wfFlowMgr extends wfCommon
{

    public function run( )
    {
        $modul = getpar( $_GET, "modul", "" );
        if ( $modul == "list" )
        {
            app::loadapp( "wf", "flowMgrList" )->run( );
        }
    }

}

?>
