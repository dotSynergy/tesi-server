# tesi-server

piccolo software lato server per accettare una stream di dati da storicizzare in un database per la visualizzazione successiva

## librerie

- ```doctrine/orm```
- ```vlucas/phpdotenv```
- ```bloatless/php-websocket```

## installazione

#### requisiti

- un sistema linux (ma dovrebbe funzionare anche su windows/mac)
- qualsiasi mysql o postgresql
- la porta 8000 libera per una websocket (da impostare programmabile? forse
- composer

#### procedimento

1. clona la repo
2. inizializza composer con ``` composer install ```
3. ``` cp .env.example .env ``` e compilalo con il tuo database/credenziali
4. se vuoi esporre ad internet, punta la webroot o una sottocartella di un qualsiasi webserver (io uso apache) correttamente configurato con php a public
5. ``` php -m server.php ``` per avviare la websocket


## problemi/limitazioni

- doctrine per qualche motivo non mi setta i timestamp piú precisi del secondo
- servirebbe qualche metodo di autenticazione per evitare che qualche bot spammi il database
