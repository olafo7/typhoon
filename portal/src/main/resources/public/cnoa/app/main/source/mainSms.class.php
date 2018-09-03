<?php
//decode by qq2859470

class mainSms extends model
{

    public function actionSend( )
    {
        app::loadapp( "main", "smsSend" )->run( );
    }

    public function actionSmsmgr( )
    {
        app::loadapp( "main", "smsMgr" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "main", "smsSetting" )->run( );
    }

    public function api_sendSms( $mobile, $msg, $time = 0 )
    {
        return TRUE;
    }

}

?>
