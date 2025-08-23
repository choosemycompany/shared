# 1. Vue d’ensemble

Le système **Mercure** est utilisé pour diffuser en temps réel l’état d’avancement d’un `Process` vers les clients abonnés.  
La configuration repose sur une série de **presenters**, **attachers**, une **logique de diffusion (broadcaster)** et des **initiations**, permettant d’initialiser correctement les callbacks du `Process`, de diffuser ses mises à jour en temps réel et d’assurer leur suivi jusqu’à la complétion ou l’échec.

Il est également possible d’ajouter ses propres attachers ou de personnaliser la diffusion pour l’adapter à des besoins métiers spécifiques.

---

# 2. Structure des services

## 2.1. Presenters

Les presenters sont responsables de la présentation des données liées à l’état du process :

| Service ID                     | Classe                         | Rôle                                         |
|--------------------------------|--------------------------------|----------------------------------------------|
| `presenter_process_domain`     | `...ProcessDomainPresenter`    | Provider + état du Process (domaine)         |
| `presenter_process_json`       | `...ProcessJsonPresenter`      | Présentation des données en JSON             |
| `presenter_process_mercure`    | `...ProcessMercurePresenter`   | Présentation des données au format Mercure   |
| `presenter_error_list_mercure` | `...ErrorListMercurePresenter` | Présentation des erreurs via Mercure         |

## 2.2. Attachers

Les attachers définissent les callbacks qui réagiront aux changements d’état du `Process` :

| Service ID                                              | Classe                                                                   | Rôle                                                                                                                                           |
|---------------------------------------------------------|--------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| `process_attacher_view_model_started_mercure`           | `...StartedProcessViewModelCallbackAttacher`                             | Callback pour l’état `started`                                                                                                                 |
| `process_attacher_view_model_in_progress_mercure`       | `...InProgressProcessViewModelCallbackAttacher`                          | Callback pour l’état `in_progress`                                                                                                             |
| `process_attacher_view_model_completed_mercure`         | `...CompletedProcessViewModelCallbackAttacher`                           | Callback pour l’état `completed`                                                                                                               |
| `process_attacher_view_model_failed_mercure`            | `...FailedProcessViewModelCallbackAttacher`                              | Callback pour l’état `failed`                                                                                                                  |
| `process_attacher_view_model_failed_error_list_mercure` | `...FailedProcessViewModelCallbackAttacher` (version erreurs détaillées) |                                                                                                                                                |
| `process_broadcaster_mercure`                           | `...MercureBroadcaster`                                                  | Diffuse l’Update Mercure via le hub (`mercure.hub.default.traceable`) pour informer en temps réel les abonnés du changement d’état du Process. |
| `process_attacher_state_change_mercure`                 | `...MultipleAttacher`                                                    | Regroupe tous les attachers ci-dessus pour un enregistrement groupé                                                                            |

## 2.3. Initiation

Dès l’initiation du `Process`, les différents **attachers** sont appliqués automatiquement.  
Les services suivants orchestrent cette phase d’initialisation :

| Service ID                            | Classe                         | Rôle                                                                                                                   |
|---------------------------------------|--------------------------------|------------------------------------------------------------------------------------------------------------------------|
| `process_initiation`                  | `...ProcessInitiation`         | Création du process et mise à disposition via le `ProcessProvider`, connecté au `presenter_process_domain`.            |
| `process_initiation_attacher_mercure` | `...ProcessAttacherInitiation` | Ajout des attachers Mercure au process lors de l’initiation                                                            |
| `process_initiation_start`            | `...ProcessStartInitiation`    | Démarrage automatique du process avec Mercure                                                                          |
| `process_initiation_mercure`          | `...MultipleInitiation`        | Regroupe les initiations pour un démarrage cohérent du process                                                         |

## 2.4. Completion

À la complétion, des services dédiés gèrent les étapes de progression et de finalisation :

| Service ID                      | Classe                         | Rôle                                                      |
|---------------------------------|--------------------------------|-----------------------------------------------------------|
| `process_completion_progress`   | `...ProcessProgressCompletion` | Mise à jour des informations de progression               |
| `process_completion_finalize`   | `...ProcessFinalizeCompletion` | Finalisation du process et envoi des erreurs si présentes |

## 2.5. Event Bus (listener)

- `event_listener_process_present_on_loaded` — `...PresentProcessOnProcessLoadedEventListener`  
  À la réception d’un `ProcessLoadedEvent`, le listener vérifie que le `Process` n’a pas déjà été présenté (`PresenterState`).  
  Il construit un `ProcessResponse` à partir de l’événement, puis appelle `present()` sur `presenter_process_domain`.  
  Ainsi, `presenter_process_domain` (qui implémente **ProcessPresenter, ProcessProvider, PresenterState, ResetState**) rend le `Process` disponible via **ProcessProvider** pour les composants en aval.

---

# 3. Schémas

## 3.1. Diagramme du flux

![flux.svg](flux.svg)

## 3.2. Diagramme de séquence (détaillé)

![diagrame_uml_sequency.svg](diagrame_uml_sequency.svg)

## 3.3. Diagramme d’activité (UML)

