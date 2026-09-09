# Ports techniques

## 🧱 Ports fournis

| Port                   | Rôle                                                                                         |
|------------------------|----------------------------------------------------------------------------------------------|
| `ErrorLogging`         | Journaliser un `ErrorLogMessage` sans exposer le logger                                      |
| `TransactionalSession` | `beginTransaction()` / `commit()` / `rollBack()` pilotés par le décorateur `WithTransaction` |

---

## 🎯 Pourquoi ces ports sont dans `shared`

Le `Domain` d'une application ne dépend que d'une interface ; l'implémentation vit dans son
`Infrastructure` (Monolog, Doctrine...). Ces ports étaient dupliqués à l'identique entre `happy`
et `main`. Ils sont fournis ici une seule fois.

La règle du dépôt s'applique : un port n'entre dans `shared` que s'il est utilisé par au moins
deux applications backend. `WarningLogging` (happy seul) et `InfoLogging` (main seul) restent
donc dans leur application.

Les ports HTTP (`HttpClient`, `HttpPromise`, `HttpResponse`) sont utilisés par `happy` et `main`,
mais leur contrat n'est pas encore stabilisé. Ils restent dans les applications jusqu'à ce qu'il
le soit.

---

## 📝 `ErrorLogMessage`

`ErrorLogging::log()` reçoit un `ErrorLogMessage`, pas une chaîne. Les ports de ce dépôt prennent
des objets du domaine (`Message`, `ErrorListResponse`, `BroadcastViewModel`...) : le port de
journalisation suit la même règle. Le value object garantit aussi qu'un message vide ne part
jamais dans les journaux.

```php
$this->errorLogger->log(ErrorLogMessage::from(
    \sprintf('Submission dropped for form %s: %s', $formIdentifier->toString(), $errors->toString()),
));
```
