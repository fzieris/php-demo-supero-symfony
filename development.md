# Entwicklungsschritte

## 1. Leeres Symfony-Projekt erstellen

```sh
composer create-project symfony/skeleton php-demo-supero-symfony
cd php-demo-supero-symfony/
git init
git add .
git commit -m "Neues Symfony 4-Projekt" 
```

Commit im Git-Log: ["Neues Symfony 4-Projekt"](../../commit/master~6)

Das so erstellte Projekt lässt sich in der IDE *PhpStorm* öffnen.
Dabei wird automatisch die Aktivierung vom Composer- und Symfony-Support
vorgeschlagen.
PhpStorm legt eine Reihe von Metadaten an, und einige von denen sollten zur
`.gitignore`
hinzugefügt werden:

```sh
echo "" >> .gitignore
echo ".idea/**/workspace.xml" >> .gitignore
echo ".idea/**/tasks.xml" >> .gitignore
echo ".idea/dictionaries" >> .gitignore
git add .
git commit -m "PhpStorm-Metadaten hinzugefügt"
```

Commit im Git-Log: ["PhpStorm-Metadaten hinzugefügt"](../../commit/master~5)

## 2. Supero-Webseite mit Twig und Doctrine vorbereiten

Symfony 4 wird nur als "Micro-Framework" angeboten, was bedeutet, dass für
jedes Projekt einzeln festgelegt werden kann, welche Komponenten wirklich
nötig sind.

Für die Übernahme des [bekannten Standes der Supero-Webseite][php-demo] sind
zunächst die Template-Engine [Twig][twig] und der OR-Mapper [Doctrine][doctrine]
hilfreich.

```sh
composer require twig doctrine
```

Für ein späteres Deployment Heroku ist es wichtig, die Abhängigkeit vom
PHP-Datenbanktreiber für SQLite explizit anzugeben.

```sh
composer require ext-pdo_sqlite
```

Um flexibel auf statische Ressourcen (wie Bilder oder externe CSS-Dateien)
zugreifen zu können, bietet Symfony die Asset-Komponente, die die
[Twig-Funktion `asset`][asset-twig] anbietet und
[flexibel konfiguriert][asset-config] werden kann.

```sh
composer require asset
```

Das `FrameworkExtraBundle` liefert u.a. die
[hilfreichen Controller-Annotationen][controller-annotations] `@Route`,
`@ParamConverter` und `@Template`:

```sh
composer require sensio/framework-extra-bundle
```

*Fixtures* sind eigentlich "Dummy-Daten", aber für einen "Start-Datensatz" wie
den Supero-Helden funktioniert das folgende Bundle genauso gut: 

```sh
composer require doctrine/doctrine-fixtures-bundle
```

Der [Profiler][profiler] ist nur für Entwicklungsumgebungen nötig
(das teilt man Composer durch `require --dev` mit).
Dadurch kann man im Browser sehr detailliert nachvollziehen, was beim aktuellen
Seiten-Aufruf intern alles passiert ist.

```sh
composer require --dev profiler
```

Quellcode wurde in diesem Commit noch keiner hinzugefügt, sondern nur die
Composer-Konfiguration um die o.g. Komponenten ergänzt.

```sh
git add .
git commit -m "Templating, ORM und Annotationen installiert"
```

Commit im Git-Log: ["Templating, ORM und Annotationen installiert"](../../commit/master~4)

## 3. Inhalte der bisherigen Seite in Symfony-Struktur übernehmen

