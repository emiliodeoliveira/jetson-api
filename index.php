<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <title>Jetson API</title>
</head>
<body>
  <div class="container">
    <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills pull-right">
          <li role="presentation" class="active">
            <a href="index.html">Home</a>
          </li>
          <li role="presentation" id="sair">
            <a href="#" onclick="sair();">Sair</a>
          </li>
          <li>
            <fb:login-button id="fb-btn" scope="public_profile,email, user_likes" onlogin="checkLoginState();">
            </fb:login-button>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Jetson API</h3>
    </div>
    <hr>
    <div class="well">
      <div id="usuario"></div>
    </div>
    <div id="paginas"></div>
  </div>
</body>
<script>
  window.fbAsyncInit = function () {
    FB.init({
      appId: '249085572631060',
      cookie: true,
      xfbml: true,
      version: 'v3.2'
    });

    FB.getLoginStatus(function (response) {
      statusChangeCallback(response);
    });
  };

  (function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) { return; }
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function statusChangeCallback(response) {
    if (response.status === 'connected') {
      console.log("UsuÃ¡rio Autorizado");
      requestAPI();
      document.getElementById('fb-btn').style.display = "none";
      document.getElementById('sair').style.display = "";
      document.getElementById('usuario').style.display = "";
    } else {
      console.log("Não Autorizado");
      document.getElementById('sair').style.display = "none";
    }
  }
  function checkLoginState() {
    FB.getLoginStatus(function (response) {
      statusChangeCallback(response);
    });
  }
  function requestAPI() {
    FB.api('me?fields=name,email,birthday,likes.limit(200){link,name,picture,fan_count},link', function (response) {
      console.log(response);
      perfil(response);
      paginas(response);
    });
  }
  function perfil(usuario) {
    var perfil = `
		<h4><strong>Nome: </strong>${usuario.name}</h4>
		<h4><strong>ID: </strong>${usuario.id}</h4>
    <h4><strong>E-mail: </strong>${usuario.email}</h4>
    <h4><strong>Nascimento: </strong>${usuario.birthday}</h4>
		<h4>Acessar Perfil: <a href="${usuario.link}" target="_blank">Clique Aqui</a></h4>
	`;
    document.getElementById('usuario').innerHTML = perfil;
  }
  function paginas(pagina) {
    paginas = document.getElementById('paginas');
    paginas.innerHTML = "";
    for (var i = 0; i < pagina.likes.data.length; i++) {
      paginas.innerHTML += `
		<div class="well">
				<div class="media">
				  <div class="media-left">
				    <a href="#">
				     <img src="${pagina.likes.data[i].picture.data.url}" class="media-object">
				    </a>
				  </div>
				  <div class="media-body">
				    <h4 class="media-heading">${pagina.likes.data[i].name}</h4>
				    <p><strong><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Curtidas:</strong> <span class="badge">${pagina.likes.data[i].fan_count}</span> </p>
				    <p><a href="${pagina.likes.data[i].link}" target="_blank"> Visitar a Página </a></p>
				  </div>
				</div>
		</div>
		`;
    }
  }
  function sair() {
    FB.logout(function (response) {
      document.getElementById('fb-btn').style.display = "";
      document.getElementById('sair').style.display = "none";
      document.getElementById('usuario').style.display = "none";
    });
  }
</script>
</html>