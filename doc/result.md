# Result

## 🧱 Résultats fournis

| Classe             | Rôle                                      |
|--------------------|-------------------------------------------|
| `CreationResult`   | Retour d’un processus de création         |
| `ValidationResult` | Retour d’une étape de validation métier   |

---

## ✅ `CreationResult<T>`

Permet de retourner une ressource créée (ex: un `User`, un `Job`, etc.) ou un ensemble d’erreurs métier.

### Méthodes :

- `CreationResult::success($resource)` : succès avec la ressource créée
- `CreationResult::failure(ErrorList $errors)` : échec métier

```php
if ($result->hasSucceeded()) {
    $user = $result->resource();
}
```

En cas d’échec, `resource()` déclenche une exception.

---

## 🔁 Interfaces partagées

| Interface        | Description                                |
|------------------|--------------------------------------------|
| `FailureResult`  | Fournit `hasFailed()` et `errors()`        |
| `ResultStatus`   | Fournit `hasSucceeded()`                   |

---

## 🏭 `CreationResult` : pour les Factories métier

La classe `CreationResult<T>` est spécifiquement conçue pour les **Factories** qui construisent des entités métier.

### Exemple concret : Factory de `UserRegister`

```php
final class UserRegisterFactory implements UserRegisterCreation
{
    public function create(UserRegisterRequest $request): CreationResult
    {
        $userRegister = new UserRegister(
            email: EmailAddress::from($request->email),
        );

        return CreationResult::success($userRegister);
    }
}
```

Cela permet de **centraliser la logique de construction** et de **renvoyer un résultat typé**, tout en encapsulant les erreurs métier dans un `ErrorList`.

---

### Intégration dans un UseCase

```php
$creationResult = $this->creation->create($request);

if ($creationResult->hasFailed()) {
    $this->presentErrors($this->errorsPresenter, $creationResult);

    return;
}

$user = $creationResult->resource();
$this->command->register($user);
```

✅ Grâce à `CreationResult`, la Factory reste **autonome, testable et explicite**, sans propager d’exceptions dans le flow nominal.

---
