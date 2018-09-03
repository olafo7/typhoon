<?php
//decode by qq2859470

class odocRead extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionFile( )
    {
        app::loadapp( "odoc", "readFile" )->run( );
    }

    public function actionReceive( )
    {
        app::loadapp( "odoc", "readReceive" )->run( );
    }

    public function actionSend( )
    {
        app::loadapp( "odoc", "readSend" )->run( );
    }

}

?>
