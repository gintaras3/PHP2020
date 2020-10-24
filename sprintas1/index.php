<html>

<?php 
    session_start(); 

    // logout
    if(isset($_GET['action']) and $_GET['action'] == 'logout'){
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        unset($_SESSION['logged_in']);
    }
    //log in
    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {	
       if ($_POST['username'] == 'admin' && $_POST['password'] == '1') {
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = 'jovalas';
       } else {
          $msg = 'Įvestas neteisingas vartotojo vardas arba slaptažodis';
       }
    }
?>


<?php
// file download 

if(isset($_POST['download'])){
    $file = './' . $_GET['path'] . './' . $_POST['download']; 
    // $path = "./" . $_GET['path'];
    // $file='./' . $_GET["path"] . $_POST['download'];
    $fileDown = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
    print_r($fileDown);
    print_r($file);
    ob_clean();
    ob_flush();
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . basename($fileDown));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileDown));
    // flush();
    ob_end_flush();
    readfile($fileDown);
    exit;
}
?>

<?php
//file upload
$path = "./" . $_GET['path'];
    if(isset($_POST['įkelti'])){
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_store = ($path . '/') .$file_name;
        move_uploaded_file($file_tmp,$file_store);
    }
?>
<head>
    <title>JOVALAS v.1.0</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>

<div>
         <?php
            $msg = '';
            if (isset($_POST['login']) 
                && !empty($_POST['username']) 
                && !empty($_POST['password'])
            ) {	
               if ($_POST['username'] == 'jovalas' && 
                  $_POST['password'] == '1'
                ) {
                  $_SESSION['logged_in'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = 'jovalas';
               } else {
                  $msg = 'Įvestas neteisingas vartotojo vardas arba slaptažodis';
               }
            }
         ?>
      
      <?php 
            if($_SESSION['logged_in'] == false){
                print('<form class="" id="jungtis" action = "" method = "post">' );
                print('<h2>Prieš įvesdami bet kokią raidę, skaičių ar ženklą ir spausdami mygtuką "Užeiti", įsitikinkite, </h2>');
                print('<h2>kad savo galimais veiksmais niekam nepadarysite jokios galimos ar menamos žalos,</h2>');
                print('<h2>neišprovokuosite karo veiksmų, nepažeisite jokių privačių ar valstybinių interesų,</h2>');
                print('<h2>bei nesukelsite grėsmės kieno nors sveikatai ar gyvybei.</h2>');
                // print('<h4>' . $msg . '</h4>');
                print('<h4>Įėjimas</h4>');
                print('<input type = "text" name = "username" placeholder = "username = jovalas" required autofocus></br>');
                print('<input type = "password" name = "password" placeholder = "password = 1" required><br>');
                print('<button class = "button" type = "submit" name = "login">Užeiti</button>');
                print('</form>');
                die();
            }
            
        ?>
        
        <form action = "" method = "post" id="jungtis">
            <h3>Jūs įėjote, elkitės gražiai!</h3>
            <h4><?php echo $msg; ?></h4>
            Norėdami išeiti spauskite <a href = "index.php?action=logout"> čia.</a>
        </form>

            
        </div>
    <h1>BYLŲ VALDYBOS JOVALAS </h1>
    
   
<form action="<?php $path ?>" method="POST" class="direktorijos_kurimas"  >
        <label for="name">Naujos direktorijos kūrimas</label>
        <br>
        <input type="text" id="name" name="name" placeholder="Direktorijos pavadinimas">
        <button type="submit">Sukurti</button>
    </form>
    <form  class="bylos_ikelimas" action="" method="POST" enctype="multipart/form-data">
        <h3>Bylos įkėlimas</h3>
            <input type="file" name="file" id="jovalas2">
                <input  type="submit" value="įkelti" name="įkelti">
           
            </form>
          
    <?php
$path = "./" . $_GET['path'];
if (isset($_POST['name'])) {
    mkdir($path . "/" . ($_POST['name']));
}
if (array_key_exists('file', $_GET)) {
    unlink($path . "/" . $_GET['file']);
}
$dir_contents = scandir($path);
echo("<table id='table'><thead><tr>
<th>Type</th> 
<th>Name</th> 
 <th>Actions(Download)</th>
 <th>Actions(Delete)</th>
  </tr></thead>");
echo("<tbody><tr>");

foreach ($dir_contents as $cont) {
    echo("<tr><td>" . (is_dir($path . "/" . $cont) ? "Dir" : "File") . "</td>");
    if (is_dir($path . "/" . $cont)) {
        echo("<td>" . "<a href='./?path=" . $_GET['path'] . "/" . $cont . "'>" . $cont . "</a> </td>");
    } else {
        echo("<td>"  . $cont . "</td>");
    }
    if (is_file ($path . "/" . $cont)) {
        echo '<td><form style="display: inline-block" action="" method="post">
        <input type="hidden" name="download" value='.$cont.'>
        <input class="middle" type="submit" value="Download">
       </form></td>';
        if ($cont != "index.php") {
            echo ("<td><button id='button_delete'><a id='raides' href='./?path=" . $_GET['path'] . "&file=" . $cont . "'>" . "Delete</a></button></td>");
        } else {
        
            echo ("<td></td>");
     
        }
    } else {
        echo("<td></td>");
    }
       
    }
    echo('<tbody></table>');
    $back = explode("/", $_GET['path']);
        $backString = "";
        for ($i = 0; $i < count($back) - 1; $i++) {
            if($back[$i] == "")
            continue;
            $backString .= "/" . $back[$i];
        }
        echo("<button>" . "<a href='./?path=" . $backString . "'>" . "BACK" . "</a>" . "</button>");
    
    ?>




</table>
</body>
</html>