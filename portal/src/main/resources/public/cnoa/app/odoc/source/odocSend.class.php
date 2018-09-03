<?php
//decode by qq2859470

class odocSend extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionApply( )
    {
        app::loadapp( "odoc", "sendApply" )->run( );
    }

    public function actionPass( )
    {
        app::loadapp( "odoc", "sendPass" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "odoc", "sendCheck" )->run( );
    }

    public function actionSign( )
    {
        app::loadapp( "odoc", "sendSign" )->run( );
    }

    public function actionList( )
    {
        app::loadapp( "odoc", "sendList" )->run( );
    }

}

?>
