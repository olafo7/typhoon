<?php
//decode by qq2859470

class docReceive extends model
{

    public function __construct( )
    {
        $task = getpar( $_GET, "task", "" );
        if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        }
    }

    public function __destruct( )
    {
    }

    public function actionApply( )
    {
        app::loadapp( "doc", "receiveApply" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "doc", "receiveCheck" )->run( );
    }

    public function actionMonit( )
    {
        app::loadapp( "doc", "receiveMonit" )->run( );
    }

    public function actionTome( )
    {
        app::loadapp( "doc", "receiveTome" )->run( );
    }

    public function actionMgr( )
    {
        app::loadapp( "doc", "receiveMgr" )->run( );
    }

}

?>
