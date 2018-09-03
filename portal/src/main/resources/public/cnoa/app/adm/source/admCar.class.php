<?php
//decode by qq2859470

class admCar extends model
{

    public function actionInfo( )
    {
        app::loadapp( "adm", "carInfo" )->run( );
    }

    public function actionAppointment( )
    {
        app::loadapp( "adm", "carAppointment" )->run( );
    }

    public function actionApply( )
    {
        app::loadapp( "adm", "carApply" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "adm", "carCheck" )->run( );
    }

    public function actionRecord( )
    {
        app::loadapp( "adm", "carRecord" )->run( );
    }

    public function actionTransport( )
    {
        app::loadapp( "adm", "carTransport" )->run( );
    }

    public function actionUserecord( )
    {
        app::loadapp( "adm", "carUserecord" )->run( );
    }

    public function actionSort( )
    {
        app::loadapp( "adm", "carSort" )->run( );
    }

    public function actionDriver( )
    {
        app::loadapp( "adm", "carDriver" )->run( );
    }

}

?>
