# Ports techniques

## 🧱 Ports fournis

| Port                   | Rôle                                                                                                                    |
|------------------------|-------------------------------------------------------------------------------------------------------------------------|
| `ErrorLogging`         | Journaliser un `ErrorLog` (message + exception optionnelle) sans exposer le logger                                      |
| `WarningLogging`       | Journaliser un `WarningLog` (message + exception optionnelle) sans exposer le logger                                    |
| `InfoLogging`          | Journaliser un `InfoLog` (message seul) sans exposer le logger                                                          |
| `TransactionalSession` | `beginTransaction()` / `commit()` / `rollBack()` pilotés par les décorateurs `*UseCaseWithTransaction` des applications |

---

## 🎯 Pourquoi ces ports sont dans `shared`

Le `Domain` d'une application ne dépend que d'une interface ; l'implémentation vit dans son
`Infrastructure` (Monolog, Doctrine...). Ces ports étaient dupliqués à l'identique entre `happy`
et `main`. Ils sont fournis ici une seule fois.

Les trois ports de journalisation forment une famille et vivent ensemble dans `shared`, même si
`WarningLogging` ne sert aujourd'hui qu'à `happy` et `InfoLogging` qu'à `main`. C'est une exception
assumée à la règle des deux applications (`doc/state.md`) : elle garde un seul contrat pour tous
les niveaux de journalisation.

Les ports HTTP (`HttpClient`, `HttpPromise`, `HttpResponse`) sont utilisés par `happy` et `main`,
mais leur contrat n'est pas encore stabilisé. Ils restent dans les applications jusqu'à ce qu'il
le soit.

---

## 📝 `ErrorLog`, `WarningLog`, `InfoLog`

Chaque port de journalisation reçoit son value object, pas une chaîne. Les ports de ce dépôt prennent
des objets du domaine (`Message`, `ErrorListResponse`, `BroadcastViewModel`...) : le port de
journalisation suit la même règle. Le value object garantit aussi qu'un message vide ne part
jamais dans les journaux.

```php
$this->errorLogger->log(ErrorLog::from(
    \sprintf('Submission dropped for form %s: %s', $formIdentifier->toString(), $errors->toString()),
));
```

Dans un `catch`, passer aussi l'exception : le message porte le contexte métier, l'exception porte
la classe, la trace et les `getPrevious()`. L'exception reste optionnelle : une erreur métier n'en a pas.
`WarningLog` suit la même forme. `InfoLog` ne porte qu'un message : une info n'a pas d'exception.

```php
} catch (\Throwable $exception) {
    $this->errorLogger->log(ErrorLog::from(
        \sprintf('Invitation send to main failed for form %s', $formIdentifier->toString()),
        $exception,
    ));
}
```

Côté `Infrastructure`, l'implémentation transmet l'exception sous la clé `exception` du contexte
PSR-3, que Monolog sait formater :

```php
public function log(ErrorLog $errorLog): void
{
    $exception = $errorLog->exception();

    $this->logger->error($errorLog->toString(), null === $exception ? [] : ['exception' => $exception]);
}
```
