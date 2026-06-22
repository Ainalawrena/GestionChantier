      table_name       |     column_name     |          data_type          
------------------------+---------------------+-----------------------------
 affectation_chantier   | id_utilisateur      | integer
 affectation_chantier   | id_chantier         | integer
 affectation_chantier   | id_role             | integer
 affectation_tache      | id_utilisateur      | integer
 affectation_tache      | id_tache            | integer
 affectation_tache      | date_debut_reelle   | date
 affectation_tache      | date_fin_reelle     | date
 avancement_tache       | id_avancement       | integer
 avancement_tache       | pourcentage         | numeric
 avancement_tache       | commentaire         | character varying
 avancement_tache       | id_tache            | integer
 cache                  | key                 | character varying
 cache                  | value               | text
 cache                  | expiration          | bigint
 cache_locks            | key                 | character varying
 cache_locks            | owner               | character varying
 cache_locks            | expiration          | bigint
 chantier               | id_chantier         | integer
 chantier               | nom                 | character varying
 chantier               | date_debut_prevu    | date
 chantier               | date_fin_prevu      | date
 chantier               | statut              | character varying
 chantier               | id_modele           | integer
 dependance_tache       | id_dependance       | integer
 dependance_tache       | id_tache            | integer
 dependance_tache       | id_tache_precedente | integer
 failed_jobs            | id                  | bigint
 failed_jobs            | uuid                | character varying
 failed_jobs            | connection          | text
 failed_jobs            | queue               | text
 failed_jobs            | payload             | text
 failed_jobs            | exception           | text
 failed_jobs            | failed_at           | timestamp without time zone
 migrations             | batch               | integer
 modele                 | id_modele           | integer
 modele                 | nom                 | character varying
 modele                 | image               | character varying
 modifier               | id_avancement       | integer
 modifier               | id_utilisateur      | integer
 modifier               | date_mise_a_jour    | date
 password_reset_tokens  | email               | character varying
 password_reset_tokens  | token               | character varying
 password_reset_tokens  | created_at          | timestamp without time zone
 personal_access_tokens | id                  | bigint
 personal_access_tokens | tokenable_type      | character varying
 personal_access_tokens | tokenable_id        | bigint
 personal_access_tokens | name                | text
 personal_access_tokens | token               | character varying
 personal_access_tokens | abilities           | text
 personal_access_tokens | last_used_at        | timestamp without time zone
 personal_access_tokens | expires_at          | timestamp without time zone
 personal_access_tokens | created_at          | timestamp without time zone
 personal_access_tokens | updated_at          | timestamp without time zone
 role                   | id_role             | integer
 role                   | libelle             | character varying
 session                | id_session          | character varying
 session                | id_utilisateur      | integer
 session                | donnees             | text
 session                | date_creation       | timestamp without time zone
 session                | date_expiration     | timestamp without time zone
 session                | ip_address          | character varying
 session                | user_agent          | character varying
 sessions               | id                  | character varying
 sessions               | user_id             | bigint
 sessions               | ip_address          | character varying
 sessions               | user_agent          | text
 sessions               | payload             | text
 sessions               | last_activity       | integer
 tache                  | id_tache            | integer
 incident               | id_incident         | integer
 incident               | description         | character varying
 incident               | gravite             | character varying
 incident               | impact              | character varying
 incident               | gravite             | character varying
 incident               | impact              | character varying
 incident               | date_incident       | date
 incident               | statut              | character varying
 incident               | solution            | character varying
 incident               | date_resolution     | date
 incident               | id_tache            | integer
 jalon                  | id                  | integer
 jalon                  | ordre               | integer
 jalon                  | nom                 | character varying
 jalon                  | pourcentage         | integer
 jalon                  | id_tache            | integer
 jalon_modele           | id_jalon_modele     | integer
 jalon_modele           | ordre               | integer
 jalon_modele           | nom                 | character varying
 jalon_modele           | pourcentage         | integer
 jalon_modele           | id_tache_modele     | integer
 job_batches            | id                  | character varying
 job_batches            | name                | character varying
 job_batches            | total_jobs          | integer
 job_batches            | pending_jobs        | integer
 job_batches            | failed_jobs         | integer
 job_batches            | failed_job_ids      | text
 job_batches            | options             | text
 job_batches            | cancelled_at        | integer
 job_batches            | created_at          | integer
 job_batches            | finished_at         | integer
 jobs                   | id                  | bigint
 jobs                   | queue               | character varying
 jobs                   | payload             | text
 jobs                   | attempts            | smallint
 jobs                   | reserved_at         | integer
 jobs                   | available_at        | integer
 jobs                   | created_at          | integer
 migrations             | id                  | integer
 migrations             | migration           | character varying
migrations             | batch               | integer
 modele                 | id_modele           | integer
 modele                 | nom                 | character varying
 modele                 | image               | character varying
 modifier               | id_avancement       | integer
 modifier               | id_utilisateur      | integer
 modifier               | date_mise_a_jour    | date
 password_reset_tokens  | email               | character varying
 password_reset_tokens  | token               | character varying
 password_reset_tokens  | created_at          | timestamp without time zone
 personal_access_tokens | id                  | bigint
 personal_access_tokens | tokenable_type      | character varying
 personal_access_tokens | tokenable_id        | bigint
 personal_access_tokens | name                | text
 personal_access_tokens | token               | character varying
 personal_access_tokens | abilities           | text
 personal_access_tokens | last_used_at        | timestamp without time zone
 personal_access_tokens | expires_at          | timestamp without time zone
 personal_access_tokens | created_at          | timestamp without time zone
 personal_access_tokens | updated_at          | timestamp without time zone
 role                   | id_role             | integer
 role                   | libelle             | character varying
 session                | id_session          | character varying
 session                | id_utilisateur      | integer
session                | date_creation       | timestamp without time zone
 session                | date_expiration     | timestamp without time zone
 session                | ip_address          | character varying
 session                | user_agent          | character varying
 sessions               | id                  | character varying
 sessions               | user_id             | bigint
 sessions               | ip_address          | character varying
 sessions               | user_agent          | text
 sessions               | payload             | text
 sessions               | last_activity       | integer
 tache                  | id_tache            | integer
 tache                  | nom                 | character varying
 tache                  | ordre               | integer
 tache                  | statut              | character varying
 tache                  | pourcentage         | numeric
 tache                  | date_debut_prevue   | date
 tache                  | date_fin_prevue     | date
 tache                  | id_tache_modele     | integer
 tache                  | id_chantier         | integer
 tache                  | id_utilisateur      | integer
 tache_modele           | id_tache_modele     | integer
 tache_modele           | nom                 | character varying
 tache_modele           | ordre               | integer
 tache_modele           | id_modele           | integer
 users                  | id                  | bigint
 users                  | name                | character varying
 users                  | email               | character varying
 users                  | email_verified_at   | timestamp without time zone
 users                  | password            | character varying
 users                  | remember_token      | character varying
 users                  | created_at          | timestamp without time zone
 users                  | updated_at          | timestamp without time zone
 utilisateur            | id_user             | integer
 utilisateur            | nom                 | character varying
 utilisateur            | login               | character varying
 utilisateur            | adresse             | character varying
 utilisateur            | email               | character varying
 utilisateur            | password_hash       | character varying
 utilisateur            | id_role             | integer
 validation             | id_validation       | integer
 validation             | statut_validation   | character varying
 validation             | date_validation     | date
 validation             | id_avancement       | integer
 validation             | id_utilisateur      | integer

