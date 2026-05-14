# Système de Gestion de Chantier

## Description
Application web de gestion de chantiers de construction (BTP), permettant de planifier, suivre et valider les travaux avec une coordination entre plusieurs acteurs.

### Fonctionnalités réalisées jusqu’ici :
1. Connexion / Inscription
2. Les fonctionnalités ci-dessous sont disponibles en tant que **Chef de chantier** :
* Ouvrir un chantier que vous avez créé ou en créer un nouveau
* Génération automatique de certaines tâches d’un chantier à partir de son modèle
* Création / modification / suppression des tâches

## Architecture MCD
## Choix de conception
Un seul modèle `Utilisateur` regroupe tous les acteurs (Chef de chantier, Architecte, Ouvrier, Administrateur) car ils partagent les mêmes caractéristiques. Leurs actions diffèrent uniquement selon leur rôle.

Un utilisateur peut avoir des rôles différents selon les chantiers — par exemple, être Chef de chantier sur un chantier et Ouvrier sur un autre.
### Entités principales
- **Utilisateur** — acteur unique avec rôle global (Chef de chantier, Architecte, Ouvrier, Administrateur)
- **Chantier** — projet de construction avec modèle associé
- **Modele** — template de chantier (Résidentiel, Commercial, etc.)
- **Tache** — unité de travail d'un chantier
- **Tache_modele** — template de tâche lié à un modèle de chantier
- **Validation** — validation d'une tâche par un architecte
- **Avancement_tache** — suivi de progression d'une tâche
- **Incident** — problème survenu sur une tâche

---

## Rôles et permissions

| Rôle | Permissions |
|---|---|
| **Administrateur** | Gestion des utilisateurs, supervision globale |
| **Chef de chantier** | Création de chantier, choix des équipes, affectation des tâches, suivi |
| **Architecte** | Validation des tâches, vérification de la conformité |
| **Ouvrier** | Consultation et exécution des tâches, mise à jour de l'avancement |


