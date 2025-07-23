# FaitMaison 🍲

Un site web de partage de recettes fait maison, en PHP avec MySQL et Bootstrap.

---

## ✅ Prérequis

Avant de commencer, assurez-vous d’avoir :

- [XAMPP](https://www.apachefriends.org/index.html) ou [MAMP](https://www.mamp.info/) installé (serveur Apache + MySQL + PHP).
- Un navigateur web moderne.
- Le fichier `faitmaison.sql` (base de données).

---

## 🚀 Lancer le site en local

1. **Placer les fichiers du projet dans le bon dossier :**

   - Avec **XAMPP** : Copiez tout le dossier du projet dans `C:\xampp\htdocs\faitmaison`
   - Avec **MAMP** : Copiez le dossier dans `Applications/MAMP/htdocs/faitmaison`

2. **Démarrer les services Apache et MySQL** via le panneau de contrôle XAMPP ou MAMP.

3. **Ouvrir le site dans votre navigateur :**

```
http://localhost/faitmaison
```

---

## 🗃️ Importer la base de données avec phpMyAdmin

1. Ouvrir **phpMyAdmin** :

```
http://localhost/phpmyadmin
```

2. Cliquer sur l’onglet **Importer**.

4. Cliquer sur **"Choisir un fichier"**, sélectionner le fichier `faitmaison.sql` de ton projet.

5. Laisser les paramètres par défaut et cliquer sur **Import**.

---

## ⚙️ Configuration de la base de données

Vérifie que le fichier `config/mysql.php` contient les bons identifiants de connexion :

```php
<?php
$mysqlClient = new PDO(
 'mysql:host=localhost;dbname=faitmaison;charset=utf8',
 'root',
 '' // ou 'root' si vous êtes sur MAMP
);
?>
```

--- 

## 📁 Arborescence

```
.
├── README.md
├── config
│   └── mysql.php
├── contact.php
├── databaseconnect.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── isConnect.php
├── login.php
├── logout.php
├── recipes_create.php
├── recipes_delete.php
├── recipes_post_create.php
├── recipes_post_delete.php
├── recipes_post_update.php
├── recipes_read.php
├── recipes_update.php
├── sql
│   └── FaitMaison.sql
├── submit_contact.php
├── submit_login.php
├── uploads
└── variables.php
```

--- 

### ✉️Contact

Projet réalisé par squach90 avec l'aide d'OpenClassroom.
Pour toute question, vous pouvez ouvrir une issue ou m’envoyer un message.
