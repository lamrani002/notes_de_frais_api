# 📌 Installation des Prérequis pour Laravel 11

Ce guide vous aidera à installer les **prérequis nécessaires** pour exécuter Laravel 11 en **local** sur **Linux, macOS et Windows**.

---

## ⚡ 1. Prérequis

Laravel 11 nécessite les logiciels suivants :

- **PHP 8.2+** avec les extensions requises
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL 8.0+** ou MariaDB
- **Git** (pour cloner le projet)
- **XAMPP** (optionnel, alternative pour MySQL)

---

## 🖥️ **2. Installation sous Linux (Ubuntu/Debian)**

### 📌 2.1. Installer PHP 8.2 et ses extensions
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-mysql unzip git curl
```
Vérifiez l’installation :
```bash
php -v
```
### 📌 2.2. Installer Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```
### 📌 2.3. Installer MySQL
```bash
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql
```
Vérifiez MySQL :
```bash
sudo mysql -u root -p
```
### 📌 2.4. Configurer MySQL pour Laravel

Dans MySQL, exécutez :
```bash
CREATE DATABASE notes_de_frais;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON notes_de_frais.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
### 📌 2.5. Installer XAMPP (Alternative)

```bash
wget https://www.apachefriends.org/xampp-files/8.2.0/xampp-linux-x64-8.2.0-0-installer.run
chmod +x xampp-linux-x64-8.2.0-0-installer.run
sudo ./xampp-linux-x64-8.2.0-0-installer.run
```

Démarrez XAMPP :
```bash
sudo /opt/lampp/lampp start
```
---

## 🍏 **3. Installation sous macOS**

### 📌 3.1. Installer Homebrew

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
### 📌 3.2. Installer PHP 8.2 et ses extensions
```bash
brew install php@8.2
brew install composer
```
**Ajoutez PHP et Composer au PATH** :
```bash
echo 'export PATH="/usr/local/opt/php@8.2/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
php -v
composer --version
```
### 📌 3.3. Installer MySQL
```bash
brew install mysql
brew services start mysql
mysql_secure_installation
```
**Créer la base de données et l’utilisateur** :
```bash
CREATE DATABASE notes_de_frais;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON notes_de_frais.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
### 📌 3.4. Installer XAMPP (Alternative)

**Téléchargez XAMPP** :
https://www.apachefriends.org/fr/index.html

Installez et démarrez MySQL depuis le panneau de contrôle.

---

## 🖥️ 4. Installation sous Windows
### 📌 4.1. Installer PHP 8.2

1. **Téléchargez PHP depuis** : https://windows.php.net/download.
2. **Extrayez l’archive dans C:\php**.
3. **Ajoutez C:\php au PATH Windows** :
    Ouvrez Paramètres > Système > Informations système > Paramètres avancés.
    Cliquez sur Variables d’environnement.
    Dans Path, ajoutez C:\php.
4. **Vérifiez l’installation** :
```bash
php -v
```
## 📌 4.2. Installer Composer

**Téléchargez et installez Composer** : https://getcomposer.org/download/.

Vérifiez l’installation :
```bash
composer --version
```
## 📌 4.3. Installer MySQL

**Téléchargez MySQL Installer depuis** https://dev.mysql.com/downloads/installer/.

**Installez MySQL Server et MySQL Workbench**.

Vérifiez MySQL :
```bash
mysql -u root -p
```
**Créez la base de données et l’utilisateur** :
```bash
CREATE DATABASE notes_de_frais;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON notes_de_frais.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 📌 4.4. Installer XAMPP (Alternative)

**Téléchargez XAMPP** :
https://www.apachefriends.org/fr/index.html

**Lancez le panneau de contrôle XAMPP et démarrez MySQL.**


---
---
## 🐳 Installation de Docker

Docker est requis pour exécuter ce projet avec `docker-compose`.  
Suivez les instructions pour installer Docker selon votre système d'exploitation :

- **Linux (Ubuntu/Debian)** : `sudo apt install -y docker.io docker-compose`
- **macOS** : [Téléchargez Docker Desktop pour Mac](https://www.docker.com/products/docker-desktop/)
- **Windows** : [Téléchargez Docker Desktop pour Windows](https://www.docker.com/products/docker-desktop/)

➡️ **Consultez le guide détaillé ici : [Installation complète de Docker](https://docs.docker.com/manuals/)**  
