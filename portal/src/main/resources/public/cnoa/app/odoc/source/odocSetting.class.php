<?php
//decode by qq2859470

class odocSetting extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionWord( )
    {
        app::loadapp( "odoc", "settingWord" )->run( );
    }

    public function actionTemplate( )
    {
        app::loadapp( "odoc", "settingTemplate" )->run( );
    }

    public function actionReceive( )
    {
        app::loadapp( "odoc", "settingReceive" )->run( );
    }

    public function actionReceivetemplate( )
    {
        app::loadapp( "odoc", "settingReceivetemplate" )->run( );
    }

    public function actionPermit( )
    {
        app::loadapp( "odoc", "settingPermit" )->run( );
    }

    public function actionFlow( )
    {
        app::loadapp( "odoc", "settingFlow" )->run( );
    }

}

?>