![process_mercure_uml_activity_v2.svg](process_mercure_uml_activity_v2.svg)

---

# 4. Mapping des services (référence YAML)

- **Presenters**
  - `presenter_process_json` → ProcessJsonPresenter
  - `presenter_process_mercure` → ProcessMercurePresenter
  - `presenter_error_list_mercure` → ErrorListMercurePresenter
- **Message bus / Middleware**
  - `message_bus_middleware_add_process_stamp` → AddProcessStampMiddleware (outcome: JSON)
  - `message_bus_middleware_process_loaded_event` → ProcessLoadedEventMiddleware (dispatch évènement via `event_bus_dispatcher`)
- **Event bus**
  - `event_bus_dispatcher` → SymfonyEventDispatcher
  - Listener: `event_listener_process_present_on_loaded` (présentation uniquement)
- **Broadcaster (Mercure)**
  - `process_update_factory_mercure` → MercureProcessUpdateFactory (processProvider + serializer)
  - `process_broadcaster_mercure` → MercureBroadcaster (hub)
- **Attachers**
  - `process_attacher_view_model_started_mercure`
  - `process_attacher_view_model_in_progress_mercure`
  - `process_attacher_view_model_completed_mercure`
  - `process_attacher_view_model_failed_mercure`
  - `process_attacher_view_model_failed_error_list_mercure` (variante erreurs détaillées)
  - `process_attacher_state_change_mercure` (agrégateur)
- **Initiations**
  - `process_initiation`
  - `process_initiation_attacher_mercure`
  - `process_initiation_start`
  - `process_initiation_mercure` (MultipleInitiation)
- **Completion**
  - `process_completion_progress`
  - `process_completion_finalize`

---

# 5. Ordre d’attachement et déclenchement

L’exécution d’un process suit un ordre précis pour garantir la cohérence entre les initiations, les attachers, le broadcaster et les phases de finalisation :

1. **Initiation JSON** : création du `Process` et mise à disposition des `topics` pour les abonnements clients.
2. **MessageBus** : ajout des stamps puis déclenchement du handler pour exécuter les étapes métier (progression, finalisation ou échec).
3. **EventBus** : relais des événements liés au process après la réception du message.
4. **Initiation Attacher** : enregistrement de tous les callbacks nécessaires :
    - `Started`, `InProgress`, `Completed`, `Failed` pour la présentation des états via les presenters.
    - **Broadcaster** : attachement du `MercureBroadcaster` afin que chaque changement d’état du `Process` déclenche automatiquement la génération d’un `Update` via `MercureProcessUpdateFactory` puis sa diffusion en temps réel sur le hub Mercure.
5. **Initiation Start** : publication de l’événement de démarrage après l’attachement des callbacks et le lancement du traitement.
6. **Completion** : gestion de l’évolution et de la finalisation du process :
    - `InProgress` : diffusion de la progression intermédiaire.
    - `Completed` : diffusion de l’état final de succès.
    - `Failed` : diffusion de l’état final d’échec avec les détails d’erreurs le cas échéant.

---

# 6. Variante erreurs détaillées (Failed)

Pour publier une vue d’erreurs enrichie lors d’un échec, utilisez `presenter_error_list_mercure` via l’attacher dédié :

```yaml
process_attacher_view_model_failed_error_list_mercure:
    class: ChooseMyCompany\Shared\Domain\Attacher\FailedProcessViewModelCallbackAttacher
    arguments:
        $viewModelAccess: '@presenter_error_list_mercure'

# Dans l’agrégateur, remplacez l’attacher failed standard si souhaité
process_attacher_state_change_mercure:
    class: ChooseMyCompany\Shared\Domain\Attacher\MultipleAttacher
    arguments:
        - '@process_attacher_view_model_started_mercure'
        - '@process_attacher_view_model_in_progress_mercure'
        - '@process_attacher_view_model_completed_mercure'
        - '@process_attacher_view_model_failed_error_list_mercure'
        - '@process_broadcaster_mercure'
```

# 7. Points d’extension

- **Ajouter ses propres attachers** : vous pouvez créer vos services spécifiques pour enrichir ou remplacer des callbacks par défaut. Par exemple :

```yaml
job_multiple_register_process_attacher_view_model_in_progress_mercure:
    class: ChooseMyCompany\Shared\Domain\Attacher\InProgressProcessViewModelCallbackAttacher
    arguments:
        $viewModelAccess: '@presenter_job_register_mercure'

job_multiple_register_process_attacher_state_change_mercure:
    class: ChooseMyCompany\Shared\Domain\Attacher\MultipleAttacher
    arguments:
        - '@process_attacher_view_model_started_mercure'
        - '@job_multiple_register_process_attacher_view_model_in_progress_mercure'
        - '@process_attacher_view_model_completed_mercure'
        - '@process_attacher_view_model_failed_error_list_mercure'
        - '@process_broadcaster_mercure'
```

- **Ajouter un nouveau canal** (ex. e‑mail) en créant un `...NotificationPublisher` et en l’intégrant dans un `MultipleAttacher` personnalisé.
- **Changer le *****outcome***** du middleware** en injectant un presenter différent dans `message_bus_middleware_add_process_stamp`.
