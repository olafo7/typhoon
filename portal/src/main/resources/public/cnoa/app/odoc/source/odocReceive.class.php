<?php
//decode by qq2859470

class odocReceive extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionWaiting( )
    {
        app::loadapp( "odoc", "receiveWaiting" )->run( );
    }

    public function actionApply( )
    {
        app::loadapp( "odoc", "receiveApply" )->run( );
    }

    public function actionHandler( )
    {
        app::loadapp( "odoc", "receiveHandler" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "odoc", "receiveCheck" )->run( );
    }

    public function actionPart( )
    {
        app::loadapp( "odoc", "receivePart" )->run( );
    }

    public function actionUndertake( )
    {
        app::loadapp( "odoc", "receiveUndertake" )->run( );
    }

    public function actionList( )
    {
        app::loadapp( "odoc", "receiveList" )->run( );
    }

}

?>
