<?php
if (!function_exists('buildMenu')) {
    function buildMenu($conn) {
        $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'latogato';

        $role_map = [
            'latogato' => 100,
            'regisztralt' => 110,
            'admin' => 111
        ];

        $role_value = $role_map[$user_role];

        // Menüpontok lekérdezése
        $sql = "SELECT nev, url, szulo, sorrend, jogosultsag FROM menu 
                WHERE jogosultsag & ? > 0
                ORDER BY sorrend";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $role_value);
        $stmt->execute();
        $result = $stmt->get_result();

        $menu_items = [];
        while ($row = $result->fetch_assoc()) {
            $menu_items[] = $row;
        }

        $menu = '<ul class="navbar-nav">';

        foreach ($menu_items as $item) {
            // Kilépés csak akkor, ha a felhasználó be van jelentkezve
            if ($item['url'] === 'logout.php' && !isset($_SESSION['user_id'])) {
                continue;
            }

            // Bejelentkezés és Regisztráció csak akkor, ha nincs bejelentkezve
            if (($item['url'] === 'login.php' || $item['url'] === 'register.php') && isset($_SESSION['user_id'])) {
                continue;
            }

            $menu .= '<li class="nav-item">';
            $menu .= '<a class="nav-link" href="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['nev']) . '</a>';
            $menu .= '</li>';
        }

        $menu .= '</ul>';
        return $menu;
    }
}
?>
