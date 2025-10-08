@echo off
echo ========================================
echo    Push des modifications sur GitHub
echo ========================================
echo.

echo [1/4] Ajout des fichiers modifies...
git add .

echo.
echo [2/4] Commit des modifications...
git commit -m "Ajout systeme de messagerie, API organisee et configuration auto local/production"

echo.
echo [3/4] Push vers GitHub...
git push origin main

echo.
echo ========================================
echo    Termine !
echo ========================================
echo.
pause
