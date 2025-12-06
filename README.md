# LEMP + Zabbix Docker Projekt

Ez a projekt egy **LEMP stack** (Linux, Nginx, MariaDB, PHP) alapú környezetet hoz létre **docker-compose** segítségével, terheléselosztással és **Zabbix monitoringgal**.  
A rendszer **SSL/TLS titkosítást** használ a biztonság érdekében, és tartalmaz két webszervert, amelyek a terhelést egy Nginx alapú load balancer segítségével osztják el.

---

## Főbb komponensek

A projekt **6 Docker szolgáltatást** tartalmaz:

| Szolgáltatás       | Leírás |
|-------------------|--------|
| **load-balancer**  | Nginx alapú terheléselosztó, amely a két webszerver között osztja el a kéréseket. Proxy cache támogatással rendelkezik. |
| **webszerver-1**   | PHP-FPM + Nginx + Zabbix agent telepítve. Az adatbázishoz kapcsolódik a `.env` fájlban megadott adatokkal. |
| **webszerver-2**   | Ugyanaz, mint webszerver-1, redundancia és terheléselosztás céljából. |
| **mariadb**        | MariaDB adatbázis SSL támogatással, az inicializáláshoz `create_db.sql` fut le. |
| **zabbix-server**  | Zabbix szerver MySQL/MariaDB alapú adattárolással, TLS kapcsolattal. |
| **zabbix-web**     | Zabbix frontend Nginx + PHP-FPM, a szerveradatok megjelenítéséhez. |

---

## Környezeti változók

A projekt használatához állítsd be a **`.env`** fájlt:

- MARIADB_HOST=mariadb
- MARIADB_DATABASE=lemp_app
- MARIADB_USER=lemp_user
- MARIADB_PASSWORD=eros_jelszo

- DB_SERVER_HOST=mariadb
- DB_SERVER_PORT=3306
- DB_SERVER_DBNAME=zabbix
- DB_SERVER_DBUSER=zabbix
- DB_SERVER_DBPASSWORD=zabbix
- DB_SERVER_ROOT_PASSWORD=root_jelszo

ZBX_SERVER_HOST=zbx-server

---

## Adatbázis inicializálás

A `mariadb/init/create_db.sql` automatikusan létrehozza:

- `lemp_app` adatbázist a webszerverekhez  
- `hosts` táblát alapértelmezett online állapotokkal  
- `zabbix` adatbázist és felhasználót SSL-sel  

---

## Webszerver konfiguráció

A **Dockerfile** tartalmazza:

- PHP-FPM 8.2  
- Nginx  
- MariaDB client  
- Zabbix agent  
- Alap PHP modulok (`mysqli`, `pdo_mysql`)  

Az **entrypoint script** (`entrypoint.sh`) beállítja a Zabbix agent konfigurációját és elindítja a szolgáltatásokat.

---

## Load Balancer

Az `lb-nginx/nginx.conf` konfiguráció:

- Figyel a **80-as porton**  
- Két webszervert használ **upstreamként**  
- Proxy cache engedélyezve  
- HTTP header-ek mutatják a cache státuszt és a kiszolgáló IP-t  
- PHP kéréseket FastCGI-vel továbbít a webszerverekhez  

---

## SSL/TLS

- Minden konténer SSL támogatással fut  
- A MariaDB és Zabbix kommunikáció TLS-en keresztül történik  
- Tanúsítványok a `mariadb/ssl` könyvtárban találhatók  

---

## Monitoring

- **Zabbix agent** telepítve a webszervereken  
- **Zabbix server** kezeli az agentek adatait  
- Frontend a `zabbix-web` konténerben érhető el  

---

## Hálózat és Volumes

- **Hálózat:** `lemp-net` (bridge driver)  
- **Volumes:**  
  - `mariadb_data`: adatbázis perzisztencia  
  - `lb_cache`: load balancer gyorsítótár  

Készítette: Veres Szabolcs
