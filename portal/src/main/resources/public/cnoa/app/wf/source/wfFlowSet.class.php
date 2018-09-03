<?php

class wfFlowSet extends wfCommon
{

    public function run( )
    {
        $modul = getpar( $_GET, "modul", "" );
        if ( $modul == "sort" )
        {
            app::loadapp( "wf", "flowSetSort" )->run( );
        }
        else if ( $modul == "flow" )
        {
            app::loadapp( "wf", "flowSetFlow" )->run( );
        }
        else if ( $modul == "form" )
        {
            app::loadapp( "wf", "flowSetForm" )->run( );
        }
        else if ( $modul == "step" )
        {
            app::loadapp( "wf", "flowSetStep" )->run( );
        }
        else
        {
            msg::callback( FALSE, "没有设置modul参数" );
        }
    }

}

?>
