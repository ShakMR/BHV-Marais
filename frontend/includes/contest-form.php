<body>
<form method="post" autocomplete="off">
	<section class="form-block form-block-info">
		<img class="form-logo" src="img/logoNoel.jpg">
		<p class="form-note">
			Cliquez sur chaque champs pour introduir vos données.
			* champs obligatoires
		</p>
		<p class="form-alert form-alert-general">
			Field/s empty
		</p>
		<input class="form-field form-code" name="code" type="text" placeholder="Code de votre flyer">
		<input class="form-field" name="lastname" type="text" placeholder="NOM *">
		<input class="form-field" name="name" type="text" placeholder="PRENOM *">
		<p class="form-alert form-alert-password">
			Merci d'entrer un e-mail valide.
		</p>
		<input class="form-field not-margin-bottom" name="email" type="text" placeholder="E-MAIL *">
		<p class="form-note">
		<input class="form-check" name="info" type="checkbox" checked>
		J'autorise LE BHV MARAIS à utiliser mon adresse email à des fins commerciales.<br>
		<a href="#">Lire les conditions</a>
		</p>
		<a class="form-submit" name="submit">JE TENTE MA CHANCE</a>
		<a class="end-session-button" target="_blacnk" href="logout.php">Fermer l'application</a>
	</section>
</form>
<div class="img-wrapper">
	<img class="back-img back-img-g"src="img/paraplui.jpg">
</div>
<div class="img-wrapper">
	<img class="back-img back-img-d"src="img/papanoel.jpg">
</div>
<script src="./js/submit.js"></script>
