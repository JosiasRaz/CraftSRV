
Bienvenu sur la documentation de CraftSRV !
*******************************************
Il s'agit d'un module **d'hébergement** utilisant le plateforme de service **SaaS** `GamePanelioAPI`<https://
docs.gamepanel.io/api/>_dans le but de créer des **server** dans des `CraftSRV`<https://gamepanel.atlassian.net/wiki/spaces/CSRV>_ version 1.x.

Manuel d'utilisation
********************

Back office
===========

Authentification
----------------
Pour s'authentifier il faut se rendre sur ce lien : http://197.158.86.184:8080/boxbilling/index.php?_url=/bb-admin>.
Après on loge avec l'adresse éléctronique : **crpreseau@gmail.com** et **admin** comme mot de passe.

Installation (serveur machine)
------------------------------
On déroule l'onglet **Extensions** ensuite on clique **Overviw** puis on **active** le **CraftSRV 0.1.0**.
Et on sera rédiriger directement vers ce lien : http://197.158.86.184:8080/boxbilling/index.php?_url=/bb-admin/craftsrv.

Gestion des serveurs machines 
-----------------------------
Pour gérer ses serveurs, il faut aller dans l'onglet **CraftSRV** puis cliquer sur **Overview**.
Et on observe sur le coin à doite succéssivement **paneau de configuration du host**, le bouton permettant de **tester si on n'est pas connécté ou pas** et finalement un **bouton d'édition**.

Le bouton d'édition
^^^^^^^^^^^^^^^^^^^
Premièrement, ici, on peut voir l'adress **IP** du serveur à créer ainsi que les ports déjà ocuppés. Aussi, c'est ici qu'on a la possibilité d'éditer le **nom**, l'**hôte**, la **version** , le **token** et les **ports** du serveur machine en question.  

ajout des serveurs machines
---------------------------
On peut facilement ajouter des serveurs machines en allant dans l'onglet **CraftSRV** puis Overview après on clique sur **New CraftSRV** .
Après cela on est face à un formulaire qui comprend **4 champs obligatoires**:
- Nom du serveur machine
- L'hôte
- La version 
- et le Token

et **2 champs facultatifs**:
- une plage de port
- les ports qu'on veut interdir (on les sépare par des virgules dans le cas où il y en a plusierus)

On clique sur le bouton **create** et on se trouve sur la page de cet url : http://197.158.86.184:8080/boxbilling/index.php?_url=/bb-admin/craftsrv.

Installation des produits serveurs
----------------------------------
Pour cela, il suffit d'aller dans l'onglet **Extensions** puis **Overview**, comme précédemnt lors de l'installation d'un serveur machine, mais cette fois-ci on active le serveur **Server CraftSRV product type 0.1.0**. 

Ajout des produits serveurs de types GameCraftSRV
-------------------------------------------------
On se rend dans l'onglet **Products** ensuite **Products & Services** puis **New Product**.
Et là, on est face à **3 champs** à savoir : 
- Le type du produit 
- son catégorie
_ enfin, son nom.

On valide, puis on se trouve face à une interface permettant de paramétrer d'une manière générale le produit et quelques configurations à savoir : 
- choix du serveur machine 
- choix du game
-  et le Hosting Plan


Front office
============

Authentification en tant que client
-----------------------------------

achats des X de type gameCraftSRV
---------------------------------