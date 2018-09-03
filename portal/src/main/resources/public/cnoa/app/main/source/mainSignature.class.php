<?php
//decode by qq2859470

class mainSignature extends model
{

    public function actionIndex( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        $model = getpar( $_GET, "model", "" );
        switch ( $model )
        {
        case "graph" :
            app::loadapp( "main", "signatureGraph" )->run( );
            break;
        case "circle" :
            app::loadapp( "main", "signatureCircle" )->run( );
        }
    }

}

?>
