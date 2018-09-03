<?php
//decode by qq2859470

class reportForm extends model
{

    public function actionUse( )
    {
        app::loadapp( "report", "formUse" )->run( );
    }

    public function actionView( )
    {
        app::loadapp( "report", "formView" )->run( );
    }

    public function actionMgr( )
    {
        app::loadapp( "report", "formMgr" )->run( );
    }

}

?>
