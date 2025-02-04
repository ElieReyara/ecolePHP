/ecole
├── /config
│   └── database.php         // Connexion à la base de données
├── /controllers
│   ├── UserController.php   // Gestion des utilisateurs (connexion, inscription)
│   ├── TeacherController.php // Gestion des actions de l'enseignant
│   └── StudentController.php // Gestion des actions de l'étudiant
├── /models
│   ├── User.php             // Modèle utilisateur
│   ├── Grade.php            // Modèle des notes
│   ├── Schedule.php         // Modèle emploi du temps
│   └── Subject.php          // Modèle des matières
├── /views
│   ├── /teacher
│   │   ├── dashboard.php    // Tableau de bord enseignant
│   │   ├── addGrade.php     // Ajouter des notes
│   │   ├── deleteGrade.php  // Supprimer une note
│   │   └── schedule.php     // Gestion emploi du temps
│   ├── /student
│   │   ├── dashboard.php    // Tableau de bord étudiant
│   │   ├── grades.php       // Voir les notes et moyenne
│   │   └── schedule.php     // Voir emploi du temps
├── /public
│   └── index.php            // Page d'accueil, avec gestion des routes
└── .htaccess                // Gestion des URLs et réécriture
