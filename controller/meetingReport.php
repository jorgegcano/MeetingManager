<?php
require_once "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$tableHead = [
  'font'=>[
    'color'=>[
      'rgb'=>'FFFFFF'
    ],
    'bold'=>true,
    'size'=>11,
  ],
  'fill'=>[
    'fillType'=> Fill::FILL_SOLID,
    'startColor'=>[
      'rgb' => '538ED5'
    ]
  ],
];

$tableHeadGuest = [
  'font'=>[
    'color'=>[
      'rgb'=>'FFFFFF'
    ],
    'bold'=>true,
    'size'=>11,
  ],
  'fill'=>[
    'fillType'=> Fill::FILL_SOLID,
    'startColor'=>[
      'rgb' => 'bfb450'
    ]
  ],
];

if (isset($_GET) && $_GET['tipo'] == 'global'){

  $array = $_GET['arrayIds'];

  $array = explode(",", $array);

  require_once '../model/empleado_reunion_DAO.php';

  $dao = new Empleado_reunion_DAO();

  $data = [];

  $idAux = 0;

  $spread = new Spreadsheet();
  $spread
      ->getProperties()
      ->setCreator("Meeting Manager")
      ->setLastModifiedBy('Meeting Manager')
      ->setTitle('Informe del gasto total de reuniones')
      ->setKeywords('PHPSpreadsheet')
      ->setCategory('Informes');

      $spread->getActiveSheet()->getStyle('A1:E1')->applyFromArray($tableHead);
      $spread->getActiveSheet()->getColumnDimension('A')->setWidth(30);
      $spread->getActiveSheet()->getColumnDimension('B')->setWidth(15);
      $spread->getActiveSheet()->getColumnDimension('C')->setWidth(15);
      $spread->getActiveSheet()->getColumnDimension('D')->setWidth(20);
      $spread->getActiveSheet()->getColumnDimension('E')->setWidth(15);

      $i = 1;
      for($j=0; $j<count($array); $j++){
        $data = $dao->obtener_reunion_detallada_Excel($array[$j]);
        foreach ($data as $cell) {
          $id = $cell['idReunion_fk'];
          if ($id != $idAux) {
          $spread->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($tableHead);
          $spread->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, 'Asunto')
          ->setCellValue('B'.$i, 'Fecha')
          ->setCellValue('C'.$i, 'Comienzo')
          ->setCellValue('D'.$i, 'Finalización')
          ->setCellValue('E'.$i, 'coste Final');
          $i++;
          $spread->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $cell['asunto'])
            ->setCellValue('B'.$i, $cell['fecha'])
            ->setCellValue('C'.$i, $cell['inicio'])
            ->setCellValue('D'.$i, $cell['fin'])
            ->setCellValue('E'.$i, $cell['costeEstimado']);
            $i++;
            $idAux = $id;
            $spread->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($tableHeadGuest);
            $spread->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, 'Invitados')
            ->setCellValue('B'.$i, 'Departamento')
            ->setCellValue('C'.$i, 'Coste/Hora');
            $i++;
            }
            $spread->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $cell['nombre']." ".$cell['apellidos'])
            ->setCellValue('B'.$i, $cell['departamento'])
            ->setCellValue('C'.$i, $cell['costeHora']);
            $i++;
        }
      }

      $spread->getActiveSheet()->getStyle('D'.$i)->applyFromArray($tableHead);
      $spread->setActiveSheetIndex(0)
      ->setCellValue('D'.$i, 'Gasto Acumulado:')
      ->setCellValue('E'.$i, '=sum(E2:E'.($i-1).')');

}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="meeting_report.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spread, 'Xlsx');
/*para AJAX*/ob_start();
$writer->save('php://output');

/*para AJAX*/
$xlsData = ob_get_contents();
ob_end_clean();
$opResult = array(
    'status' => 1,
    'data'=>"data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
 );
echo json_encode($opResult);

exit;

?>