| Alte Datei | Neuer Ort |
| ------------ | --------- |
| `web/held.php` | [`HeroController::steckbrief()`](src/Controller/HeroController.php) <br> (und [`StatusExtension::status2css()`](src/Twig/StatusExtension.php)) |
| `web/helden.php` | [`HeroController::overview()`](src/Controller/HeroController.php) |
| `web/css/*` | externe Stylesheets (Konfiguration: [`framework.assets.packages.bootstrap`](config/packages/framework.yaml)) |
| `web/img/*` | [`public/img/*`](public/img) |
| `tools/reset_database.php` | [`HeroFixtures`](src/DataFixtures/HeroFixtures.php) & [`ResetDatabaseCommand`](src/Command/ResetDatabaseCommand.php) | 
| `src/Hero.php` | [`Hero`](src/Entity/Hero.php) |
| `templates/base.htm.twig` | [`templates/base.html.twig`](templates/base.html.twig) |
| `templates/_navi.htm.twig` | [`templates/base.html.twig`](templates/base.html.twig) |
| `templates/held.htm.twig` | [`templates/hero/steckbrief.html.twig`](templates/hero/steckbrief.html.twig) |
| `templates/helden.htm.twig` | [`templates/hero/overview.html.twig`](templates/hero/overview.html.twig) |

Neu dazugekommen sind folgende Dateien:

* [`src/Entity/Availability.php`](src/Entity/Availability.php):
  "Pseudo-Enum" für die Helden-Verfügbarkeitsstatuswerte
* [`src/Controller/DefaultController.php`](src/Controller/DefaultController.php):
  Anzeige der Startseite
* [`src/Controller/BookingController.php`](src/Controller/BookingController.php):
  Anzeige der Kontaktseite
* [`templates/index.html.twig`](templates/index.html.twig):
  Template der Startseite
* [`templates/booking/new.html.twig`](templates/booking/new.html.twig):
  Template der Kontaktseite

Mit einem Aufruf von `php bin/console supero:reset-database` kann nun die
SQLite-Datenbank eingerichtet werden.
(Die Datenbank-Verbindung wird in der `.env`-Datei konfiguriert,
[siehe README](README.md).)

Damit das Laden der Fixtures auch einer Produktivumgebung wie Heroku
funktioniert, musste noch das Data-Fixtures-Bundle in der
[`bundles.php`](config/bundles.php) für alle Umgebungen aktiviert werden. 

```sh
php bin/console supero:reset-database
git add .
git commit -m "Alter Stand der Supero-Seite, in Symfony"
```

Commit im Git-Log: ["Alter Stand der Supero-Seite, in Symfony"](../../commit/master~3)

## 4. Kontakt-Formular

Die erste Ergänzung der Supero-Seite ist nun ein echtes Kontaktformular, das die
Auswahl eines Helden erlaubt, und dabei verschiedene Validierer benutzt.

```sh
composer require form validator
git add .
git commit -m "Komponenten für Formulare und Validierung installiert"
```

Commit im Git-Log: ["Komponenten für Formulare und Validierung installiert"](../../commit/master~2)

Das Buchungsformular selbst ist in der Klasse
[`BookHeroType`](src/Form/BookHeroType.php) implementiert.
Das Formular ist im Grunde recht einfach aufgebaut, hat aber ein paar
Besonderheiten:

* Das Feld `name` kriegt beim Laden im Browser automatisch den Fokus, d.h. der
  Textcursor springt dorthin.
* Für alle Felder ist der Type und jeweils ein Constraint angegeben.
  (Alternativ hätte man eine neue Model-Klasse `BookingRequest` anlegen können,
  die als Grundlage für die Formular-Felder und -Constraints dient.)
* Alle Felder nutzen Platzhalter für ihre jeweiligen Labels
  (z.B. `'label' => 'booking.name`)
  und Validierungs-Fehlermeldungen (`'message' => 'booking.name.short`).
  Alternativ hätte man hier auch direkt die jeweiligen Texte eintragen können
  (also z.B. `'label' => 'Ihr Name'`); weil das verwendete Formular-Theme aber
  automatisch die Translation-Komponente von Symfony anspricht, kann man die
  jeweiligen Meldungen auch in die Dateien
  [`messages.de.yaml`](translations/messages.de.yaml) und
  [`validators.de.yaml`](translations/validators.de.yaml) im
  `translations`-Verzeichnis auslagern, und hat somit schon einen Schritt in
  Richtung Mehrsprachigkeit getan. 
