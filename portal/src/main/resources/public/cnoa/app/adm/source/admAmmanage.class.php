<?php
//decode by qq2859470

class admAmmanage extends model
{

    public function actionSet( )
    {
        app::loadapp( "adm", "ammanageSet" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "adm", "ammanageCheck" )->run( );
    }

    public function actionManage( )
    {
        app::loadapp( "adm", "ammanageManage" )->run( );
    }

    public function actiondep( )
    {
    }

}

?>
