<?php
//decode by qq2859470

class userDisk extends model
{

    private $table_list = "user_disk_main";

    public function actionIndex( )
    {
        app::loadapp( "user", "diskIndex" )->run( );
    }

    public function actionPublic( )
    {
        app::loadapp( "user", "diskPublic" )->run( );
    }

    public function actionMgrpub( )
    {
        app::loadapp( "user", "diskMgrpub" )->run( );
    }

    public function actionMgrnet( )
    {
        app::loadapp( "user", "diskMgrnet" )->run( );
    }

}

?>
