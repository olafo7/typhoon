<?php
//decode by qq2859470

class docSend extends model
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
        app::loadapp( "doc", "sendApply" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "doc", "sendCheck" )->run( );
    }

    public function actionMonit( )
    {
        app::loadapp( "doc", "sendMonit" )->run( );
    }

    public function actionTome( )
    {
        app::loadapp( "doc", "sendTome" )->run( );
    }

    public function actionMgr( )
    {
        app::loadapp( "doc", "sendMgr" )->run( );
    }

}

?>
