<?php require_once __DIR__ . '/common.php';
$type=$_GET['type'] ?? 'csv';
$data=$pdo->query('SELECT ea.id,s.reg_no,u.full_name,ea.score,ea.total,ea.start_time,ea.submit_time,ea.status FROM exam_attempts ea JOIN students s ON s.id=ea.student_id JOIN users u ON u.id=s.user_id ORDER BY ea.id DESC')->fetchAll();
if($type==='csv'){
 header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="results.csv"'); $out=fopen('php://output','w'); fputcsv($out,['ID','Reg No','Name','Score','Total','Percent','Start','Submit','Status']); foreach($data as $r){$p=$r['total']?round($r['score']/$r['total']*100,2):0; fputcsv($out,[$r['id'],$r['reg_no'],$r['full_name'],$r['score'],$r['total'],$p,$r['start_time'],$r['submit_time'],$r['status']]);} fclose($out); exit;
}
if($type==='xlsx' && file_exists(__DIR__ . '/../vendor/autoload.php')){
 require __DIR__ . '/../vendor/autoload.php';
 $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet(); $sheet=$spreadsheet->getActiveSheet();
 $sheet->fromArray(['ID','Reg No','Name','Score','Total'],null,'A1'); $r=2; foreach($data as $d){$sheet->fromArray([$d['id'],$d['reg_no'],$d['full_name'],$d['score'],$d['total']],null,'A'.$r++);} header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); header('Content-Disposition: attachment; filename="results.xlsx"'); (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output'); exit;
}
if($type==='pdf' && file_exists(__DIR__ . '/../vendor/autoload.php')){
 require __DIR__ . '/../vendor/autoload.php'; $html='<h3>Results</h3><table border="1" cellpadding="4"><tr><th>Name</th><th>Score</th><th>Total</th></tr>';
 foreach($data as $d){$html.='<tr><td>'.htmlspecialchars($d['full_name']).'</td><td>'.$d['score'].'</td><td>'.$d['total'].'</td></tr>';}
 $html.='</table>';
 $dompdf = new \Dompdf\Dompdf(); $dompdf->loadHtml($html); $dompdf->render(); $dompdf->stream('results.pdf'); exit;
}
exit('Requested export library missing. Run composer install.');
