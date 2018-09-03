<?php
//decode by qq2859470

class newsBbs extends model
{

    protected $table_forum = "news_bbs_forum";
    protected $table_post = "news_bbs_post";
    protected $table_reply = "news_bbs_reply";
    protected $table_friendLink = "news_bbs_friendLink";
    protected $table_setting = "news_bbs_setting";
    protected $table_searchHistory = "news_bbs_searchHistory";
    protected $table_user = "main_user";

    public function actionBbs( )
    {
        if ( isset( $_GET['pid'] ) )
        {
            app::loadapp( "news", "bbsView" )->run( );
        }
        else
        {
            app::loadapp( "news", "bbsBbs" )->run( );
        }
    }

    public function actionSetting( )
    {
        app::loadapp( "news", "bbsSetting" )->run( );
    }

    public function actionMypost( )
    {
        app::loadapp( "news", "bbsMypost" )->run( );
    }

    public function actionMyreply( )
    {
        app::loadapp( "news", "bbsMyreply" )->run( );
    }

    protected function api_getForumNameById( $id )
    {
        global $CNOA_DB;
        return $CNOA_DB->db_getfield( "name", $this->table_forum, "WHERE `id`='".$id."'" );
    }

    protected function api_getForumFnameById( $id )
    {
        global $CNOA_DB;
        $fid = $CNOA_DB->db_getfield( "fid", $this->table_forum, "WHERE `id`='".$id."'" );
        return $CNOA_DB->db_getfield( "name", $this->table_forum, "WHERE `id`='".$fid."'" );
    }

    protected function api_getPostTitleById( $id )
    {
        global $CNOA_DB;
        return $CNOA_DB->db_getfield( "title", $this->table_post, "WHERE `id`='".$id."'" );
    }

}

?>
