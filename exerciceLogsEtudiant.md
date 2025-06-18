# Étudiant

## Objectif

L'objectif de cet exercice est d'apprendre à se connecter à une base de données et à récupérer des données en utilisant PDO et MongoDB.  

## Sujet

Vous devez créer une application de gestion des étudiants qui permet de visualiser et de modifier leurs informations. La base de données contient une table nommée "etudiant" avec les colonnes suivantes :

- ID : identifiant unique de l'étudiant (entier)
- Nom : nom de l'étudiant (chaîne de caractères)
- Prénom : prénom de l'étudiant (chaîne de caractères)
- Date de naissance : date de naissance de l'étudiant (date)
- Adresse email : adresse e-mail de l'étudiant (chaîne de caractères)

Rappel : vous pouvez récupérer l'exercice 1 déjà fait pour éviter de refaire la partie PDO. 

**Taches** : 

**Partie 1** : Ajout de logs 

- Créer une connection avec une BDD MongoDB. 

- Créer une collections `logs` qui aura les champs suivant : 

    - Type : Type du log (enum pouvant être `DEBUG`, `WARN`, `ERR`)
    - Opération : Nom de l'opération effecuté (chaine de caractères). Ex: "Insertion", "Mise à jour",...
    - Message : Détails de l'opération (chaine de caractères). Ex: "Ajout de l'étudiant d'ID : $id"

    Exemple : 

    | Type  | Opération | Message |
    | :--------------- |:---------------| :-----|
    | DEBUG  |  Update  |  Mise à jour de l'étudiant d'ID : 25 |
    | WARN  | Insertion |  Nom saisie incorrect
    | ERR  | Suppression |  Exception lors de la suppression de l'étudiant d'Id : 12|

- Ajouter une fonctionnalité à l'IHM qui est `afficherLogs` qui affichera les 10 dernier logs. 

- Ajouter une fonctionnalité à l'IHM qui est `viderLogs` qui videra la collection de tout ses documents. 


**Partie 2 :** Migration vers MongoDB

Pour bénéficier de la flexibilité d'une base NoSQL, vous devez remplacer la couche PDO par MongoDB.

- Remplacer la connection avec PDO par une connection avec le client Mongodb. 

- Créer une bdd `ecole`. 

- Créer une collection `etudiant` qui impose le nom (chaine), le prenom (chaine) et une quantité maximale de 50 documents dans la collection.

- Modifier la méthode de création d'un étudiant pour autoriser les informations non-essentielles à etre null. (nom et prenom est obligatoire)

- Modifier les différentes fonctionnalité CRUD pour l'adapter à l'utilisation de base MongoDB (dans le repository si vous en avez un). 

    - Si les informations non-essentielles sont entrée lors de la création d'un étudiant sont null alors ne pas créer le champs en BDD. 

**Partie 3 :** Affichage dans le navigateur 

Bien que l'affichage dans le terminal soit sympathique nous sommes malgrès tout très limité. 
Nous allons donc changer notre IHM sur terminal par un affichage HTML/CSS dans notre navigateur  

- Créer une view `StudentView.php` qui sera inclut dans notre `index.php`. Il contiendra : 
    
    - Un formulaire avec les champs : 
        - Id (entier)
        - Nom (chaine)
        - Prenom (chaine)
        - Date de naissance (chaine au format aaaa-mm-jj)
        - Email (chaine)

    - Un bouton d'ajout et un bouton de modification lié à ce formulaire

    - Un champs input demandant l'ID de l'étudiant à supprimer avec un bouton de soumission. 

    - Un tableau qui affiche les étudiants présent en BDD. 

- A chaque soumission, récupérer les données transmissent dans $_POST pour appeler la méthode correspondante et intéragir avec la BDD. 

- Le tableau doit être actualisé à chaque changement effectué.