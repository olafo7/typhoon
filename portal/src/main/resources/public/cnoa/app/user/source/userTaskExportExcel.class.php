<?php
//decode by qq2859470

class userTaskExportExcel extends model
{

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
        $this->objPHPExcel->getActiveSheet( )->setTitle( lang( "firstPage" ) );
        $this->objPHPExcel->getActiveSheet( )->mergeCells( "A1:F1" );
        $this->objPHPExcel->getActiveSheet( )->getRowDimension( "1" )->setRowHeight( 30 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "A" )->setWidth( 30 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "B" )->setWidth( 13 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "C" )->setWidth( 13 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "D" )->setWidth( 12 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "E" )->setWidth( 12 );
        $this->objPHPExcel->getActiveSheet( )->getColumnDimension( "F" )->setWidth( 12 );
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
        $this->objPHPExcel->getActiveSheet( )->getStyle( "A2:F2" )->applyFromArray( $styleTitle );
        $this->objPHPExcel->getActiveSheet( )->getStyle( "A2:F2" )->getFill( )->setFillType( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor( )->setARGB( "DFDFDFDF" );
        $this->objPHPExcel->getActiveSheet( )->setCellValue( "A1", lang( "taskList" )." - [".lang( "principal" ).":".$truename." / ".lang( "timeQuantum" ).":".date( lang( "ymd" ), $stime )."-".date( lang( "ymd" ), $etime )."]" )->setCellValue( "A2", lang( "taskTitle" ) )->setCellValue( "B2", lang( "status" ) )->setCellValue( "C2", lang( "principal" ) )->setCellValue( "D2", lang( "bzr" ) )->setCellValue( "E2", lang( "startTime" ) )->setCellValue( "F2", lang( "endTime" ) );
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
            $this->objPHPExcel->getActiveSheet( )->setCellValue( "A".$i, $sv['title'] )->setCellValue( "B".$i, $sv['statusText'] )->setCellValue( "C".$i, $sv['execman'] )->setCellValue( "D".$i, $sv['postter'] )->setCellValue( "E".$i, $sv['stime'] )->setCellValue( "F".$i, $sv['etime'] );
            ++$i;
        }
        $styleBody = array(
            "borders" => array(
                "allborders" => array(
                    "style" => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objPHPExcel->getActiveSheet( )->getStyle( "A2:F".--$i )->applyFromArray( $styleBody );
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
