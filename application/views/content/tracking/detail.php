<table class="table"> 
<thead> <tr> 
	<th>NO CONT</th>
 <th>KONDISI SEGEL</th>
  <th>PELABUHAN BONGKAR</th>
  <th>WAKTU IN</th> 
  <th>WAKTU OUT</th> 
  <th>WAKTU REKAM</th> 
</tr> </thead>
 <tbody> 

 	<?php
// $jml = count($arrhdr);
 	//print_r($arrhdr);
    foreach ($arrhdr as $v2) {
echo' 	<tr> 	
 		<th scope="row">'.$v2->NO_CONT.'</th> 
 		<td>'.$v2->KONDISI_SEGEL.'</td>
 		 <td>'.$v2->KD_PEL_BONGKAR.'</td> 
 		<td>'.$v2->WK_IN.'</td> 
<td>'.$v2->WK_OUT.'</td>
<td>'.$v2->WK_REKAM.'</td>
 		</tr>';
    }

 	?>
 
 	</tbody> 
</table>
