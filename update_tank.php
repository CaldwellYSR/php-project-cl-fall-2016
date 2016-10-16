<?php 
	require_once(__DIR__."/includes/functions.php"); 

    if (isset($_POST['submit'])) {
        unset($_POST["submit"]);
        if ($post = validate_data($_POST)) {
            if (update_tank($post["id"], $post)) {
                header("Location:index.php");
            } else {
                echo "Something went wrong... sorry about that";
            }

        }

    }


    if (isset($_GET['id']) && $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT)) {
        $tank = get_tank_by_id($id);
    } else {
		header("Location:index.php");
    }
?>
<!Doctype html />
<html>
<head>
    <title>Update Tank <?php echo $tank->name; ?></title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
    <h3>Update Tank <a href="index.php">&lt;&lt; Back</a></h3>
    <?php render_form($tank); ?>
</body>
</html>
