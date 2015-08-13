<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-10
 * Time: ����4:15
 */
require_once BATH_PATH."source/PHPExcel.php";
class AssessExcel{
    static  $excelInstance;
    static function getExcelObjInstance(){
        if(!self::$excelInstance){
            self::$excelInstance = new PHPExcel();
        }
        return self::$excelInstance;
    }

    //����excel��������
    static function setExcelData($assessData){
        self::setBaseTplData();
        $assessBaseInfo = $assessData['baseInfo'];
        $objPHPExcel = self::getExcelObjInstance();

        //���ÿ��˻�������
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue( 'B1', gbkToUtf($assessBaseInfo['dep']))
            ->setCellValue( 'B2', gbkToUtf($assessBaseInfo['depGroup']))
            ->setCellValue( 'B3', gbkToUtf($assessBaseInfo['name']))
            ->setCellValue( 'B4', gbkToUtf($assessBaseInfo['leaderName']))
            ->setCellValue( 'B5', gbkToUtf($assessBaseInfo['period']));

        $rowIndex = 11;
        //���ÿ��˾���ָ����
        $objActSheet =  $objPHPExcel->setActiveSheetIndex(0);
        $totalResult = array();
        $styleThinBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,   //����border��ʽ
                    'color' => array ('argb' => 'FF000000'),          //����border��ɫ
                ),
            ),
        );
        foreach($assessData['attrList'] as $attrData){
            foreach($attrData['itemList'] as $itemData){
                $objActSheet->setCellValue( 'A'.$rowIndex, gbkToUtf($itemData['detailName']))->getStyle('A'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'B'.$rowIndex, gbkToUtf($itemData['detailTxt']))->getStyle('B'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'C'.$rowIndex, gbkToUtf($itemData['assessStad']))->getStyle('C'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'D'.$rowIndex, gbkToUtf($itemData['reachTime']))->getStyle('D'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'E'.$rowIndex, gbkToUtf($itemData['qz']."%"))->getStyle('E'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'F'.$rowIndex, gbkToUtf($itemData['sourceData']))->getStyle('F'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'G'.$rowIndex, gbkToUtf($itemData['selfScore']))->getStyle('G'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'H'.$rowIndex, gbkToUtf($itemData['selfAssess']))->getStyle('H'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'I'.$rowIndex, gbkToUtf($itemData['leadScore']))->getStyle('I'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $objActSheet->setCellValue( 'J'.$rowIndex, gbkToUtf($itemData['leadAssess']))->getStyle('J'.$rowIndex)->applyFromArray($styleThinBlackBorderOutline);
                $totalResult['totalQz']+= $itemData['qz'];
                $totalResult['totalSelf']+= $itemData['qz']*$itemData['selfScore']*0.01;
                $totalResult['totalLead']+= $itemData['qz']*$itemData['leadScore']*0.01;
                $rowIndex++;
            }
        }
        $objActSheet->setCellValue( 'A'.$rowIndex, gbkToUtf('�¶ȼ�Ч�÷�'))->getStyle('A'.$rowIndex)->applyFromArray(
            array(
                'font'=>array(
                    'color'=>array('rgb' => 'FF0000'),
                    'bold' => true,
                ),

            )
        );
        $objActSheet->mergeCells( "A$rowIndex:D$rowIndex");

        $objActSheet->setCellValue( 'E'.$rowIndex, gbkToUtf($totalResult['totalQz']."%"));
        $objActSheet->getStyle( 'E'.$rowIndex)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle( 'E'.$rowIndex)->getFill()->getStartColor()->setARGB('FFFFE7BA');

        $objActSheet->setCellValue( 'G'.$rowIndex, gbkToUtf($totalResult['totalSelf']));
        $objActSheet->getStyle( 'G'.$rowIndex)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle( 'G'.$rowIndex)->getFill()->getStartColor()->setARGB('FFFFE7BA');

        $objActSheet->setCellValue( 'I'.$rowIndex, gbkToUtf($totalResult['totalLead']));
        $objActSheet->getStyle( 'I'.$rowIndex)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle( 'I'.$rowIndex)->getFill()->getStartColor()->setARGB('FFFFE7BA');


        $objActSheet->getStyle( 'A9:J9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle( 'A9:J9')->getFill()->getStartColor()->setARGB('FFFFE7BA');
        $styleMediumBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,   //����border��ʽ
                    'color' => array ('argb' => 'FF000000'),          //����border��ɫ
                ),
            ),
        );
        $objActSheet->getStyle( 'A1:J'.$rowIndex)->applyFromArray($styleMediumBlackBorderOutline);
    }

    //Ϊexcel���û�����ģ�����ݿ��
    static function setBaseTplData(){
        $objPHPExcel = self::getExcelObjInstance();
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
            ->setCellValue( 'A2', gbkToUtf('��λ'))
            ->setCellValue( 'A3', gbkToUtf('����'))
            ->setCellValue( 'A4', gbkToUtf('�ֹ��쵼'))
            ->setCellValue( 'A5', gbkToUtf('��������'))
            ->mergeCells( 'A7:J7')
            ->mergeCells( 'A9:F9')
            ->mergeCells( 'G9:H9')
            ->mergeCells( 'I9:J9')
            ->setCellValue('A7',gbkToUtf('�¶ȼ�Ч���˿��'))
            ->setCellValue( 'A9', gbkToUtf('���˿��'))
            ->setCellValue( 'G9', gbkToUtf('����'))
            ->setCellValue( 'I9', gbkToUtf('�ϼ�����'))
            ->setCellValue( 'A10', gbkToUtf('ά��ָ��/������Ŀ'))
            ->setCellValue( 'B10', gbkToUtf('���幤������/�ж�'))
            ->setCellValue( 'C10', gbkToUtf('���۱�׼'))
            ->setCellValue( 'D10', gbkToUtf('���ʱ��'))
            ->setCellValue( 'E10', gbkToUtf('Ȩ��'))
            ->setCellValue( 'F10', gbkToUtf('������Դ'))
            ->setCellValue( 'G10', gbkToUtf('��������[0~100]'))
            ->setCellValue( 'H10', gbkToUtf('��������'))
            ->setCellValue( 'I10', gbkToUtf('�ϼ�����[0~100]'))
            ->setCellValue( 'J10', gbkToUtf('�ϼ�����'));

        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth('25');
        $objActSheet->getColumnDimension('C')->setWidth('16');
        $objActSheet->getColumnDimension('D')->setWidth('16');
        $objActSheet->getColumnDimension('E')->setWidth('16');
        $objActSheet->getColumnDimension('F')->setWidth('16');
        $objActSheet->getColumnDimension('H')->setWidth('16');
        $objActSheet->getColumnDimension('I')->setWidth('16');

        $objActSheet->getColumnDimension('B')->setWidth('25');
        $objActSheet->getColumnDimension('H')->setWidth('20');
        $objActSheet->getColumnDimension('J')->setWidth('20');

        $objActSheet->getStyle( 'A1:A5')->getFont()->setBold(true);

        $objActSheet->getStyle( 'A7:J7')->getFont()->setSize(10);
        $objActSheet->getStyle( 'A7:J7')->getFont()->setBold(true);
        $objActSheet->getStyle( 'A7:J7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//ˮƽ���������˶���

        $objActSheet->getStyle( 'A9:J9')->getFont()->setBold(true);
        $objActSheet->getStyle( 'A9:J9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//ˮƽ���������˶���
        $objActSheet->getStyle( 'A9:J9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle( 'A9:J9')->getFill()->getStartColor()->setARGB('FFFFE7BA');
        $styleThinBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,   //����border��ʽ
                    'color' => array ('argb' => 'FF000000'),          //����border��ɫ
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle( 'A9:F9')->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle( 'G9:H9')->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle( 'I9:J9')->applyFromArray($styleThinBlackBorderOutline);

        $objActSheet->getStyle('A10:J10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//ˮƽ���������˶���
        $objActSheet->getStyle( 'A10:J10')->getFont()->setSize(10);
        $objActSheet->getStyle( 'A10:J10')->getFont()->setBold(true);
    }

    static function createExcel($baseId,$userId){
        $objPHPExcel = self::getExcelObjInstance();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $dirPath = BATH_PATH.'upload/'.$baseId;
        if(!is_dir($dirPath)){
            mkdir($dirPath);
        }
        $salt = self::getExcelSaltKey($baseId,$userId);
        $excelPath = $dirPath."/".self::getEncodeFileName($userId,$salt);
        $objWriter->save($excelPath);
    }

    static function getExcelSaltKey($baseId,$userId){
        return $baseId."_".$userId."_excel.xlsx";
    }


    static function getEncodeFileName($userId,$uniqueFileKey){
        $tmp = explode('.',$uniqueFileKey);
        $suffix = $tmp[count($tmp)-1];
        return $userId."_".md5($uniqueFileKey).".".$suffix;
    }
}