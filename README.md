# Svobodné rámečky
 PHP nástroj na vkládání rámečků do profilových fotografií na sociální sítě
 Založeno na [PF-Gen project](https://github.com/AloisSeckar/PF-Gen)

## Funkce
Překryje vložený vstupní obrázek (JPG nebo PNG) rámečkem `bg0.png`, `bg1.png` nebo `bg2.png`. Zdrojový obrázek je upraven na čtvercový tvar a zmenšen na 1000x1000 px pokud je větší. Rámeček (měl by být částečně průhledný) se následně vloží přes upravený zdroj. Výsledek je uložen jako `tmp/output.png` a zobrazen na stránce. Tento obrázek se trvale neukládá, příští spuštění skriptu ho přepíše.

## Použití
Stačí nahrát soubory na libovolný hosting s PHP (>= 5.5.0 kvůli funkcím `imagecrop` a `imagescale`) a zobrazit `index.php`.
