<?php
//decode by qq2859470

class admArticles extends model
{

    public function actionInfo( )
    {
        app::loadapp( "adm", "articlesInfo" )->run( );
    }

    public function actionLibrary( )
    {
        app::loadapp( "adm", "articlesLibrary" )->run( );
    }

    public function actionRegister( )
    {
        app::loadapp( "adm", "articlesRegister" )->run( );
    }

    public function actionPersonRegister( )
    {
        app::loadapp( "adm", "articlesPersonRegister" )->run( );
    }

    public function actionTransport( )
    {
        app::loadapp( "adm", "articlesTransport" )->run( );
    }

}

?>
