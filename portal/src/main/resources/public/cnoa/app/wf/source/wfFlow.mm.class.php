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
            app::loadappform( "wf", "FlowUseTodoM" )->run( );
            break;
        case "done" :
            return app::loadappform( "wf", "flowUseDoneM" )->run( );
        case "form" :
            app::loadapp( "wf", "flowUsePhoneMM" )->run( );
            break;
        case "newfree" :
            app::loadappform( "wf", "flowUseNewfreeM" )->run( );
        }
    }

}

?>
