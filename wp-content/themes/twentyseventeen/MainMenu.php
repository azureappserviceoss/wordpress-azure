<?php
/**
 * The template for displaying all pages
 *The Template Name: MainMenu
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

// get_header(); ?>

<!DOCTYPE html>
<html>
<title>Pepito's Bistro</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<style>
body {font-family: "Times New Roman", Georgia, Serif;}
h1,h2,h3,h4,h5,h6 {
    font-family: "Playfair Display";
    letter-spacing: 5px;
}
</style>
<body>

<!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar w3-white w3-padding w3-card-2" style="letter-spacing:4px;">
    <a href="#home" class="w3-bar-item w3-button">Pepito's Bistro</a>
    <!-- Right-sided navbar links. Hide them on small screens -->
    <div class="w3-right w3-hide-small">
      <a href="#about" class="w3-bar-item w3-button">Acerca de</a>
      <a href="#menu" class="w3-bar-item w3-button">Menú</a>
      <a href="#contact" class="w3-bar-item w3-button">Contacto</a>
    </div>
  </div>
</div>

<!-- Header -->
<header class="w3-display-container w3-content w3-wide" style="max-width:1600px;min-width:500px" id="home">
  <img class="w3-image" src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnumexicano.jpg" alt="Hamburger Catering" width="1600" height="800">
  <div class="w3-display-bottomleft w3-padding-large w3-opacity">
    <h1 class="w3-xxlarge">Pepito's Bistro</h1>
  </div>
</header>

<!-- Page content -->
<div class="w3-content" style="max-width:1100px">

  <!-- About Section -->
  <div class="w3-row w3-padding-64" id="about">
    <div class="w3-col m6 w3-padding-large w3-hide-small">
     <img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnucampirano.jpg" class="w3-round w3-image w3-opacity-min" alt="Table Setting" width="600" height="750">
		 <br>
		 <img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnugriego.jpg" class="w3-round w3-image w3-opacity-min" alt="Table Setting" width="600" height="750">
		 <br>
		 <img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnu3quesos.jpg" class="w3-round w3-image w3-opacity-min" alt="Table Setting" width="600" height="750">

		</div>

    <div class="w3-col m6 w3-padding-large">
      <h1 class="w3-center">Acerca de Pepito's</h1><br>
      <h5 class="w3-center">Un nuevo concepto en Guatemala</h5>
      <p class="w3-large">Inspirados por la tradición de las tabernas españolas, pero con un retoque del estilo bistró en su compleja sencillez.
				El pepito de Pepito´s Gourmet brinda una propuesta innovadora al mundo de los bocadillos taberneros.
				Otorgando así, sabores complejos e inexplorados que busca transportar a los comensales a nuevas experiencias culinarias, al tiempo que
				mantiene la comodidad y sencillez de un pepito.</p>

      <p class="w3-large w3-text-grey w3-hide-medium">El pepito es un bocadillo popular de las tabernas españolas. Originalmente consistía en un
				baguette con filete de ternera. Pero debido a su popularidad se ha adoptado el nombre de pepito a todo tipo de bocadillos. Se cuenta que
				originalmente data de la postguerra española, en la que en una taberna un señor llamado Pepe pidió habitualmente este bocadillo, al ganar
				popularidad entre los comensales en vez de pedir “un bocadillo como el de Pepe” pedían un Pepito.</p>
    </div>
  </div>

  <hr>

  <!-- Menu Section -->
  <div class="w3-row w3-padding-64" id="menu">
    <div class="w3-col l6 w3-padding-large">
      <h1 class="w3-center">Nuestro Menú</h1><br>
      <h4>Klassiker</h4>
      <p class="w3-text-grey">Inspirado en el Hot Dog Alemán. Salchicha Frankfurt con exquisita mostaza Dijon para los más finos paladares.</p><br>

      <h4>Tres quesos</h4>
      <p class="w3-text-grey">Lo mejor de tres quesos: gouda, queso de cabra y mozarella. Acompañado de rica ceblla y pimientos cocidos.</p><br>

      <h4>Gourmet Mex</h4>
      <p class="w3-text-grey">Inspirado en la gastronomía mexicana con exquisito guacamol, tocino y el típico toque picante mexicano.</p><br>

      <h4>Griego</h4>
      <p class="w3-text-grey">Una inspiración del Olimpo. Queso de cabra, salchicha italiana y saltziki.</p><br>

      <h4>Champagnat</h4>
      <p class="w3-text-grey">Deliciosos champiñones cocidos acompañados de queso gouda.</p><br>

			<h4>Francés</h4>
			<p class="w3-text-grey">Una exquisita combinación de queso gouda y cebolla dulce para su Hot Dog.</p><br>

			<h4>Monte Cristo</h4>
      <p class="w3-text-grey">Una combinación de salchicha Frankfurt con jamón de cerdo, acompañada de rico queso mozarella y cebolla frita.</p><br>

			<h4>Nacho Dog</h4>
      <p class="w3-text-grey">Un plato digno de los amantes de la comida frita. Tocino frito, queso mozarella y deliciosos jalapeños.</p><br>

    </div>

    <div class="w3-col l6 w3-padding-large">
      <img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnuklassiker.jpg" class="w3-round w3-image w3-opacity-min" alt="Menu" width="500" height="750">
			<br>
			<img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnuchampagnat.jpg" class="w3-round w3-image w3-opacity-min" alt="Menu" width="500" height="750">
			<br>
			<img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnucapri.jpg" class="w3-round w3-image w3-opacity-min" alt="Menu" width="500" height="750">
			<br>
			<img src="http://localhost/pepitosbistro/wp-content/uploads/2017/05/mnucapri.jpg" class="w3-round w3-image w3-opacity-min" alt="Menu" width="500" height="750">


		</div>
  </div>

  <hr>

  <!-- Contact Section -->
  <div class="w3-container w3-padding-64" id="contact">
    <h1>Contacto</h1><br>
    <p>.</p>
		<p class="w3-text-blue-grey w3-large"><b>Horario: 10:00 - 20:00</b></p>
		<p class="w3-text-blue-grey w3-large"><b>Número telefónico: (502) 7374867 o 1 (800) P-E-P-I-T-O-S-B-I-S-T-R-O</b></p>


    <p class="w3-text-blue-grey w3-large"><b>Centro Comercial La Pradera. Entre Boulevard Los Próceres y 20 calle 25-85 Zona10, Ciudad de Guatemala. Local 502.</b></p>
    <p>Para más informacion nos puede contactar a nuestro número telefónico o email informacion@pepitosbistro.com, o nos puede enviar un mensaje aquí:</p>
    <form action="/action_page.php" target="_blank">
      <p><input class="w3-input w3-padding-16" type="text" placeholder="Nombre" required name="Name"></p>
      <!-- <p><input class="w3-input w3-padding-16" type="number" placeholder="How many people" required name="People"></p> -->
      <!-- <p><input class="w3-input w3-padding-16" type="datetime-local" placeholder="Date and time" required name="date" value="2017-11-16T20:00"></p> -->
      <p><input class="w3-input w3-padding-16" type="text" placeholder="Duda" required name="Message"></p>
      <p><button class="w3-button w3-light-grey w3-section" type="submit">ENVIAR MENSAJE</button></p>
    </form>
  </div>

<!-- End page content -->
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-32">
  <p>Powered by PepitosBistro</p>
</footer>

</body>
</html>
