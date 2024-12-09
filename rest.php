<?php
include_once 'header.php'; 
require_once 'auth.php';
requireRole('admin');
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Süti Kezelés</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        section {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            display: flex;
            justify-content: space-between;
        }

        form input[type="text"], form input[type="checkbox"] {
            width: 100%;
            max-width: 400px;
        }

        button {
            padding: 10px 20px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            margin: 0 auto; /* Gomb középre igazítása */
        }

        button:hover {
            background-color: orangered;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f9f9f9;
        }

        pre {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
            overflow-x: auto;
        }
    </style>
    <script>
        async function getSutik() {
            const response = await fetch('controllers/suti_api_controller.php');
            const data = await response.json();

            const resultTable = document.getElementById('resultTable');
            resultTable.innerHTML = '';

            if (data.error) {
                resultTable.innerHTML = `<tr><td colspan="3">${data.error}</td></tr>`;
            } else {
                data.forEach(suti => {
                    const row = `
                        <tr>
                            <td>${suti.id}</td>
                            <td>${suti.nev}</td>
                            <td>${suti.tipus}</td>
                            <td>${suti.dijazott ? 'Igen' : 'Nem'}</td>
                        </tr>`;
                    resultTable.innerHTML += row;
                });
            }
        }

        async function createSuti() {
            const nev = document.getElementById('nev').value;
            const tipus = document.getElementById('tipus').value;
            const dijazott = document.getElementById('dijazott').checked ? 1 : 0;
            const response = await fetch('controllers/suti_api_controller.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nev, tipus, dijazott })
            });
            const data = await response.json();
            alert(data.message);
            getSutik();
        }

        async function updateSuti() {
            const id = document.getElementById('update_id').value;
            const nev = document.getElementById('update_nev').value;
            const tipus = document.getElementById('update_tipus').value;
            const dijazott = document.getElementById('update_dijazott').checked ? 1 : 0;
            const response = await fetch(`controllers/suti_api_controller.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nev, tipus, dijazott })
            });
            const data = await response.json();
            alert(data.message);
            getSutik();
        }

        async function deleteSuti() {
            const id = document.getElementById('delete_id').value;
            const response = await fetch(`controllers/suti_api_controller.php?id=${id}`, { method: 'DELETE' });
            const data = await response.json();
            alert(data.message);
            getSutik();
        }
        async function getSutiById() {
            const id = document.getElementById('get_id').value;

            const response = await fetch(`controllers/suti_api_controller.php?id=${id}`);
            const data = await response.json();

            const resultTable = document.getElementById('sutiByIdResult');
            resultTable.innerHTML = '';

            if (data.error) {
                resultTable.innerHTML = `<tr><td colspan="4">${data.error}</td></tr>`;
            } else {
                const row = `
                    <tr>
                        <td>${data.id}</td>
                        <td>${data.nev}</td>
                        <td>${data.tipus}</td>
                        <td>${data.dijazott ? 'Igen' : 'Nem'}</td>
                    </tr>`;
                resultTable.innerHTML = row;
            }
        }
    </script>
</head>
<body>
    <h1>Süti Kezelés</h1>
    <section>
        <h2>Süti Lekérdezése ID alapján</h2>
        <form onsubmit="event.preventDefault(); getSutiById();">
            <label>ID: <input type="text" id="get_id" required></label>
            <button type="submit">Lekérdezés</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Típus</th>
                    <th>Díjazott</th>
                </tr>
            </thead>
            <tbody id="sutiByIdResult"></tbody>
        </table>
    </section>
    <section>
    <h2>Összes Süti Lekérése</h2>
    <!-- A gomb tartalmazó div középre igazításához -->
    <div style="text-align: center;">
        <button onclick="getSutik()" style="padding: 10px 25px; background-color: orange; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Lekérdezés/Frissítés
        </button>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #ddd;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px;">ID</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Név</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Típus</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Díjazott</th>
            </tr>
        </thead>
        <tbody id="resultTable">
            <!-- Dinamikusan betöltött adatok -->
        </tbody>
    </table>
</section>

    <section>
        <h2>Új Süti Létrehozása</h2>
        <form onsubmit="event.preventDefault(); createSuti();">
            <label>Név: <input type="text" id="nev" required></label>
            <label>Típus: <input type="text" id="tipus" required></label>
            <label>Díjazott: <input type="checkbox" id="dijazott"></label>
            <button type="submit">Létrehozás</button>
        </form>
    </section>

    <section>
        <h2>Süti Módosítása</h2>
        <form onsubmit="event.preventDefault(); updateSuti();">
            <label>ID: <input type="text" id="update_id" required></label>
            <label>Név: <input type="text" id="update_nev" required></label>
            <label>Típus: <input type="text" id="update_tipus" required></label>
            <label>Díjazott: <input type="checkbox" id="update_dijazott"></label>
            <button type="submit">Módosítás</button>
        </form>
    </section>

    <section>
        <h2>Süti Törlése</h2>
        <form onsubmit="event.preventDefault(); deleteSuti();">
            <label>ID: <input type="text" id="delete_id" required></label>
            <button type="submit">Törlés</button>
        </form>
    </section>
</body>
</html>

<?php
include 'footer.php'; 
?>
