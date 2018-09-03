<?php
//decode by qq2859470

class contractBusiness extends model
{

    public function actionNotice( )
    {
        app::loadapp( "contract", "businessNotice" )->run( );
    }

    public function actionCheck( )
    {
        app::loadapp( "contract", "businessCheck" )->run( );
    }

    public function actionManage( )
    {
        app::loadapp( "contract", "businessManage" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "contract", "businessSetting" )->run( );
    }

}

?>
