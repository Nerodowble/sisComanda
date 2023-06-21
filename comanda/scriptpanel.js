document.addEventListener('DOMContentLoaded', function() {
      const menuIcon = document.querySelector('.menu-icon');
      const menu = document.querySelector('.menu');

      menuIcon.addEventListener('click', function() {
        menu.classList.toggle('open');
      });

      const submenuTitles = document.querySelectorAll('.submenu-title');

      submenuTitles.forEach(function(title) {
        title.addEventListener('click', function() {
          this.nextElementSibling.classList.toggle('open');
        });
      });
    });
    
