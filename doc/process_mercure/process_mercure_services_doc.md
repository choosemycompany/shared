# 1. Vue d’ensemble

Le système de notifications **Mercure** est utilisé pour diffuser en temps réel l’état d’avancement d’un `Process` vers les clients abonnés. La configuration repose sur une série de **presenters**, **attachers**, et **initiations** permettant d’initialiser correctement les callbacks du process et d’assurer leur suivi jusqu’à la complétion ou l’échec.

Il est également possible d’ajouter ses propres attachers personnalisés pour adapter le comportement à des cas métiers spécifiques.

---

# 2. Structure des services

## 2.1. Presenters

Les presenters sont responsables de la présentation des données liées à l’état du process :

| Service ID                     | Classe                                                                  | Rôle                                       |
| ------------------------------ | ----------------------------------------------------------------------- | ------------------------------------------ |
| `presenter_process_json`       | `ChooseMyCompany\Shared\Presentation\Json\ProcessJsonPresenter`         | Présentation des données en JSON           |
| `presenter_process_mercure`    | `ChooseMyCompany\Shared\Presentation\Mercure\ProcessMercurePresenter`   | Présentation des données au format Mercure |
| `presenter_error_list_mercure` | `ChooseMyCompany\Shared\Presentation\Mercure\ErrorListMercurePresenter` | Présentation des erreurs via Mercure       |

## 2.2. Attachers

Les attachers définissent les callbacks qui réagiront aux changements d’état du `Process` :

| Service ID                                              | Classe                                                                   | Rôle                                                                |
| ------------------------------------------------------- | ------------------------------------------------------------------------ | ------------------------------------------------------------------- |
| `process_attacher_view_model_started_mercure`           | `...StartedProcessViewModelCallbackAttacher`                             | Callback pour l’état `started`                                      |
| `process_attacher_view_model_in_progress_mercure`       | `...InProgressProcessViewModelCallbackAttacher`                          | Callback pour l’état `in_progress`                                  |
| `process_attacher_view_model_completed_mercure`         | `...CompletedProcessViewModelCallbackAttacher`                           | Callback pour l’état `completed`                                    |
| `process_attacher_view_model_failed_mercure`            | `...FailedProcessViewModelCallbackAttacher`                              | Callback pour l’état `failed`                                       |
| `process_attacher_view_model_failed_error_list_mercure` | `...FailedProcessViewModelCallbackAttacher` (version erreurs détaillées) |                                                                     |
| `process_attacher_notification_process_mercure`         | `...ProcessNotificationCallbackAttacher`                                 | Publication des notifications liées au process                      |
| `process_attacher_state_change_mercure`                 | `...MultipleProcessStateChangeAttacher`                                  | Regroupe tous les attachers ci-dessus pour un enregistrement groupé |

## 2.3. Initiation

Depuis cette refonte, **les attachers sont désormais appliqués dès l’initiation** du `Process`. Les services suivants orchestrent l’initialisation :

| Service ID                            | Classe                         | Rôle                                                            |
| ------------------------------------- | ------------------------------ | --------------------------------------------------------------- |
| `process_initiation_json`             | `...ProcessInitiation`         | Création initiale du process et association d’un presenter JSON |
| `process_initiation_attacher_mercure` | `...ProcessAttacherInitiation` | Ajout des attachers Mercure au process lors de l’initiation     |
| `process_initiation_start_mercure`    | `...ProcessStartInitiation`    | Démarrage automatique du process avec Mercure                   |
| `process_initiation_mercure`          | `...MultipleInitiation`        | Regroupe les initiations pour un démarrage cohérent du process  |

## 2.4. Completion

À la complétion, des services dédiés gèrent les étapes de progression et de finalisation :

| Service ID                            | Classe                         | Rôle                                                      |
| ------------------------------------- | ------------------------------ | --------------------------------------------------------- |
| `process_completion_progress_mercure` | `...ProcessProgressCompletion` | Mise à jour des informations de progression               |
| `process_completion_finalize_mercure` | `...ProcessFinalizeCompletion` | Finalisation du process et envoi des erreurs si présentes |

---

# 3. Schémas

## 3.1. Diagramme du flux

![flux.svg](flux.svg)

## 3.2. Diagramme de séquence (détaillé)

```plantuml
@startuml
actor Client
participant "API / Controller" as API
participant "ProcessInitiation (JSON)" as PI_JSON
participant "ProcessAttacherInitiation" as PI_ATTACH
participant "ProcessStartInitiation" as PI_START
participant "Process (Domain)" as PROC
participant "MessageBus (Dispatcher)" as MB
participant "AddProcessStampMiddleware" as MW_ADD
participant "Handler / UseCase" as HANDLER
participant "ProcessLoadedEventMiddleware" as MW_LOADED
participant "EventBus (Dispatcher)" as EB
participant "ProcessMercurePresenter" as PRES
participant "ErrorListMercurePresenter" as PRES_ERR
participant "Mercure Hub" as HUB

Client -> API: POST /api/jobs (ex.)
API -> PI_JSON: initiate(process)
PI_JSON -> PROC: create(status=initiated)
PROC --> PI_JSON: Process + topics
PI_JSON --> API: ViewModel(JSON) + topics
API --> Client: 202 Accepted + topics

' Attachement des callbacks à l’initiation
API -> PI_ATTACH: attach(process)
PI_ATTACH -> PROC: onStateChanged(Started/InProgress/Completed/Failed)
PI_ATTACH -> PROC: onStateChanged(Notification)

' Démarrage Mercure à l’initiation
API -> PI_START: start(process)
PI_START -> PRES: present(Start)
PRES -> HUB: publish(Update START)

' Envoi d’un message métier
API -> MB: dispatch(Command)
MB -> MW_ADD: stamp(ProcessId + outcome presenter JSON)
MW_ADD -> HANDLER: handle(Command)

' Chargement et events côté consommation
HANDLER -> MW_LOADED: load(process)
MW_LOADED -> EB: dispatch(ProcessLoadedEvent)
EB -> PRES: present(Loaded)
PRES -> HUB: publish(Update LOADED)

' Exécution métier (boucles, progression)
HANDLER -> PROC: progress()
PROC -> PRES: present(InProgress)
PRES -> HUB: publish(Update IN_PROGRESS)

' Succès
HANDLER -> PROC: complete()
PROC -> PRES: present(Completed)
PRES -> HUB: publish(Update COMPLETED)

' Échec
HANDLER -> PROC: fail(errors)
PROC -> PRES_ERR: present(Failed + errors)
PRES_ERR -> HUB: publish(Update FAILED)
@enduml
```

