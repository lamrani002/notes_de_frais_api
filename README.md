# 📌 (Test) Notes de Frais - API avec Laravel 11

Ce projet est une API permettant à un commercial de gérer ses notes de frais pour obtenir des remboursements.  
L'API inclut la gestion des notes de frais (CRUD) et peut être exécutée **en local** ou **via Docker**.  

---

## ⚡ 1. Prérequis

### 📌 En local
Assurez-vous d'avoir installé : **(Si ce n'est pas le cas, accéder au [guide d'installation](INSTALLATION.md) pour le faire )**
- **PHP 8.2** (minimum)
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL 8.0+**
- **Git**

> ℹ️ Vous pouvez aussi utiliser un environnement type XAMPP, WAMP ou MAMP sur Windows/Mac.

### 📌 Avec Docker
- **Docker** >= 20.x
- **Docker Compose** >= 2.x

---

## 🔧 2. Installation et Configuration

### 🖥️ **Option 1 : Installation Locale**

#### 2.1. Installer les dépendances
1. **Cloner le dépôt** :
   ```bash
   git clone https://votre-repo-git.git notes-de-frais
   cd notes-de-frais
   ```
2. **Installer les dépendances PHP**:
    
    ```bash
    composer install
    ```

3. **Copier le fichier .env.example vers .env** :
    
    ```bash
    cp .env.example .env
    ```

4. **Configuration de labase de données dans .env** :
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=notes_de_frais
    DB_USERNAME=ton_utilisateur
    DB_PASSWORD=ton_mot_de_passe
    ```    
### 2.2. Générer la clé et migrer la base
1. **Générer la clé d'application** :
    ```bash
    php artisan key:generate
    ```
2. **Migrer la base de données** :
    ```bash
    php artisan migrate
    ```
3. **Démarrer le serveur Laravel** :
    ```bash
    php artisan serve
    ```
4. **Accéder à l'application** :
Ouvrir un navigateur sur http://127.0.0.1:8000.



### 🐳 **Option 2 : Installation avec Docker**

#### 2.1. Vérifier les fichiers Docker

Ce projet inclut les fichiers suivants :

    Dockerfile
    docker-compose.yml
    docker/nginx.conf

(Déjà présents dans ce dépôt, pas besoin de les créer !)

#### 2.2. Lancer l’environnement Docker

1. **Copier .env.example vers .env** :
    ```bash
    cp .env.example .env
    ```

2. **Construire et démarrer les conteneurs** :
    ```bash
    docker-compose up -d --build
    ```

3. **Installer les dépendances Composer dans Docker** :
    ```bash
    docker-compose exec app composer install
    ```

4. **Générer la clé Laravel** :
    ```bash
    docker-compose exec app php artisan key:generate
    ```

5. **Lancer les migrations** :
    ```bash
    docker-compose exec app php artisan migrate
    ```

6. **Accéder à l’application** :
    Ouvrir un navigateur sur http://localhost:8080.
