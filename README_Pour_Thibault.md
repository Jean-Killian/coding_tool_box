## 🚀 Fonctionnalités principales

### 👩‍🏫 Côté Enseignant
- Génération de QCM via une intelligence artificielle (Mistral API).
- Prévisualisation du QCM généré avant validation.
- Publication des QCM (brouillon / publié).
- Affectation des QCM à des cohortes d'étudiants.
- Suppression de QCM.
- Vue dédiée `teacher_index` avec séparation : QCM publiés / brouillons

### 🧑‍🎓 Côté Étudiant
- Accès aux QCM assignés par les enseignants.
- Réponse aux QCM dans une interface simple.
- Calcul et enregistrement automatique du score.
- Affichage du résultat avec correction (bonne réponse en vert, mauvaise en rouge).
- Accès à l’historique des QCM complétés.

## ✅ Fonctionnalités techniques
- Gestion des rôles avec une table `users_schools` (`role` dans le pivot)
- Middlewares conditionnels pour les accès selon rôle (enseignant / étudiant)*
- Politique (`QuizPolicy`) appliquée pour sécuriser l'accès aux routes sensibles

## ⚠️ Bugs connus / Points à améliorer
- Le modèle d'IA choisi étant un modèle gratuit et très simple, il ne peut pas générer des réponse trop longue.
Plus on lui demande un questionnaires avec beaucoup de question et de réponses possible, plus les chances qu'il renvoie
une erreur sont élevé.
- Parfois L'IA génère un fichier json qui n'est pas correctement structurée, le code ne pouvant pas encoder je fichier
renvoie une erreur
- Parfois L'IA met trop de temps a répondre et on reçoit une erreur "Maximum execution time of 60 seconds exceeded"
- Lorsque le quiz est crée ou publié l'heure affiché n'est pas la bonne
- Créer une animation de chargement lors de la génération du QCM

## 🔧 Ce qu'il reste à faire
-  Ajouter une option de duplication de QCM pour créer des variantes
-  Permettre l’édition des QCM (actuellement non modifiables)
-  Ajouter des feedbacks plus visuels lors des soumissions (alertes, toasts, etc.)
-  Mettre en place des tests PHPUnit (services + contrôleurs)
