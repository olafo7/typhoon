<?php
//decode by qq2859470

class flowFlowExportExcel extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function init( $source, $truename, $stime, $etime )
    {
        ( );
        $this->objPHPExcel = new PHPExcel( );
        $this->setProperties( $truename, $stime, $etime );
        $this->addData( $source );
    }

    private function setProperties( $truename, $stime, $etime )
    {
        $this->objPHPExcel->getProperties( )->setCreator( "协众软件" )->setLastModifiedBy( "协众软件" )->setTitle( "协众软件XLS文档" )->setSubject( "协众软件XLS标题" )->setDescription( "协众软件XLS描述" )->setKeywords( "关键词" )->setCategory( "Category" );
        $this->objPHPExcel->setActiveSheetIndex( 0 );
        $this->objPHPExcel->getActiveSheet( )->setTitle( "第一页" );
        $this->objPHPExcel->getActiveSheet( )->mergeCells( "A1:G1" );
        $this->objPHPExcel->getActiveSheet( )->getRowDimension( "1" )->setRowHeight( 30 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "A" )->setWidth( 30 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "B" )->setWidth( 13 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "C" )->setWidth( 13 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "D" )->setWidth( 12 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "E" )->setWidth( 12 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "F" )->setWidth( 12 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "G" )->setWidth( 12 );
        $styleTitle = array(
            "font" => array(
                "bold" => TRUE,
                "color" => array( "argb" => "000000" )
            ),
            "borders" => array(
                "allborders" => array(
                    "style" => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objPHPExcel->getActiveSheet( )->getStyle( "A2:G2" )->applyFromArray( $styleTitle );
        $this->objPHPExcel->getActiveSheet( )->getStyle( "A2:G2" )->getFill( )->setFillType( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor( )->setARGB( "DFDFDFDF" );
        $this->objPHPExcel->getActiveSheet( )->setCellValue( "A1", "工作流程列表 - [发起人:".$truename." / 时间段:".date( "Y年m月d日", $stime )."-".date( "Y年m月d日", $etime )."]" )->setCellValue( "A2", "流程编号" )->setCellValue( "B2", "所属流程" )->setCellValue( "C2", lang( "title" ) )->setCellValue( "D2", "重要等级" )->setCellValue( "E2", "当前步骤" )->setCellValue( "F2", "状态" )->setCellValue( "G2", "发起日期" );
    }

    private function addData( $source )
    {
        if ( !is_array( $source ) )
        {
            $source = array( );
        }
        $i = 3;
        foreach ( $source as $sv )
        {
            $this->objPHPExcel->getActiveSheet( )->setCellValue( "A".$i, $sv['name'] )->setCellValue( "B".$i, $sv['flowname'] )->setCellValue( "C".$i, $sv['title'] )->setCellValue( "D".$i, $sv['level'] )->setCellValue( "E".$i, $sv['step'] )->setCellValue( "F".$i, $sv['statusText'] )->setCellValue( "G".$i, $sv['posttime'] );
            ++$i;
        }
        $styleBody = array(
            "borders" => array(
                "allborders" => array(
                    "style" => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objPHPExcel->getActiveSheet( )->getStyle( "A2:G".--$i )->applyFromArray( $styleBody );
    }

    public function save( $filename )
    {
        $this->objPHPExcel = PHPExcel_IOFactory::createwriter( $this->objPHPExcel, "Excel2007" );
        $this->objPHPExcel->save( $filename );
    }

    public function export( )
    {
        @ini_set( "zlib.output_compression", "Off" );
        header( "Content-Type: application/vnd.ms-excel" );
        header( "Content-Disposition: attachment;filename=\"01simple.xlsx\"" );
        header( "Cache-Control: max-age=0" );
        $this->objPHPExcel = PHPExcel_IOFactory::createwriter( $this->objPHPExcel, "Excel2007" );
        $this->objPHPExcel->save( "php://output" );
        exit( );
    }

}

?>
