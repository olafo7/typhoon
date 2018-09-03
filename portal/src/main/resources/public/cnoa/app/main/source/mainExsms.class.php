<?php
//decode by qq2859470

class mainExsms extends model
{

    public function actionSend( )
    {
        app::loadapp( "main", "exsmsSend" )->run( );
    }

    public function actionSmsmgr( )
    {
        app::loadapp( "main", "exsmsMgr" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "main", "exsmsSetting" )->run( );
    }

}

?>
