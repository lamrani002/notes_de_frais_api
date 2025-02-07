#  (Test) Notes de Frais - API avec Laravel 11

Ce projet est une API permettant à un commercial de gérer ses notes de frais pour obtenir des remboursements.  
L'API inclut la gestion des notes de frais (CRUD) et peut être exécutée **en local** ou **via Docker**.  

---

##  1. Prérequis

###  En local
Assurez-vous d'avoir installé : **(Si ce n'est pas le cas, accéder au [guide d'installation](INSTALLATION.md) pour le faire )**
- **PHP 8.2** (minimum)
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL 8.0+**
- **Git**

> ℹ️ Vous pouvez aussi utiliser un environnement type XAMPP, WAMP ou MAMP sur Windows/Mac.

###  Avec Docker
- **Docker** >= 20.x
- **Docker Compose** >= 2.x

---

##  2. Installation et Configuration

###  **Option 1 : Installation Locale**

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
    php artisan migrate --seed
    ```
3. **Démarrer le serveur Laravel** :
    ```bash
    php artisan serve
    ```
4. **Accéder à l'application** :
Ouvrir un navigateur sur http://127.0.0.1:8000.



###  **Option 2 : Installation avec Docker**

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
    **IMPORTANT !!(N'oublier pas de changer  DB_HOST=127.0.0.1 en  DB_HOST=db)**

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
    docker-compose exec app php artisan migrate --seed
    ```

6. **Accéder à l’application** :
    Ouvrir un navigateur sur http://localhost:8080.

## 🔐 3. Système d'Authentification

L'API utilise **Laravel Sanctum** pour la gestion des tokens d'authentification.

###  Logique d'authentification
- **Un utilisateur peut s'inscrire et se connecter.**
- **Un token est généré à la connexion.**
- **Toutes les routes sont protégées par Sanctum.**
- **Seul l'utilisateur `ID=1` peut créer, modifier ou supprimer des notes de frais.**
- **Les autres utilisateurs peuvent uniquement consulter les notes de frais.**

###  Endpoints d'Authentification
| Méthode | Route              | Description              |
|----------|-------------------|-------------------------|
| POST     | /api/register     | Inscription             |
| POST     | /api/login        | Connexion (renvoie un token) |
| POST     | /api/logout       | Déconnexion            |

**Exemple de connexion :**
```bash
curl -X POST http://127.0.0.1:8000/api/login -d '{"email": "test@example.com", "password": "password"}' -H "Content-Type: application/json"
```
**Réponse :**
```json
{
    "user": {"id": 1, "email": "test@example.com"},
    "token": "abcdef123456789"
}
```

---

## 📡 4. Endpoints de l’API

| Méthode | Route                   | Description                               |
|----------|-------------------------|-------------------------------------------|
| GET      | /api/expense-notes      | Récupère toutes les notes de frais     |
| GET      | /api/expense-notes/{id} | Récupère une note spécifique         |
| POST     | /api/expense-notes      | Crée une nouvelle note (Admin uniquement) |
| PUT      | /api/expense-notes/{id} | Met à jour une note (Admin uniquement)  |
| DELETE   | /api/expense-notes/{id} | Supprime une note (Admin uniquement)    |

**Exemple d'appel API avec token :**
```bash
curl -X GET http://127.0.0.1:8000/api/expense-notes -H "Authorization: Bearer abcdef123456789"
```

---

## 🛠 5. Exécuter les tests

###  Tests unitaires et fonctionnels
```bash
php artisan test
```
###  Exécuter un test spécifique
```bash
php artisan test --filter ExpenseNoteControllerTest
```

---

##  Conclusion
L'API de gestion des notes de frais est maintenant complètement fonctionnelle et sécurisée avec Sanctum.