## 3.3. Diagramme d’activité (UML)

```plantuml
@startuml
start
:Initiate Process (JSON);
:Attach Mercure Callbacks;
:Start (publish START);
if (Message dispatched?) then (yes)
  :AddProcessStampMiddleware;
  :Handler loads Process;
  :ProcessLoadedEvent -> Present;
else (no)
  :Await work;
endif
repeat
  :Do work step;
  :Publish IN_PROGRESS;
repeat while (More work?) is (yes)
if (Success?) then (yes)
  :Finalize -> Publish COMPLETED;
  stop
else (no)
  :Collect errors;
  :Present errors via ErrorList;
  :Publish FAILED;
  stop
endif
@enduml
```

---

# 4. Mapping des services (référence YAML)

- **Presenters**
  - `presenter_process_json` → ProcessJsonPresenter
  - `presenter_process_mercure` → ProcessMercurePresenter
  - `presenter_error_list_mercure` → ErrorListMercurePresenter
- **Message bus / Middleware**
  - `message_bus_middleware_add_process_stamp_json` → AddProcessStampMiddleware (outcome: JSON)
  - `message_bus_middleware_process_loaded_event` → ProcessLoadedEventMiddleware (dispatch évènement via `event_bus_dispatcher`)
- **Event bus**
  - `event_bus_dispatcher` → SymfonyEventDispatcher
  - Listener: `event_listener_process_present_on_loaded_mercure` (présentation uniquement)
- **Notification**
  - `process_notification_publisher_mercure` → MercureProcessNotificationPublisher (hub + serializer)
- **Attachers**
  - `process_attacher_view_model_started_mercure`
  - `process_attacher_view_model_in_progress_mercure`
  - `process_attacher_view_model_completed_mercure`
  - `process_attacher_view_model_failed_mercure`
  - `process_attacher_view_model_failed_error_list_mercure` (variante erreurs détaillées)
  - `process_attacher_notification_process_mercure`
  - `process_attacher_state_change_mercure` (agrégateur)
- **Initiations**
  - `process_initiation_json`
  - `process_initiation_attacher_mercure`
  - `process_initiation_start_mercure`
  - `process_initiation_mercure` (MultipleInitiation)
- **Completion**
  - `process_completion_progress_mercure`
  - `process_completion_finalize_mercure`

---

# 5. Ordre d’attachement et déclenchement

L’exécution d’un process suit un ordre précis pour garantir la cohérence entre les initiations, les attachers et les notifications :

1. **Initiation JSON** : création du `Process` et mise à disposition des `topics` pour les abonnements clients.
2. **MessageBus** : ajout des stamps puis déclenchement du handler pour exécuter les étapes métier (progression, finalisation ou échec).
3. **EventBus** : relais des événements liés au process après la réception du message.
4. **Initiation Attacher** : enregistrement de tous les callbacks nécessaires (`Started`, `InProgress`, `Completed`, `Failed`, `Notification`) pour gérer les changements d’état tout au long du cycle de vie du process.
5. **Initiation Start** : publication de l’événement de démarrage après l’attachement des callbacks et le lancement du traitement.

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
    class: ChooseMyCompany\Shared\Domain\Attacher\MultipleProcessStateChangeAttacher
    arguments:
        - '@process_attacher_view_model_started_mercure'
        - '@process_attacher_view_model_in_progress_mercure'
        - '@process_attacher_view_model_completed_mercure'
        - '@process_attacher_view_model_failed_error_list_mercure'
        - '@process_attacher_notification_process_mercure'
```

# 7. Points d’extension

- **Ajouter ses propres attachers** : vous pouvez créer vos services spécifiques pour enrichir ou remplacer des callbacks par défaut. Par exemple :

```yaml
job_multiple_register_process_attacher_view_model_in_progress_mercure:
    class: App\Shared\Domain\Attacher\InProgressProcessViewModelCallbackAttacher
    arguments:
        $viewModelAccess: '@presenter_job_register_mercure'

job_multiple_register_process_attacher_state_change_mercure:
    class: App\Shared\Domain\Attacher\MultipleProcessStateChangeAttacher
    arguments:
        - '@process_attacher_view_model_started_mercure'
        - '@job_multiple_register_process_attacher_view_model_in_progress_mercure'
        - '@process_attacher_view_model_completed_mercure'
        - '@process_attacher_view_model_failed_error_list_mercure'
        - '@process_attacher_notification_process_mercure'
```

- **Ajouter un nouveau canal** (ex. e‑mail) en créant un `...NotificationPublisher` et en l’intégrant dans un `MultipleProcessStateChangeAttacher` personnalisé.
- **Changer le *****outcome***** du middleware** en injectant un presenter différent dans `message_bus_middleware_add_process_stamp_json`.
