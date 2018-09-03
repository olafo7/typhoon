<?php
//decode by qq2859470

class assetsAssets extends assetsCommon
{

    protected static $export_limit = 2000;

    public function actionAssetsmanagement( )
    {
        app::loadapp( "assets", "assetsAssetsmanagement" )->run( );
    }

    public function actionSecondment( )
    {
        app::loadapp( "assets", "assetsSecondment" )->run( );
    }

    public function actionHistorical( )
    {
        app::loadapp( "assets", "assetsHistorical" )->run( );
    }

}

?>
