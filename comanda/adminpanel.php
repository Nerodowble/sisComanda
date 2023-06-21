<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="stylepanel.css">
  <title>Menu Interativo</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .submenu ul {
      display: none;
    }
    .submenu.open ul {
      display: block;
    }
  </style>
</head>
<body>
  <div class="container">
    <span class="menu-icon">&#9776;</span>
    <div class="menu">
      <div class="submenu-title">Mesas</div>
		<div class="submenu">
		  <div class="submenu-content">
			<ul>
				  <li data-section="index.php">Index Mesas</li>
			</ul>
		  </div>
		</div>
      <div class="submenu-title">Itens</div>
      <div class="submenu">
        <ul>
          <li data-section="adicionar_itens.php">Adicionar Itens</li>
        </ul>
      </div>
      <div class="submenu-title">Garçons</div>
      <div class="submenu">
        <ul>
          <li data-section="garcom.php">Adicionar Garçom</li>
        </ul>
      </div>
      <div class="submenu-title">Cozinha</div>
      <div class="submenu">
        <ul>
          <li data-section="cozinha.php">Cozinha | Pedidos</li>
        </ul>
      </div>
      <div class="submenu-title">Cadastro de Mesas</div>
      <div class="submenu">
        <ul>
          <li data-section="cadastro_mesas.php">Cadastro de Mesas</li>
        </ul>
      </div>
	  <div class="submenu-title">Cadastro de Funcionários</div>
      <div class="submenu">
        <ul>
          <li data-section="cadastro_funcionario.php">Cadastro de funcionarios</li>
        </ul>
      </div>
    </div>
  </div>
  <script>
  //itens
    $(document).ready(function() {
      const menuIcon = $('.menu-icon');
      const menu = $('.menu');
      const submenuTitles = $('.submenu-title');

      menuIcon.click(function() {
        menu.toggleClass('open');
      });

      submenuTitles.click(function() {
        $(this).next('.submenu').toggleClass('open');
        const section = $(this).next('.submenu').find('li[data-section]');
        if (section.length > 0) {
          const sectionUrl = section.attr('data-section');
          loadSection(sectionUrl, $(this).next('.submenu'));
        }
      });

      function loadSection(url, container) {
        $.ajax({
          url: url,
          type: 'GET',
          dataType: 'html',
          success: function(response) {
            container.html(response);
          },
          error: function(xhr, status, error) {
            console.log(error);
          }
        });
      }
    });
	
	//Mesas
	
	$(document).ready(function() {
	  $('.menu-item-mesas-inicio').click(function(e) {
		e.preventDefault();
		
		// Realizar uma requisição AJAX para buscar o conteúdo da página index.php
		$.ajax({
		  url: 'index.php',
		  success: function(data) {
			// Inserir o conteúdo da página no HTML do submenu
			$('.submenu-content').html(data);
		  },
		  error: function() {
			alert('Erro ao carregar a página.');
		  }
		});
	  });
	});
	
	//Garçons
	
	$(document).ready(function() {
	  $('.menu-item-garcon').click(function(e) {
		e.preventDefault();
		
		// Realizar uma requisição AJAX para buscar o conteúdo da página garcom.php
		$.ajax({
		  url: 'garcom.php',
		  success: function(data) {
			// Inserir o conteúdo da página no HTML do submenu
			$('.submenu-content').html(data);
		  },
		  error: function() {
			alert('Erro ao carregar a página.');
		  }
		});
	  });
	});
	
	
	//Cozinha
	
	$(document).ready(function() {
	  $('.menu-item-cozinha').click(function(e) {
		e.preventDefault();
		
		// Realizar uma requisição AJAX para buscar o conteúdo da página garcom.php
		$.ajax({
		  url: 'cozinha.php',
		  success: function(data) {
			// Inserir o conteúdo da página no HTML do submenu
			$('.submenu-content').html(data);
		  },
		  error: function() {
			alert('Erro ao carregar a página.');
		  }
		});
	  });
	});
	
	
	//Cadastro de Mesas
	
	$(document).ready(function() {
	  $('.menu-item-cadastro_mesas').click(function(e) {
		e.preventDefault();
		
		// Realizar uma requisição AJAX para buscar o conteúdo da página garcom.php
		$.ajax({
		  url: 'cadastro_mesas.php',
		  success: function(data) {
			// Inserir o conteúdo da página no HTML do submenu
			$('.submenu-content').html(data);
		  },
		  error: function() {
			alert('Erro ao carregar a página.');
		  }
		});
	  });
	});
	
	//Cadastro de funcionários
	
	$(document).ready(function() {
	  $('.menu-item-cadastro_funcionario').click(function(e) {
		e.preventDefault();
		
		// Realizar uma requisição AJAX para buscar o conteúdo da página garcom.php
		$.ajax({
		  url: 'cadastro_funcionario.php',
		  success: function(data) {
			// Inserir o conteúdo da página no HTML do submenu
			$('.submenu-content').html(data);
		  },
		  error: function() {
			alert('Erro ao carregar a página.');
		  }
		});
	  });
	});

  </script>
</body>
</html>
