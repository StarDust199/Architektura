Instrukcja
Wszystkie pobrania i instalacje robić w domyślnych ścieżkach

1. Pobrać scoop komendą w powershell
  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
  Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression

2. Pobrać composer
   https://getcomposer.org/download/

3. Pobrać xampp
   https://www.apachefriends.org/pl/download.html

   uruchomić w xamppie Apache i MySQL

5. W phpmyadmin utworzyć nową pustą bazę danych kanbanal
6. Pobrać git
   https://git-scm.com/download/win

7. Pobrać node.js
   https://nodejs.org/en/download

8. Sklonować repozytorium do folderu htdocs w xamppie
9. W VSCode w terminalu wejść w folder Architektura_backend
10. Wpisać
    scoop install symfony-cli

11. Wpisać
    composer install

12. Wpisać
    symfony server:start

13. W nowym terminalu wejść do folderu Architektura_backend i wpisać
    php bin/console doctrine:migrations:migrate

    Teraz wszystkie tabele i przykładowe dane będą już w bazie

14. Przejść do folderu architektura_front
15. Wpisać
    npm install

16. Wpisać
    npm start

Do testowania backendu można użyć Postman, ale nie jest to konieczne
https://www.postman.com/downloads/

Teraz całe środowisko jest skonfigurowane i uruchomione
