<?php

class wfFlow extends wfCommon
{

    public function actionSet( )
    {
        app::loadapp( "wf", "flowSet" )->run( );
    }

    public function actionMgr( )
    {
        app::loadapp( "wf", "flowMgr" )->run( );
    }

    public function actionUse( )
    {
        app::loadapp( "wf", "flowUse" )->run( );
    }

    public function actionManager( )
    {
        app::loadapp( "wf", "flowManager" )->run( );
    }

    public function actionTimeout( )
    {
        app::loadapp( "wf", "flowTimeout" )->run( );
    }

    public function actionSetdesktop( )
    {
        app::loadapp( "wf", "flowSetdesktop" )->run( );
    }

    public function actionTaohong( )
    {
        app::loadapp( "wf", "flowUseView" )->run( );
    }

}

?>
