<?php

class wfEngineAbutment
{

    public function getBusinessData( $level )
    {
        $flowId = getpar( $_GET, "flowId" );
        if ( empty( $flowId ) )
        {
            return;
        }
        $mapPath = CNOA_PATH_FILE.( "/common/wf/abutment/".$flowId.".map.php" );
        if ( !file_exists( $mapPath ) )
        {
            return FALSE;
        }
        $map = include_once( $mapPath );
        $data = array( );
        foreach ( $map as $key => $value )
        {
            if ( !$value['open'] )
            {
            }
            else
            {
                $bindCheck = $value['check'];
                if ( !array_key_exists( $bindCheck['id'], $data ) )
                {
                    $data[$bindCheck['id']]['data'][] = array(
                        "idea" => $bindCheck['idea'][0]
                    );
                    $data[$bindCheck['id']]['data'][] = array(
                        "idea" => $bindCheck['idea'][1]
                    );
                    $data[$bindCheck['id']]['display'] = "idea";
                    $data[$bindCheck['id']]['value'] = "idea";
                }
            }
        }
        echo json_encode( $data );
    }

}

?>
