<?php
//decode by qq2859470

class userDiskM
{

    private $table_list = "user_disk_main";

    public function actionIndex( )
    {
        app::loadappform( "user", "diskIndexM" )->run( );
    }

    public function actionPublic( )
    {
        app::loadappform( "user", "diskPublicM" )->run( );
    }

    public function actionMgrpub( )
    {
        app::loadappform( "user", "diskMgrpubM" )->run( );
    }

    public function actionMgrnet( )
    {
        app::loadappform( "user", "diskMgrnetM" )->run( );
    }

}

?>
