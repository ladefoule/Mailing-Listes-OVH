

PRÉREQUIS

    - installer php (installer et activer le module php-imap)
    - installer composer
    - avoir un nom de domaine (ex : mailing.example.com)
    - créer des accès OVH sur l'API : https://eu.api.ovh.com/createToken/
      (avec les méthodes get/post/delete sur l'url /email/domain/VOTRENOMDEDOMAINE/mailingList/*)

INSTALLATION

    1. $ composer install
    2. $ cp config.example.php config.php
    3. configurer le fichier config.php en fonction de votre cas

