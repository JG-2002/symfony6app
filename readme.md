1- install bundle : composer install

2- install node js dependencies : npm install node

3- regenerate asset : npm run build

4- generate database : php bin/console doctrine:database:create

5- execute migrations : php bin/console doctrine:migrations:migrate

6- load datafixure : php bin/console doctrine:fixtures:load

7- lancer l'application soit en utilisant la command symfony server:start ( si symfony cli installé) ou en créant un hôte virtuel.
