<?php
//decode by qq2859470

class salaryBasic extends salaryCommon
{

    public function actionPerson( )
    {
        app::loadapp( "salary", "basicPerson" )->run( );
    }

    public function actionInsure( )
    {
        app::loadapp( "salary", "basicInsure" )->run( );
    }

    public function actionSet( )
    {
        app::loadapp( "salary", "basicSet" )->run( );
    }

    public function actionCardinal( )
    {
        app::loadapp( "salary", "basicCardinal" )->run( );
    }

    public function actionWeal( )
    {
        app::loadapp( "salary", "basicWeal" )->run( );
    }

    public function actionBingding( )
    {
        app::loadapp( "salary", "basicBingding" )->run( );
    }

}

?>
