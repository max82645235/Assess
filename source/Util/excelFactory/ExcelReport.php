<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-28
 * Time: 上午10:11
 */
require_once BATH_PATH."source/PHPExcel.php";
class ExcelReport{
    private $_phpexcel;
    public function __construct(PHPExcel $phpExcel){
        $this->_phpexcel = $phpExcel;
    }

    public function getReportExcel($excelDataList){
        $objPHPExcel = $this->_phpexcel;
        $objPHPExcel
            ->getProperties()  //获得文件属性对象，给下文提供设置资源
            ->setCreator( "Maarten Balliauw")                 //设置文件的创建者
            ->setLastModifiedBy( "Maarten Balliauw")          //设置最后修改者
            ->setTitle( "Office 2007 XLSX Test Document" )    //设置标题
            ->setSubject( "Office 2007 XLSX Test Document" )  //设置主题
            ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //设置备注
            ->setKeywords( "office 2007 openxml php")        //设置标记
            ->setCategory( "Test result file");                //设置类别
        $objPHPExcel->setActiveSheetIndex(0)             //设置第一个内置表（一个xls文件里可以有多个表）为活动的
            ->setCellValue( 'A1', gbkToUtf('姓名'))
            ->setCellValue( 'B1', gbkToUtf('编号'))
            ->setCellValue( 'C1', gbkToUtf('部门'))
            ->setCellValue( 'D1', gbkToUtf('考核名称'))
            ->setCellValue( 'E1', gbkToUtf('频率'))
            ->setCellValue( 'F1', gbkToUtf('考核时间'))
            ->setCellValue( 'G1', gbkToUtf('考核类型'))
            ->setCellValue( 'H1', gbkToUtf('评分'))
            ->setCellValue( 'I1', gbkToUtf('奖惩'));

        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth('16');
        $objActSheet->getColumnDimension('B')->setWidth('16');
        $objActSheet->getColumnDimension('C')->setWidth('30');
        $objActSheet->getColumnDimension('D')->setWidth('25');
        $objActSheet->getColumnDimension('E')->setWidth('25');
        $objActSheet->getColumnDimension('F')->setWidth('30');
        $objActSheet->getColumnDimension('G')->setWidth('40');
        $objActSheet->getColumnDimension('H')->setWidth('16');
        $objActSheet->getColumnDimension('I')->setWidth('25');
        $objActSheet->getStyle( 'A1:I1')->getFont()->setBold(true);
        $objActSheet->getStyle( 'A1:I1')->getFont()->setSize(10);

        $objActSheet =  $objPHPExcel->setActiveSheetIndex(0);
        $styleThinBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    'color' => array ('argb' => 'FF000000'),          //设置border颜色
                ),
            ),
        );
        $rowIndex = 2;
        foreach($excelDataList as $excelData){
            $objActSheet->setCellValue( 'A'.$rowIndex, gbkToUtf($excelData['username']))->getStyle('A'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'B'.$rowIndex, gbkToUtf($excelData['card_no']))->getStyle('B'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'C'.$rowIndex, gbkToUtf($excelData['depart']))->getStyle('C'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'D'.$rowIndex, gbkToUtf($excelData['basename']))->getStyle('D'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'E'.$rowIndex, gbkToUtf($excelData['assess_period_type']))->getStyle('E'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'F'.$rowIndex, gbkToUtf($excelData['time']))->getStyle('F'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'G'.$rowIndex, gbkToUtf($excelData['assess_attr_type']))->getStyle('G'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'H'.$rowIndex, gbkToUtf($excelData['score']))->getStyle('H'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $objActSheet->setCellValue( 'I'.$rowIndex, gbkToUtf($excelData['rpData']))->getStyle('I'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
            $rowIndex++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $dirPath = BATH_PATH."tmp/".time().rand(0,1000);
        if(!is_dir($dirPath)){
            mkdir($dirPath);
        }
        $excelPath = $dirPath."/reportExcel_".time().".xlsx";
        $objWriter->save($excelPath);
        require_once BATH_PATH."source/Util/DownloadFile.php";
        $download = new DownloadFile('php,exe,html',false);
        $download->setFileInLocal();
        if(!$download->downloadfile($excelPath)){
            echo $download->geterrormsg();
        }
    }
}