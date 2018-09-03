<?php
//decode by qq2859470

class flowFlow extends model
{

    private $table_sort = "flow_flow_sort";

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionDefault( )
    {
    }

    public function actionSetting( )
    {
        app::loadapp( "flow", "flowSetting" )->run( );
    }

    public function actionfmanger( )
    {
        app::loadapp( "flow", "flowFmanger" )->run( );
    }

    public function actionUser( )
    {
        $module = getpar( $_GET, "module", "" );
        if ( $module == "commonFlow" )
        {
            app::loadapp( "flow", "flowUserCommonFlow" )->run( );
        }
        else
        {
            app::loadapp( "flow", "flowUser" )->run( );
        }
    }

    public function actionManage( )
    {
        app::loadapp( "flow", "flowManage" )->run( );
    }

    public function api_getSortList( )
    {
        app::loadapp( "flow", "flowSetting" )->_getSortList( );
    }

    public function api_flowpreviewLoadData( )
    {
        app::loadapp( "flow", "flowSetting" )->_flowdesignLoadData( );
    }

    public function api_loadFormData( )
    {
        app::loadapp( "flow", "flowSetting" )->_formeditLoadForm( );
    }

}

?>
