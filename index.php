<html lang="cs">
	<head>
		<title>Svobodné rámečky</title>
		<meta charset="UTF-8">
		<meta name="description" content="Vkládání rámečků do profilových fotografií">
		<meta name="author" content="Alois Sečkár">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="0">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
		<link href="ramecky.css" rel="stylesheet">
	</head>
	
	<body>
	<?php
		$message = "Vyberte fotografii, kterou chcete doplnit rámečkem (JPG or PNG)";
		$alert_type = "alert-info";
		$output = null;
		$img_dir = "tmp/";
		$source_file = $img_dir . "input";
		$target_file = $img_dir . "output.png";
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		if(isset($_FILES["fileToUpload"])) {
			try {
				$mime = $_FILES["fileToUpload"]["type"];
				$check = $mime == "image/jpeg" || $mime == "image/png";
				if($check !== false) {
					
					if (file_exists($source_file)) {
						unlink($source_file);
					}
					if (file_exists($target_file)) {
						unlink($target_file);
					}
					
					
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $source_file)) {
						
						$im = imagecreatefromstring(file_get_contents($source_file));
						
						$im_x = imagesx($im);
						$im_y = imagesy($im);
						$crop_size = min($im_x, $im_y);
						$crop_x_offset = $im_x > $crop_size ? (($im_x - $crop_size) / 2) : 0;
						$crop_y_offset = $im_y > $crop_size ? (($im_y - $crop_size) / 2) : 0;
						$im = imagecrop($im, ['x' => $crop_x_offset, 'y' => $crop_y_offset, 'width' => $crop_size, 'height' => $crop_size]);
						
						if ($crop_size > 1000) {
							$im = imagescale($im, 1000, 1000, IMG_BICUBIC);
						}
						
						$stamp = imagecreatefrompng($_POST["stamp"]);
						$stamp_size = min($crop_size, 1000);
						
						imagecopyresampled($im, $stamp, 0, 0, 0, 0, $stamp_size, $stamp_size, 1000, 1000);
						imagepng($im, $target_file);
						
						imagedestroy($im);
						
						$output = $target_file;
						
						$message = "Soubor ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " byl úspěšně zpracován<br />Výsledný soubor si můžete stáhnout níže a vložit do Vašeho profilu";
						$alert_type = "alert-success";
						
					} else {
						$message = "Chyba při nahrávání souboru";
						$alert_type = "alert-danger";
					}
				} else {
					$message = "Soubor nemá požadovaný formát (JPG or PNG)";
					$alert_type = "alert-danger";
				}
			} catch (Exception $e) {
				$message = "Chyba při zpracování souboru";
				$alert_type = "alert-danger";
			} 
		}
		
	?> 
	
		<div class="container text-center">
			<h1>Svobodné rámečky</h1>
			<h2>Vylepšete si profilovou fotografii rámečkem z dílny Svobodných</h2>
			<div class="row">
				<div class="alert <?=$alert_type;?>"><?=$message;?></div>
			</div>
			<div>
				<table>
				<tr><th>Varianta 1</th><th>Varianta 2</th></tr>
				<tr><td><img src="bg1.png" alt="Varianta 1" class="thumb" /></td><td><img src="bg2.png" alt="Varianta 2" class="thumb" /></td></tr>
				</table>
			</div>
			<div class="row">
				<form class="form-inline" action="index.php" method="post" enctype="multipart/form-data">
					<p>
					<label for="stamp">Přidat rámeček:</label>
					<select name="stamp" id="stamp">
					  <option value="bg1.png" <?php if ($_POST["stamp"] == "bg1.png") echo "selected"; ?>>Varianta 1</option>
					  <option value="bg2.png" <?php if ($_POST["stamp"] == "bg2.png") echo "selected"; ?>>Varianta 2</option>
					</select>
					</p>
					<input type="file" name="fileToUpload" id="fileToUpload" style="display:none;" onchange="this.form.submit();" ondrag="this.form.submit();"/>
					<label for="fileToUpload" id="img-area" ondrag="this.form.submit();">Klikněte pro výběr souboru</label>
				</form>
				<p>Zpracování začne automaticky po vybrání fotografie (větší soubory mohou chvíli trvat)</p>
				<p>Soubor bude v případě potřeby oříznut na čtvercový tvar a zmenšen na velikost 1000x1000</p>
			</div>
			<hr />
			<div class="row">
				<?php if (isset($output)) { ?>
					<p><img src="<?=$output . "?" . filemtime($output) ;?>" class="res" /></p>
				<?php } ?>
			</div>
			<hr />
			<div class="row">
				<p class="font-weight-light"><a href="http://alois-seckar.cz">Alois Sečkár</a> pro <a href="https://www.svobodni.cz/">Svobodné</a> 2022 | <a href="https://unlicense.org/">UNLICENSE</a></p>
			</div>
		</div>
		
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
		
	</body>
</html>