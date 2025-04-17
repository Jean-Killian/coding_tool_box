## 🚀 Fonctionnalités principales

### 👩‍🏫 Côté Enseignant
- Génération de QCM via une intelligence artificielle (Mistral API).
- Prévisualisation du QCM généré avant validation.
- Publication des QCM (brouillon / publié).
- Affectation des QCM à des cohortes d'étudiants.
- Suppression de QCM.
- Accès à un tableau de bord avec tous les QCM créés.

### 🧑‍🎓 Côté Étudiant
- Accès aux QCM assignés par les enseignants.
- Réponse aux QCM dans une interface simple.
- Calcul et enregistrement automatique du score.
- Affichage du résultat avec correction (bonne réponse en vert, mauvaise en rouge).
- Accès à l’historique des QCM complétés.

##Bug
- Le modèle d'IA choisi étant un modèle gratuit et très simple, il ne peut pas générer des réponse trop longue.
Plus on lui demande un questionnaires avec beaucoup de question et de réponses possible, plus les chances qu'il renvoie
une erreur sont élevé.
- Parfois L'IA génère un fichier json qui n'est pas correctement structurée, le code ne pouvant pas encoder je fichier
renvoie une erreur
- Parfois L'IA met trop de temps a répondre et on reçoit une erreur "Maximum execution time of 60 seconds exceeded"
- Lorsque le quiz est crée ou publié l'heure affiché n'est pas la bonne
