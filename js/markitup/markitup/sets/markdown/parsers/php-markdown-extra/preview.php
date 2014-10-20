<?php
/*
 * Markitup Preview using Php Markdown Extra
 */
include_once dirname(__FILE__) . "/markdown.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>markItUp! preview template</title>
<!-- link rel="stylesheet" type="text/css" href="/wp-content/plugins/simpli-frames/js/markitup/markitup/templates/preview.css" /-->
<link type="text/css" rel="stylesheet" href="http://markitup.jaysalvat.com/examples/markitup/templates/preview.css" />

<?php
    $markitup_preview_html = Markdown($_POST['data']);
    echo $markitup_preview_html;
    ?>


</head>
<body>
<!-- content -->
</body>
</html>

