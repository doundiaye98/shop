# Script PowerShell pour créer la table users
Write-Host "Création de la table users dans la base de données shop..." -ForegroundColor Green

# Chemin vers MySQL dans WAMP (ajustez selon votre version)
$mysqlPath = "C:\wamp\bin\mysql\mysql8.0.31\bin\mysql.exe"

# Vérifier si le chemin existe
if (Test-Path $mysqlPath) {
    Write-Host "MySQL trouvé à: $mysqlPath" -ForegroundColor Yellow
    
    # Lire le contenu du fichier SQL
    $sqlContent = Get-Content "create_users_table.sql" -Raw
    
    # Exécuter la commande MySQL
    try {
        & $mysqlPath -u root -p shop -e $sqlContent
        Write-Host "Table users créée avec succès!" -ForegroundColor Green
    }
    catch {
        Write-Host "Erreur lors de l'exécution: $_" -ForegroundColor Red
    }
} else {
    Write-Host "MySQL non trouvé à: $mysqlPath" -ForegroundColor Red
    Write-Host "Veuillez ajuster le chemin dans le script ou utiliser phpMyAdmin" -ForegroundColor Yellow
}

Write-Host "Appuyez sur une touche pour continuer..." -ForegroundColor Cyan
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown") 