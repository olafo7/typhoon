<?php
//decode by qq2859470

class newsVote
{

    public function actionLaunch( )
    {
        app::loadapp( "news", "voteLaunch" )->run( );
    }

    public function actionRead( )
    {
        app::loadapp( "news", "voteRead" )->run( );
    }

    public function actionManage( )
    {
        app::loadapp( "news", "voteManage" )->run( );
    }

}

?>
