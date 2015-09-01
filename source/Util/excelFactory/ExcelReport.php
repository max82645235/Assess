<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-28
 * Time: ����10:11
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
            ->getProperties()  //����ļ����Զ��󣬸������ṩ������Դ
            ->setCreator( "Maarten Balliauw")                 //�����ļ��Ĵ�����
            ->setLastModifiedBy( "Maarten Balliauw")          //��������޸���
            ->setTitle( "Office 2007 XLSX Test Document" )    //���ñ���
            ->setSubject( "Office 2007 XLSX Test Document" )  //��������
            ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //���ñ�ע
            ->setKeywords( "office 2007 openxml php")        //���ñ��
            ->setCategory( "Test result file");                //�������
        $objPHPExcel->setActiveSheetIndex(0)             //���õ�һ�����ñ�һ��xls�ļ�������ж����Ϊ���
            ->setCellValue( 'A1', gbkToUtf('����'))
            ->setCellValue( 'B1', gbkToUtf('���'))
            ->setCellValue( 'C1', gbkToUtf('����'))
            ->setCellValue( 'D1', gbkToUtf('��������'))
            ->setCellValue( 'E1', gbkToUtf('Ƶ��'))
            ->setCellValue( 'F1', gbkToUtf('����ʱ��'))
            ->setCellValue( 'G1', gbkToUtf('��������'))
            ->setCellValue( 'H1', gbkToUtf('����'))
            ->setCellValue( 'I1', gbkToUtf('����'));

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
                    'style' => PHPExcel_Style_Border::BORDER_THIN,   //����border��ʽ
                    'color' => array ('argb' => 'FF000000'),          //����border��ɫ
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