# Symfony-Demo "Supero"

Dieses Git-Repository gehört zur Lehrveranstaltung [Webentwicklung][web] an der
HTW Berlin.

## Inhaltsverzeichnis

* [Installation](#installation)
  1. [Klonen und Abhängigkeiten installieren](#1-klonen-und-abhängigkeiten-installieren)
  2. [Umgebungsvariablen setzen](#2-umgebungsvariablen-setzen)
  3. [Datenbank aufsetzen](#3-datenbank-aufsetzen)
  4. [Anwendung starten](#4-anwendung-starten)
* [Entwicklung](#entwicklungsstände)
* [Heroku-Deployment](#deployment-auf-heroku)

## Installation

### 1. Klonen und Abhängigkeiten installieren

Zunächst müssen Sie das Projekt klonen und dann alle Abhängigkeiten mit
[Composer][composer] installieren.
```sh
git clone https://github.com/fzieris/php-demo-supero-symfony.git supero-symfony
cd supero-symfony
composer install
```

### 2. Umgebungsvariablen setzen

Jede Installation eines Symfony-Projekts befindet sich in einer eigenen
Umgebung, welche konfiguriert werden muss.

Erstellen Sie im Projektverzeichnis eine `.env`-Datei.
Kopieren Sie dafür einfach die `.env.dist`-Datei.
```sh
cp .env.dist .env
```

Öffnen Sie die neue `.env`-Datei mit einem Text-Editor und setzen Sie bei Bedarf
die folgenden drei Werte:
* **`APP_ENV`**: Erlaubte Werte:
  * `dev` für ein Entwicklungssystem (schaltet viele Debugging-Informationen ein)
  * `prod` für ein Produktivsystem (schneller, aber keine Debugging-Informationen)
* **`APP_SECRET`**: Wird von Symfony benutzt, um Funktionen, die zufällige Werte
  brauchen, ein wenig zufälliger zu machen
  (siehe [Eintrag in der Symfony-Referenz][app-secret]).
  Sie können sich z.B. auf Seiten wie http://nux.net/secret einfach zufällige
  Strings erzeugen lassen.
* **`DATABASE_URL`**: Eine Datenbank-URL. Nutzen Sie für dieses einfach Projekt
  einfach eine SQLite-Datenbank, indem Sie folgenden Wert setzen:
  `sqlite:///%kernel.project_dir%/var/data.db`
     
Die `.env`-Datei bleibt dank [.gitignore](.gitignore) lokal und wird nicht
mit-committet.
     
### 3. Datenbank aufsetzen

Rufen Sie im Projektverzeichnis (im Beispiel von oben: `supero-symfony/`)
folgenden Befehl auf:
```
php bin/console supero:reset-database
```
Die erwartete Ausgabe sieht etwas so aus:
```
Dropped database for connection named F:\git\supero-symfony/var/data.db
Created database F:\git\supero-symfony/var/data.db for connection named default
ATTENTION: This operation should not be executed in a production environment.

Creating database schema...
Database schema created successfully!
  > purging database
  > loading App\DataFixtures\HeroFixtures
```
(Der Befehl `supero:reset-database` gehört nicht zu Symfony, sondern ist eigens
 für dieses Demo-Projekt geschrieben worden.
 Er ruft aber nacheinander vier Symfony-Befehle auf:
 evtl. bestehende Datenbank löschen, neue Datenbank anlegen,
 Datenbank-Schema erstellen und Startdaten einfügen.)

### 4. Anwendung starten

Am einfachsten geht das Starten der Webanwendung über den PHP-eigenen Webserver:
```sh
php -S 127.0.0.1:8080 -t public
```
Statt `8080` können Sie auch einen anderen freien Port verwenden.
Rufen Sie in Ihrem Web-Browser die Supero-Seite auf: http://127.0.0.1:8080

In Ihrer Konsole sehen Sie alle Aufrufe, die an den Webserver gehen.
Wenn Sie den Webserver stoppen wollen, tippen Sie in die Konsole `CTRL`+`C`.

## Entwicklungsstände

Die einzelnen Commits können im [Git-Log](../../commits/master) eingesehen werden;
die jeweiligen Schritte werden in [diesem Dokument](development.md) erläutert.

## Deployment auf Heroku

Sie können die Supero-Webseite auch einfach auf Heroku deployen.

Legen Sie erst eine neue Heroku-Instanz an:
```sh
heroku create
```

Ihre `.env`-Datei ist nur lokal und in einer Heroku-Instanz können Sie nicht
einfach (und vor allem nicht dauerhaft) eine Datei anlegen, die nicht unter
Versionskontrolle ist.
Verwenden Sie daher für Ihre Heroku-Instanz Umgebungsvariablen.
Diese können Sie einfach [von der Kommandozeile setzen][heroku-settings]:
```sh
heroku config:set APP_ENV=prod
heroku config:set APP_SECRET={ein frisches, zufälliges Secret}
heroku config:set DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
```

Alternativ können Sie die Umgebungsvariablen auch über das Webinterface einsehen
und verändern:
[Heroku Dashboard][heroku-dashboard] > Ihre App > *Settings > Config Variables*.

Jetzt können Sie ganz wie gewohnt deployen:
```sh
git push heroku
```

Das war's! 
Bei jedem Deployment wird die Datenbank in der Heroku-Instanz zurückgesetzt
(weil im [Procfile](Procfile) der Befehl `supero:reset-database` automatisch vor
 dem Start des Apache Webservers aufgerufen wird).
     
[web]: http://www.zieris.net/webdev/
[composer]: https://getcomposer.org/
[heroku-dashboard]: https://dashboard.heroku.com/apps
[heroku-settings]: https://devcenter.heroku.com/articles/config-vars#setting-up-config-vars-for-a-deployed-application
[app-secret]: http://symfony.com/doc/current/reference/configuration/framework.html#secret
