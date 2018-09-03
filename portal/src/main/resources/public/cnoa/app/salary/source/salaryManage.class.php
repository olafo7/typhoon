<?php
//decode by qq2859470

class salaryManage extends salaryCommon
{

    public function actionEntering( )
    {
        app::loadapp( "salary", "manageEntering" )->run( );
    }

    public function actionDetail( )
    {
        app::loadapp( "salary", "manageDetail" )->run( );
    }

    public function actionCount( )
    {
        app::loadapp( "salary", "managecount" )->run( );
    }

    public function actionMysalary( )
    {
        app::loadapp( "salary", "manageMysalary" )->run( );
    }

    public function actionApprove( )
    {
        app::loadapp( "salary", "manageApprove" )->run( );
    }

}

?>
