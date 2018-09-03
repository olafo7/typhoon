<?php
//decode by qq2859470

class assetsBase extends assetsCommon
{

    public function actionBaseset( )
    {
        app::loadapp( "assets", "baseBaseset" )->run( );
    }

    public function actionSortsetting( )
    {
        app::loadapp( "assets", "baseSortsetting" )->run( );
    }

    public function actionNumberset( )
    {
        app::loadapp( "assets", "baseNumberset" )->run( );
    }

    public function actionCustom( )
    {
        app::loadapp( "assets", "baseCustom" )->run( );
    }

    public function actionFix( )
    {
        app::loadapp( "assets", "baseFix" )->run( );
    }

}

?>
