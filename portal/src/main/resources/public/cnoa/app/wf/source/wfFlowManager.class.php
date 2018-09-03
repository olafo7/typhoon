<?php

class wfFlowManager extends wfCommon
{

    public function run( )
    {
        $modul = getpar( $_GET, "modul", "" );
        if ( $modul == "trust" )
        {
            app::loadapp( "wf", "flowManagerTrust" )->run( );
        }
    }

}

?>
