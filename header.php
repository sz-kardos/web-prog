<?php
require_once 'menu.php'; // Menü logika
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menü előkészítése
$menuItems = fetchAllMenuItems($pdo); // Adatbázis lekérdezése
$menuTree = buildMenuTree($menuItems); // Hierarchikus struktúra létrehozása
$menuHTML = generateMenuHTML($menuTree); // HTML generálása
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
 
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>.navbar-nav .nav-item {
    margin-right: 15px; /* Távolság a menüpontok között */
}

.navbar-nav .nav-link {
    color: #333; /* Szöveg színe */
    font-weight: bold; /* Félkövér szöveg */
}

.navbar-nav .nav-link:hover {
    color: #007bff; /* Hover szín */
}
</style>
</head>
<body>
<header id="header" class="header sticky-top bg-light">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

    <!-- Logo -->
    <a href="index.php" class="logo d-flex align-items-center">
      <img src="img/logo.png" alt="">
    </a>

    <!-- Menü -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <?php echo $menuHTML; ?> <!-- Generált vízszintes menü -->
      </div>
    </nav>
  </div>
</header>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.mobile-nav-toggle');
  const navMenu = document.querySelector('.navmenu ul');
  const dropdownToggles = document.querySelectorAll('.navmenu .dropdown > a');

  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
      document.body.classList.toggle('mobile-nav-active');
      menuToggle.classList.toggle('open');
    });
  }

  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      const parent = this.parentElement;

      if (!parent.classList.contains('dropdown-active')) {
        document.querySelectorAll('.dropdown-active').forEach(el => el.classList.remove('dropdown-active'));
      }
      parent.classList.toggle('dropdown-active');
    });
  });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