* Das Feld `hero` wird als Auswahlliste aller Helden angezeigt.
  * `class`: Gibt die Entity-Klasse an, deren Instanzen aus der Datenbank geholt
    und angezeigt werden sollen (hier: `Hero`).
  * `choice_label`: Gibt an, wie die Instanzen in der Liste angezeigt werden
    sollen (hier: `nameStatus`, d.h. Symfony ruft automatisch die neue
    `Hero::getNameStatus()`-Methode auf).
  * `choice_attr`: Fügt HTML-Attribute hinzu (hier: Listeneinträge werden
    ausgegraut, wenn Helden nicht buchbar sind, ebenfalls über eine neue Methode
    `Hero::isBookable()`).
* Zusätzlich verwendet das `hero`-Feld ein selbstgeschriebenes Constraint namens
  `Bookable`, dass bei der Formularvalidierung (d.h. nach dem Submit)
  sicherstellt, dass der Nutzer auch wirklich keinen nicht buchbaren Helden
  ausgewählt hat.
  
Der zugehörige [`BookingController`](src/Controller/BookingController.php) nimmt
optional einen Helden-Namen über die URL entgegen und verwendet diesen dann für
eine Vorauswahl im Formular.
Ein findiger Anwender (oder ein Programmierfehler) könnte eine URL konstruieren,
die einen nicht buchbaren Helden als Vorauswahl beinhaltet
(wie http://127.0.0.1:8080/buchen/Batman).
Die bisherigen Constraints greifen aber erst beim Submit des Formulars, sodass
man beim Aufrufen einer solchen URL nicht merken würde, dass sich ein Submit gar
nicht lohnt.
Symfonys Formular-Mechanismus erlaubt es aber, sich an bestimmten Punkten
"einzuklinken", z.B. am Zeitpunkt direkt nachdem das Formular serverseitig mit
einer Vorauswahl versehen wurde.
Mit ein paar Zeilen Code kann man diesen Zeitpunkt abpassen, den Validierer für
das Helden-Feld aufrufen, eine Meldung erzeugen und die Vorauswahl zurücknehmen. 

```sh
git commit -m "Buchungsformular implementiert"
```

Commit im Git-Log: ["Buchungsformular implementiert"](../../commit/master~1)

## 5. Administrations-Tools

Als nächsten Schritt wird nun ein neuer
[`AdminController`](src/Controller/AdminController.php) angelegt, der das
Anlegen, Bearbeiten und Löschen von Helden erlaubt.
Das zum Anlegen und Bearbeiten nötige Formular
[`HeroType`](src/Form/HeroType.php) nutzt die `Hero`-Entity-Klasse um die
Datentypen und Validierer für die Felder `name`, `price` und `text` zu bestimmen
und ist daher sehr übersichtlich.

Der [`AdminController`](src/Controller/AdminController.php) ist zwar etwas
länger, aber dafür nicht allzu kompliziert.
Man beachte, dass es noch keinerlei Zugangskontrolle gibt:
Jede/r kann die Adresse http://127.0.0.1:8080/admin aufrufen und die
Helden-Verwaltung benutzen.

```sh
git commit -m "Admin-Tools zur Helden-Verwaltung"
```

Commit im Git-Log: ["Admin-Tools zur Helden-Verwaltung"](../../commit/master)

[php-demo]: https://github.com/fzieris/php-demo-supero
[twig]: https://symfony.com/doc/current/templating.html
[doctrine]: https://symfony.com/doc/current/doctrine.html
[asset-config]: https://symfony.com/doc/current/reference/configuration/framework.html#assets
[asset-twig]: https://symfony.com/doc/current/templating.html#linking-to-assets
[controller-annotations]: https://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/index.html#annotations-for-controllers
[profiler]: https://symfony.com/doc/current/page_creation.html#the-web-debug-toolbar-debugging-dream
