# Mon_premier_projet
Projet réalisé pour mettre en pratique mes acquis de ma formation RNCP niveau III
Mise en situation des 5 langages de programmation HTML5, CSS3, javascript, jQuery, PHP, MySQL
Ce projet a pour vocation de pouvoir comparer et choisir le transporteur le moins cher et le plus approprié dans une liste établie par l'utilisateur qui uploadera les fichiers CSV nécessaires au fonctionnement du logiciel.

Fonctionnement: 

*- A l'ouverture du logiciel et lors de sa première utilisation, aucune base de donnée n'est présente et toute tentative de connexion par login et mot de passe echouera et un message d'erreur apparaîtra.

*- A l'inscription du premier utilisateur, la base de donnée sera automatiquement créée et le logiciel vous invitera à vous connecter.

*- Si le log est correct alors le logiciel basculera sur la page application à proprement parler.Sinon un message d'erreur vous invitera à réessayer.

*-Vous vous êtes logué et vous êtes sur l'application. La page est vide...il faut integrer de nouveaux transporteurs en cliquant sur "Ajouter un transporteur" dans le menu supérieur.

*-Vous êtes sur nouveau transporteur : cliquez pour donner un nom a ce transporteur attention ce nom, a valeur d'id il sera unique.Toute tentative d'utilisation de deux noms identiques sur le même compte sera refusé.
<<<<<<< HEAD
-Cliquez ensuite sur le type de transport : Messagerie, Frêt, Affrètement.
-Enfin choisissez un fichier à Uploader.Si le fichier n'est pas de type CSV, ou vide, ou déjà existant il sera refusé.Sinon le logiciel vous indiquera que le fichier à bien été uploadé. Le logiciel vous invitera à ajouter alors un nouveau transporteur sinon quittez en cliquant sur le bouton superieur "Retour a l'application".

*-Vous revenez sur la page de l'application, vous voyez apparaître vos transporteurs et les noms des fichiers associés. Attention si vous ne sauvegardez pas vos données et que vous quittiez votre compte avec le bouton "Deconnexion" vos saisies seront perdues.Sinon au prochain log vous retrouverez vos enregistrements.
Pour ce faire le logiciel crée un fichier CSV dans le dossier fichier du serveur et stock les fichiers Uploadés dans le sous dossier "Datacarrier"/"User+index"/fichiers CSV uploadés.

*-Vous souhaitez supprimer un transporteur...cliquez sur "Supprimer un transporteur" dans le menu supérieur.
Des cases a cocher vous permettrons de sélectionner les transporteurs à supprimer.Appuyez sur le bouton "Supprimer" dans le menu du bas.
Un message de confirmation apparaîtra alors pour valider ou non votre choix.
Si vous poursuivez le ou les transporteurs sélectionnés seront effacés.N'oubliez pas de sauvegarder à nouveau de retour dans le menu de l'application.

Enfin vous souhaitez supprimer un compte...Cliquez sur le bouton "Supprimer un compte" dans le menu de l'application.
Des cases à cocher précisant quels comptes doivent être supprimés feront apparaître le menu de suppression.
De nouveau un message de confirmation vous invitera a poursuivre ou non.
A noter que si le compte sur lequel vous vous trouvez est également sélectionné, le logiciel vous basculera sur la page d'accueil.
Attention tous les fichiers uploadés pour les comptes concernés seront également supprimés.

Pour l'heure, faute de fiches tarifaires éxistantes, je travaille à créer un modèle d'intégration de tarifs. En l'état le logiciel pour la partie comparaison des prix n'est pas fonctionnelle.
Cette application a pour vocation de faire valoir des acquis et n'est pas à vendre (en référence à un recruteur qui m'a gentiment indiqué qu'il ne m'en donnerai pas un euro ;) ).
=======
  -Cliquez ensuite sur le type de transport : Messagerie, Frêt, Affrètement.
  -Enfin choisissez un fichier à Uploader.Si le fichier n'est pas de type CSV, ou vide, ou déjà existant il sera refusé.Sinon le logiciel vous indiquera que le fichier à bien été uploadé. Le logiciel vous invitera à ajouter alors un nouveau transporteur sinon quittez en cliquant sur le bouton superieur "Retour a l'application".
  
  *-Vous revenez sur la page de l'application, vous voyez apparaître vos transporteurs et les noms des fichiers associés. Attention si vous ne sauvegardez pas vos données et que vous quittiez votre compte avec le bouton "Deconnexion" vos saisies seront perdues.Sinon au prochain log vous retrouverez vos enregistrements.
  Pour ce faire le logiciel crée un fichier CSV dans le dossier fichier du serveur et stock les fichiers Uploadés dans le sous dossier "Datacarrier"/"User+index"/fichiers CSV uploadés.
  
  *-Vous souhaitez supprimer un transporteur...cliquez sur "Supprimer un transporteur" dans le menu supérieur.
  Des cases a cocher vous permettrons de sélectionner les transporteurs à supprimer.Appuyez sur le bouton "Supprimer" dans le menu du bas.
  Un message de confirmation apparaîtra alors pour valider ou non votre choix.
  Si vous poursuivez le ou les transporteurs sélectionnés seront effacés.N'oubliez pas de sauvegarder à nouveau de retour dans le menu de l'application.
  
  Enfin vous souhaitez supprimer un compte...Cliquez sur le bouton "Supprimer un compte" dans le menu de l'application.
  Des cases à cocher précisant quels comptes doivent être supprimés feront apparaître le menu de suppression.
  De nouveau un message de confirmation vous invitera a poursuivre ou non.
  A noter que si le compte sur lequel vous vous trouvez est également sélectionné, le logiciel vous basculera sur la page d'accueil.
  Attention tous les fichiers uploadés pour les comptes concernés seront également supprimés.
  
  Pour l'heure, faute de fiches tarifaires éxistantes, je travaille à créer un modèle d'intégration de tarifs. En l'état le logiciel pour la partie comparaison des prix n'est pas fonctionnelle.
  Cette application a pour vocation de faire valoir des acquis et n'est pas à vendre .
