<?php
header("Content-Type: application/json");
require_once '../config/database.php';
require_once '../auth.php';
requireRole('admin');



//Az API metódus feldolgozása
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

//Az ID-t a query paraméterekből szerezzük meg
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            //Egy adott süti értékeinek lekérése
            $stmt = $pdo->prepare("SELECT * FROM suti WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            echo json_encode($result);
        } else {
            //Összes sütiérték lekérése
            $stmt = $pdo->query("SELECT * FROM suti");
            $result = $stmt->fetchAll();
            echo json_encode($result);
        }
        break;

    case 'POST':
        //Új süti létrehozása
        $stmt = $pdo->prepare("INSERT INTO suti (nev, tipus, dijazott) VALUES (?, ?, ?)");
        $stmt->execute([$input['nev'], $input['tipus'], $input['dijazott']]);
        echo json_encode(['message' => 'Új süti sikeresen létrehozva!']);
        break;

    case 'PUT':
        //Süti módosítása
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID szükséges a frissítéshez']);
            break;
        }
        $stmt = $pdo->prepare("UPDATE suti SET nev = ?, tipus = ?, dijazott = ? WHERE id = ?");
        $stmt->execute([$input['nev'], $input['tipus'], $input['dijazott'], $id]);
        echo json_encode(['message' => 'Süti frissítve!']);
        break;

    case 'DELETE':
        //Süti törlése
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID szükséges a törléshez']);
            break;
        }
        $stmt = $pdo->prepare("DELETE FROM suti WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Süti törölve!']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Metódus nem támogatott']);
}
