<?php
if (class_exists('SoapClient')) {
    echo "SOAP modul engedélyezve!";
} else {
    echo "SOAP modul nincs engedélyezve.";
}
?>
