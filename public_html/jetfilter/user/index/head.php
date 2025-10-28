<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="shortcut icon" href="./../../img/logo/web.ico" type="image/x-icon">
	
	
    <?php
if (!isset($_GET['pag']) || $_GET['pag'] == '') {
    echo '<title>Jetfilter</title>';
} else {
    $pag = $_GET['pag'];
    $pag = ucfirst($pag);
        echo '<title>'.$pag.'</title>';
    
}
?>
	<link href="./../../css/css_vende/styles.css" rel="stylesheet">
    <link href="./../../css/estilos.css" rel="stylesheet">
	<link rel="stylesheet" href="./../../vendor/bootstrap/css/bootstrap.min.css">
	<link href="./../../css/css_vende/app.css" rel="stylesheet">
    <link href="./../../css/css_vende/pagina.css" rel="stylesheet">
    <link rel="stylesheet" href="./../../vendor/boxicons/css/boxicons.min.css">
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script src="./../gestor/js/sweetAlerta.js"></script> 
	
	<link rel="stylesheet" href="./../../css/css_vende/dataTables.bootstrap51.css">

</head>