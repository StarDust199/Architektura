Instrukcja
Wszystkie pobrania i instalacje robić w domyślnych ścieżkach

1. Pobrać scoop komendą w powershell

  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
  Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression

3. Pobrać composer

   https://getcomposer.org/download/

5. Pobrać xampp

   https://www.apachefriends.org/pl/download.html

   uruchomić w xamppie Apache i MySQL

7. W phpmyadmin utworzyć nową pustą bazę danych kanbanal
8. Pobrać git

   https://git-scm.com/download/win

10. Pobrać node.js

    https://nodejs.org/en/download

12. Sklonować repozytorium do folderu htdocs w xamppie
13. W VSCode w terminalu wejść w folder Architektura_backend
14. Wpisać

    scoop install symfony-cli

16. Wpisać

    composer install

18. Wpisać

    symfony server:start

20. W nowym terminalu wejść do folderu Architektura_backend i wpisać

    php bin/console doctrine:migrations:migrate

    Teraz wszystkie tabele i przykładowe dane będą już w bazie

22. Przejść do folderu architektura_front
23. Wpisać

    npm install

25. Wpisać

    npm start

Do testowania backendu można użyć Postman, ale nie jest to konieczne
https://www.postman.com/downloads/

Teraz całe środowisko jest skonfigurowane i uruchomione
