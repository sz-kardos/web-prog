<?php
require __DIR__ . '/../config/database.php';

class SOAPService
{
    private $pdo;

    /**
     * Konstruktor - PDO kapcsolat inicializálása
     */
    public function __construct()
    {
        global $pdo;

        if (!$pdo) {
            throw new Exception("Nem sikerült az adatbázis kapcsolatot létrehozni.");
        }

        $this->pdo = $pdo;
    }

    /**
     * Süti adatok lekérése
     * @return array
     */
    public function getSuti()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM suti");
            $result = $stmt->fetchAll();

            // Ha nincs adat
            if (empty($result)) {
                return ['error' => 'Nincs adat a süti táblában.'];
            }

            return $result;

        } catch (PDOException $e) {
            error_log("getSuti hiba: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Ár adatok lekérése
     * @return array
     */
    public function getAr()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM ar");
            $result = $stmt->fetchAll();

            // Ha nincs adat
            if (empty($result)) {
                return ['error' => 'Nincs adat az ár táblában.'];
            }

            return $result;

        } catch (PDOException $e) {
            error_log("getAr hiba: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Tartalom adatok lekérése
     * @return array
     */
    public function getTartalom()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM tartalom");
            $result = $stmt->fetchAll();

            // Ha nincs adat
            if (empty($result)) {
                return ['error' => 'Nincs adat a tartalom táblában.'];
            }

            return $result;

        } catch (PDOException $e) {
            error_log("getTartalom hiba: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
$options = array(
	"uri" => "http://localhost/cukraszdamasolat/services/SOAPService.php");
	$server = new SoapServer(null, $options);
	$server->setClass('SOAPService');
	$server->handle();
