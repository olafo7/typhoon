<?php
//decode by qq2859470

class odocFiles extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionBorrow( )
    {
        app::loadapp( "odoc", "filesBorrow" )->run( );
    }

    public function actionDananmgr( )
    {
        app::loadapp( "odoc", "filesDananmgr" )->run( );
    }

    public function actionClean( )
    {
        app::loadapp( "odoc", "filesClean" )->run( );
    }

    public function actionAnjuanmgr( )
    {
        app::loadapp( "odoc", "filesAnjuanmgr" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "odoc", "filesSetting" )->run( );
    }

    public function actionSearch( )
    {
        app::loadapp( "odoc", "filesSearch" )->run( );
    }

    public function actionBrwchk( )
    {
        app::loadapp( "odoc", "filesBrwchk" )->run( );
    }

}

?>
