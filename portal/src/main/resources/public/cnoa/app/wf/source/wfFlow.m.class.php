<?php

class wfFlowM extends wfCommon
{

    public function actionUse( )
    {
        $module = getpar( $_GET, "modul", "" );
        switch ( $module )
        {
        case "new" :
            app::loadappform( "wf", "flowUseNewM" )->run( );
            break;
        case "todo" :
            app::loadappform( "wf", "flowUseTodoM" )->run( );
            break;
        case "done" :
            return app::loadappform( "wf", "flowUseDoneM" )->run( );
        case "form" :
            global $CNOA_SESSION;
            if ( isclient( $CNOA_SESSION->get( "DEVICE" ) ) )
            {
                header( "Access-Control-Allow-Origin: *" );
                app::loadapp( "wf", "flowUsePhone" )->run( );
            }
            else
            {
                app::loadappform( "wf", "flowUseFormM" )->run( );
            }
        }
    }

}

?>
