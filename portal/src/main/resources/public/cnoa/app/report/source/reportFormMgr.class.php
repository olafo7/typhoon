<?php
//decode by qq2859470

class reportFormMgr extends model
{

    public function run( )
    {
        $modul = getpar( $_GET, "modul", "" );
        switch ( $modul )
        {
        case "wf" :
            app::loadapp( "report", "formMgrWf" )->run( );
        }
    }

}

?>
