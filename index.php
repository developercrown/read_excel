<?php
		require_once 'PHPExcel/Classes/PHPExcel.php';
		$archivo = "database.xlsx";
		$inputFileType = PHPExcel_IOFactory::identify($archivo);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($archivo);
		$sheet = $objPHPExcel->getSheet(0); 
		$highestColumn = $sheet->getHighestColumn();
		$data = array();
		$filter = null;


		function crearRow($matricula, $nombre, $carrera, $taller, $salon){
			$output = array(
				"matricula" => "$matricula",
				"nombre" => "$nombre",
				"carrera" => "$carrera",
				"taller" => "$taller",
				"salon" => "$salon"
			);
			return $output;
		}

		function send_results($data){
			header('Content-Type: application/json');
			echo json_encode($data);
		}

		function process_results($sheet, $filter=false){
			$highestRow = $sheet->getHighestRow();
			$output = array();
			if($filter != false){
				for ($row = 2; $row <= $highestRow; $row++){
					$string = strtolower($sheet->getCell("B".$row)->getValue() . "");
					if(strpos($string , $filter) !== false){
						$tmp = crearRow(
							strtolower($sheet->getCell("A".$row)->getValue()),
							strtolower($sheet->getCell("B".$row)->getValue()),
							strtolower($sheet->getCell("C".$row)->getValue()),
							strtolower($sheet->getCell("D".$row)->getValue()),
							strtolower($sheet->getCell("E".$row)->getValue())
						);
						array_push($output, $tmp);
					}
				}
			}else{
				for ($row = 2; $row <= $highestRow; $row++){
					$string = strtolower($sheet->getCell("B".$row)->getValue() . "");
						$tmp = crearRow(
							strtolower($sheet->getCell("A".$row)->getValue()),
							strtolower($sheet->getCell("B".$row)->getValue()),
							strtolower($sheet->getCell("C".$row)->getValue()),
							strtolower($sheet->getCell("D".$row)->getValue()),
							strtolower($sheet->getCell("E".$row)->getValue())
						);
						array_push($output, $tmp);
				}
			}

			return $output;
		}

		if(isset($_GET['query']) && $_GET['query'] != "" && $_GET['query'] != " "){
			$filter = $_GET["query"];
			$out_data = process_results($sheet, $filter);
			send_results($out_data);
		}else{
			$out_data = process_results($sheet);
			send_results($out_data);
		}

		

		
	?>