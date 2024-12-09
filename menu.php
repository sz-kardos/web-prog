<?php
require_once 'config/database.php'; // Adatbázis kapcsolat

// Menüelemek lekérése az adatbázisból
function fetchAllMenuItems($pdo) {
    $sql = "SELECT id, parent_id, name, link, is_active FROM menu WHERE is_active = 1 ORDER BY parent_id, id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hierarchikus menüstruktúra építése
function buildMenuTree(array $menuItems, $parentId = null) {
    $tree = [];
    foreach ($menuItems as $item) {
        if ($item['parent_id'] == $parentId) {
            $children = buildMenuTree($menuItems, $item['id']);
            if ($children) {
                $item['children'] = $children;
            }
            $tree[] = $item;
        }
    }
    return $tree;
}

function generateMenuHTML(array $menuTree) {
    $html = '<ul class="navbar-nav ms-auto d-flex">'; // Vízszintes menü kialakítása
    foreach ($menuTree as $item) {
        $hasChildren = !empty($item['children']);
        if ($hasChildren) {
            $html .= '<li class="nav-item dropdown">';
            $html .= '<a class="nav-link dropdown-toggle" href="#" id="dropdown' . $item['id'] . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
            $html .= htmlspecialchars($item['name']);
            $html .= '</a>';
            $html .= '<ul class="dropdown-menu" aria-labelledby="dropdown' . $item['id'] . '">';
            $html .= generateMenuHTML($item['children']);
            $html .= '</ul>';
        } else {
            $html .= '<li class="nav-item">';
            $html .= '<a class="nav-link" href="' . ($item['link'] ? htmlspecialchars($item['link']) : '#') . '">' . htmlspecialchars($item['name']) . '</a>';
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

?>
