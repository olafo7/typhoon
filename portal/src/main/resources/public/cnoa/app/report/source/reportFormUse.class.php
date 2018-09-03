<?php
//decode by qq2859470

class reportFormUse extends model
{

    public function run( )
    {
        $modul = getpar( $_GET, "modul", "" );
        switch ( $modul )
        {
        case "wf" :
            app::loadapp( "report", "formUseWf" )->run( );
            break;
        case "temple" :
            app::loadapp( "report", "formUseTemple" )->run( );
        }
    }

}

?>
