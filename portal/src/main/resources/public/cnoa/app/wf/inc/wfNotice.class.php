<?php

class wfNotice extends wfCommon
{

    private $flowId = 0;
    private $uFlowId = 0;

    public function __construct( $flowId, $uFlowId )
    {
        $this->flowId = intval( $flowId );
        $this->uFlowId = intval( $uFlowId );
    }

}

?>
