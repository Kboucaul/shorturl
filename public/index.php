
<?php
// IS RECEIVED SHORTCUT

$host     = $_SERVER['HTTP_HOST'];

if(isset($_GET['q'])){

	// VARIABLE
	$shortcut = htmlspecialchars($_GET['q']);

	// IS A SHORTCUT ?
    $bdd = new PDO("mysql:host=localhost;dbname=shorturl;charset=utf8", 'root', '');
	$req =$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		if($result['x'] != 1){
			header('location: ../?error=true&message=Adresse url non connue');
			exit();
		}

	}

	// REDIRECTION
	$req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		header('location: '.$result['url']);
		exit();

	}

}

// IS SENDING A FORM
if(isset($_POST['url'])) {

	// VARIABLE
	$url = $_POST['url'];

	// VERIFICATION
	if(!filter_var($url, FILTER_VALIDATE_URL)) {
		// PAS UN LIEN
		header('location: ../?error=true&message=Adresse url non valide');
		exit();
	}

	// SHORTCUT
	$shortcut = crypt($url, rand());

	// HAS BEEN ALREADY SEND ?
	$bdd = new PDO("mysql:host=localhost;dbname=shorturl;charset=utf8", 'root', '');
	$req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
	$req->execute(array($url));

	while($result = $req->fetch()){

		if($result['x'] != 0){
			header('location: ../?short='.$shortcut);
	        exit();
		}

	}

	// SENDING
	$req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
	$req->execute(array($url, $shortcut));

	header('location: ../?short='.$shortcut);
	exit();

}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Raccourcisseur d'url express</title>
		<link rel="shortcut icon" type="image/png" href="pictures/favico.png"/>
    <link rel="stylesheet" href="/design/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	</head>

	<body>
		<!-- PRESENTATION -->
		<section id="hello">
			
			<!-- CONTAINER -->
			<div class="container">
				
				<!-- HEADER -->
				<header class="py-3">
                    <span class="logo">ShortUrl</span>
                </header>

				<!-- VP -->
				<h1>Une url longue ? Raccourcissez-la ?</h1>
				<h2>Largement meilleur et plus court que les autres.</h2>

				<!-- FORM -->
				<form  method="post" action="../">
					<input type="url" name="url" placeholder="Collez un lien à raccourcir">
					<input type="submit" value="Raccourcir">
				</form>

				<?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>
					<div class="center container">
						<div id="result">
							<b><?php echo htmlspecialchars($_GET['message']); ?></b>
						</div>
					</div>
				<?php } else if(isset($_GET['short'])) { ?>
					<div class="center container">
						<div id="result">
							<b>URL RACCOURCIE : </b>
							http://<?=$host?>/?q=<?php echo htmlspecialchars($_GET['short']); ?>
						</div>
					</div>
				<?php } ?>

			</div>

		</section>

		<!-- BRANDS -->
		<section id="brands">
			
			<!-- CONTAINER -->
			<div class="container">
				<h3>Ces marques nous font confiance</h3>
				<img src="pictures/1.png" alt="1" class="picture">
				<img src="pictures/2.png" alt="2" class="picture">
				<img src="pictures/3.png" alt="3" class="picture">
				<img src="pictures/4.png" alt="4" class="picture">
			</div>

		</section>

		<!-- FOOTER -->
		<section id="footer">
         <span class="logo">ShortUrl</span>
         <p class="mb-0">© Kévin Boucault</p>
         <a href="#">Contact</a>
         -
         <a href="https://www.linkedin.com/in/k%C3%A9vin-boucault-1095/">À propos de moi</a>
    </section>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	</body>
</html>