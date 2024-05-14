# API server pro mobilní aplikaci pro osobní evidenci zdravotní péče

- Autor: [Vojtěch Voleman](https://github.com/vvoleman)
- Vedoucí práce: [Ing. Jana Vitvarová, Ph.D.](https://www.fm.tul.cz/personal/jana.vitvarova)

## Zadání práce

| Název tématu          | Aplikace pro osobní evidenci zdravotní péče |
|-----------------------|---------------------------------------------|
| Název tématu anglicky | Personal healthcare app                     |

### Zásady pro vypracování

1. Proveďte rešerši existujících aplikací pro evidenci osobní zdravotní péče.
2. Analyzujte potřeby pacientů při vybraných akutních i chronických zdravotních problémech.
3. Analyzujte využitelná veřejně dostupná zdravotnická data, jako například informace o lécích, či
   kódové označení nemocí. Dále zdroje dat, které má pacient k dispozici, jako například přehledy
   zdravotní péče od zdravotní pojišťovny nebo papírové lékařské zprávy.
4. Vytvořte funkční specifikaci aplikace dle zjištěných potřeb.
5. Navrhněte technické řešení a vyberte platformu s důrazem na bezpečné nakládání s citlivými daty.
6. Vytvořte interaktivní prototyp uživatelského rozhraní.
7. Implementujte a testujte prototyp aplikace procesem CI/CD.
8. Proveďte uživatelské testování a výsledky vyhodnoťte.

### Literatura

1. ECKEL, Bruce a Svetlana ISAKOVA. Atomic Kotlin. Mindview, 2021. ISBN 978-0-9818725-5-1.
2. MARTIN, Robert C. Clean Code: A Handbook of Agile Software Craftsmanship. Pearson, 2008. ISBN
   0132350882.
3. MKN-10 klasifikace [online]. [cit. 2022-10-12]. Dostupné z: https://mkn10.uzis.cz

## Popis

Tento projekt slouží jako API server pro [mobilní aplikaci](https://github.com/vvoleman/phr_android) vyvíjenou v rámci
bakalářské práce na FM TUL, které nabízí zpracovaná data z veřejně dostupných zdrojů.

### 1. Synchronizace dat z veřejných zdrojů

Jelikož se tyto data periodicky aktualizují, musí server umožňovat jejich synchronizaci. Pro tento účel byly vytvořeny
příkazy:

```shell
# Runs all syncers
php bin/console syncer:all:run

# Runs individual syncers
php bin/console syncer:mkn:run
php bin/console syncer:nrpzs:run
php bin/console syncer:sukl:run
```

Tyto příkazy je možné spouštět ručně, jsou ale také naplánovány v cronu, aby se vykonávaly pravidelně pátý den v měsíci
ve 3:00. Doba trvání synchronizace se při měření pohybovala kolem minuty na lokálním zařízení a kolem 90 sekund na
vzdáleném stroji.

### 2. Poskytování dat pomocí API

Pro poskytování dat se využívají controllery v Symfony, které zajišťují zpracování požadavků a odpovědí. Dokumentaci pro
API lze najít v souboru [schema.json](schema.json), v interaktivní podobě pak na
adrese [phr.vvoleman.eu/api/doc](https://phr.vvoleman.eu/api/doc).

## Technologie

API server byl napsal v jazyce PHP 8.1.1 s využitím frameworku Symfony 6.2.14. Pro ukládání dat byla použita databáze
MySQL. Pro příkazy je využita knihovna `symfony/console`, pro získání odkazů na soubory dat `symfony/dom-crawler`.

Celé prostředí je nasazeno v Dockeru (viz [docker-compose.yml](docker-compose.yml)).

## Spuštění

API server je dostupný na adrese [phr.vvoleman.eu/api](https://phr.vvoleman.eu/api). Pro lokální spuštění je třeba mít
nainstalovaný Docker a Docker Compose. Před spuštěním je potřeba vytvořit soubor `.env` podle vzoru `.env.example`. Poté
je
možné spustit server příkazem:

```shell
docker-compose up
```

Pokud je potřeba provádět příkazy nad serverem, stačí se připojit do kontejneru:

```shell
docker exec -it phr_backend bash
```

a poté spouštět příkazy (např. synchronizovat data).

## Troubleshooting

### Chybějící složky

Pokud se při spuštění objeví chyba, že některé složky neexistují (např. var/data/uzis, /var/data/sukl, /var/data/nrpzs),
je třeba je vytvořit. Za normálních okolností se složky vytvářejí samy, při nedostatečném oprávnění však může nastat
problém. Tyto složky je tedy potřeba vytvořit ručně a nastavit jim správná oprávnění (775, při nejhorším 777)

### Chybějící composer závislosti

Při prvotním spuštění se provádí příkaz `composer install`, který stáhne všechny závislosti. Pokud se však objeví chyba
týkající se chybějících závislostí, je třeba spustit tento příkaz ručně:

```shell
docker exec -it phr_backend composer install
```

### Paměť vyčerpána při provádění synchronizace

K chybové hlášce
`
Error: Allowed memory size of 134217728 bytes exhausted (tried to allocate 23371010 bytes)
`
dochází ve vývojářském prostředí, jelikož se Doctrine snaží loggovat všechny dotazy. Řešením je buď přepnout prostředí
do produkčního v `.env`

```dotenv
# APP_ENV=dev
APP_ENV=prod
```

nebo spouštět příkazy s parametrem `--no-debug`:

```shell
php bin/console syncer:all:run --no-debug
```

### Chyba při spouštění dotazů v dokumentaci API

Pokud se při spuštění dotazů v dokumentaci API objeví chyba `Failed to fetch`, zkontrolujte na jaký server je dotaz
odesílán. Dokumentace obsahuje dva servery, jeden pro lokální spuštění a druhý pro produkční. Toto nastavení lze nalést
na začátku stránky s popiskem "Servers".
V případě, že by nedošlo k vyřešení problému, kontaktujte prosím autora. Je možné, že došlo k výpadku serveru.

**Poznámka:** Endpoint `/api/medical-product/document` přesměrovává na stránku s příbalovým letákem léku. Dokumentace ale
zobrazí jen chybovou hlášku. Jedná se o očekávané chování. Pro testování funkčnosti je možné přistoupit na vygenerovaný
Request URL manuálně z prohlížeče.


