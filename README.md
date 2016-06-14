Content plugin BIS
==================

Joomla Content plugin pro propojení redakčního systému Joomla 3.X s Brontosauřím Informačním Systémem - BISem. Umožní zobrazení dat z BISu ve článcích. Obsahuje MYR klient pro komponenty a moduly (mod_bis, com_bis) a ikony programů HB.

Instalace
---------
Plugin se instaluje standardním (Joomla) způsobem z přiloženého zip souboru.


Nastavení pluginu
-----------------
- v administraci Joomla po instalaci povolíme plugin *Content - Plugin BIS*
- provedeme základní nastavení pluginu
  - *URL BIS remote* = http://bis.brontosaurus.cz/remote.php
  - *Uživatel* = "zjisti od správce BISu"
  - *Heslo* = "zjisti od správce BISu"
  - *URL zobrazení příloh* = http://bis.brontosaurus.cz/files/psb/ (odtud se stahují přílohy)
- ostatní nastavení jsou dostupná v záložkách
  - **Zobrazení** : CSS třída obalující zobrazenáý kód, formát ikon, ladící výstup (ano/ne)
  - **Odkazy na detail** : relativní URL odkazy (v rámci webu) pro vygenerování odkazu ze seznamu na detail. Typicky stránka se seznamem akcí -> jiná stránka s detailem akce
  - **Šablona seznamu/detailu** : zde je možné definovat HTML5 kód pro zobrazení dat. Oproti standardu HTML5 může obsahovat 2 rozšíření
    - *Proměnné BIS* - ```##nazev##```, takováto hodnota je nahrazena hodnotou pole "nazev" z BISu, tedy v tomto případě názvem tábora nebo názvem základního článku atp.
    - *Funkce* : ```##funkce_plg_bis##```, tyto hodnoty jsou nahrazeny výsledkem funkce. Řetězec "\_plg_bis" slouží pro odlišení od proměnných. Příkladem funkce je například formátovaní datumu nebo zobrazení ikony programu HB
    - *Podmínky / Bloky* : ```--- podminka ---``` kód zobrazený při splnění podmínky ```------```, podmínka začíná definicí, uzavřenou třemí znaky "---", následuje podmíněný kód, ukončuje se 6 znaky "------"

Syntaxe
-------
### Proměnné
```
##promenna##
```
Zobrazí data z BISu v nezměněné podobě tak jak je poskytuje MYR klient (MySQL remote). Více informací získáte od správců BISu.

### Funkce
```
##jmeno_plg_bis*##
```
Zobrazí výsledek funkce.

#### Dostupné funkce
| Název | Popis |
|-------|-------|
| ```##od_plg_bis##``` / ```##do_plg_bis##``` | formátuje datum na DD.MM.YYYY |
| ```##od_do_plg_bis##``` | formátuje datum na "od-do DD-DD.MM-MM.YYYY-YYYY" |
| ```##web_plg_bis##``` | zobrazí URL odkaz na adresu uvedenou v položce "web" nebo "www" |
| ```##kontakt_email__plg_bis##``` | zobrazí mailto: odkaz na e-mailovou adresu |
| ```##vek_od_plg_bis##``` / ```##vek_do__plg_bis##``` | požadavek na věk účastníků ve formátu "X let" |
| ```##vek_odd_plg_bis##``` | požadavek na věk účastníků ve formátu "X let - Y let" |
| ```##even_odd_plg_bis##``` | pomocná funkce pro stylování stránek. Vrací hodnotu even/odd - sudá/lichá. Používá se pro barevné odlišení řádků v seznamu |
| ```##staz_priloh_plg_bis(,cislo)##``` | link pro stažení přílohy, volitelný parametr - číslo přílohy - 1 nebo 2. Bez zadaní se zobrazí první definovaná příloha |
| ```##link_priloha_plg_bis(,cislo)##``` | čísté URL přílohy, volitelný parametr - číslo přílohy - 1 nebo 2. Bez zadaní se zobrazí URL první definované přílohy |
| ```##obrazek_plg_bis(,velikost,cislo_prilohy,css_trida)##``` | zobrazení přílohy jako obrázku, volitelné parametry - velikost (číslo v pixelech), číslo přílohy (1 nebo 2), CSS třída (pro stylování stránek) |
| ```##ikona_prg_plg_bis(,velikost,barva,css_trida)##``` | zobrazení ikony programu HB (AP,APam,...), volitelné parametry - velikost (číslo v pixelech), barva ('tmava','svetla','bront' = zelená HB), CSS třída (pro stylování stránek) |
| ```##ikona_prg_link_plg_bis(,barva)##``` | URL ikony programu HB (AP,APam,...), volitelný parametr - barva ('tmava','svetla','bront' = zelená HB) |
| ```##link_detail_plg_bis##``` | URL pro zobrazení detailu - viz nastavení pluginu |
| ```##text_plg_bis##``` | formátuje "text" - popis akce - do HTML5 kódu. Odstavce dle zalomení řádků |
| ```##intro_text_plg_bis##``` | formátuje "text" - popis akce - do HTML5 kódu. Odstavce dle zalomení řádků. Zobrazí pouze prvních 250 znaků. Vhodné pro stručné seznamy |

### Podmínky / Bloky
```
--- podminka ---
```
Libovolný kód
```
------
```

#### Dostupné podmínky
| Podmínka | Popis |
|----------|-------|
| ```if prilohy``` | jsou definovány nějaké přílohy? |
| ```if prilohy``` | je příloha obrázek (jpg,gif,png)? |
| ```if promenna=nazev``` | je proměnná 'nazev' definována? |
| ```if_not promenna=nazev``` | negace předchozí funkce |
