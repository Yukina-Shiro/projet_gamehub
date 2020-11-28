# MVC-Core
![Logo UCA - IUT|](images/Logo_IUT-icon.png)
## Introduction
Le Patron de Conception  « MVC » s'inscrit dans le cadre d'une architecture à 3 niveaux :

* Données : données persistantes (e.g. base de données) ;
* Service : logique de l'application ;
* Présentation : HTML, CSS, Javascript, Web Services...

 Pour une application Web, le MVC se situe dans le niveau *Présentation* de l'architecture ci-dessus :
* Données : ...;
* Service : ...;
* Présentation :
    * Le Contrôleur : intercepte la requête HTTP et renvoie la réponse HTTP ;
    * Le Modèle : stocke les données à afficher / traiter ;
    * La Vue: organise la sortie / l'affichage.

Le cycle de vie d'une requête HTTP typique :

1. L'utilisateur envoie la requête HTTP ;
2. Le contrôleur l'intercepte ;
3. Le contrôleur appelle le service approprié ;
4. Le service appelle le DAO approprié, qui renvoie des données persistantes (par exemple) ;
5. Le service traite les données et renvoie les données au contrôleur ;
6. Le contrôleur stocke les données dans le modèle approprié et appelle la vue appropriée ;
7. La vue est instanciée avec les données du modèle et renvoyée en tant que réponse HTTP.

Ceci peut se schématiser de la façon suivante :

