# Validation

## ✅ `ValidationResult`

Représente le résultat d’une validation métier (ex : contraintes métier, unicité, préconditions...).

- `ValidationResult::valid()`
- `ValidationResult::invalid(ErrorList $errors)`

---

## ✅ `ValidationResult` : pour les règles métier et validations d’entrée

`ValidationResult` est utilisé pour encapsuler le résultat d’une **validation métier** ou d’une **vérification d’entrée** sans lancer d’exception.

---

### 🧪 Validation d’un `Request`

```php
$validationResult = $this->requestValidation->validate($request);

if ($validationResult->hasFailed()) {
    $this->presentErrors($this->errorsPresenter, $validationResult);
    return;
}

$this->userCase->execute($request);
```

Cela permet de chaîner les validations sans casser le flot nominal.

---

### 📌 Exemple : `UserRegisterRequestValidator`

```php
final class UserRegisterRequestValidator implements UserRegisterRequestValidation
{
    public function validate(UserRegisterRequest $request): ValidationResult
    {
        return AssertValidation::validateLazy(fn(LazyAssertion $lazy) => $this->assert($lazy, $request));
    }

    private function assert(LazyAssertion $lazy, UserRegisterRequest $request): void
    {
        AssertValidation::validateLazyField($lazy, $request->email, 'email', [EmailAddress::class, 'validate']);
        AssertValidation::validateLazyField($lazy, $request->language, 'language', [Language::class, 'validate']);
    }
}
```

---

### 📌 Exemple : `UserRegisterPolicyValidator`

```php
final class UserRegisterPolicyValidator implements UserRegisterPolicyValidation
{
    public function __construct(
        private readonly UserUniquenessVerification $uniquenessVerification,
    ) {}

    public function validate(UserRegisterRequest $request): ValidationResult
    {
        return AssertValidation::validateSimple(fn () => $this->verify($request));
    }

    private function verify(UserRegisterRequest $request): void
    {
        $filter = UserFilter::byEmail($request->getEmail())
    
        AssertValidation::validateSimpleField(
            $request->identifier,
            'email',
            fn ($filter) => $this->uniquenessVerification->unique($filter),
            'Email already used'
         );
    }
}
```

---

✅ `ValidationResult` permet de **composer plusieurs validations successives**, tout en conservant une logique pure, testable et sans dépendance à une couche technique (ex: exceptions, HTTP...).

---

## 📏 Validation métier avec `AssertValidation`

La classe utilitaire `AssertValidation` permet de **standardiser la validation métier** en encapsulant des appels à la librairie [`beberlei/assert`](https://github.com/beberlei/assert) tout en produisant des objets `ValidationResult`.

Elle est utilisée pour **éviter les exceptions non maîtrisées**, et garantir un retour typé en cas d’erreurs de validation.

---

### ✅ Méthodes disponibles

| Méthode                         | Usage                                                  |
|----------------------------------|---------------------------------------------------------|
| `validateSimple(callable)`      | Validation directe, sans injection de `LazyAssertion`  |
| `validateLazy(callable)`        | Validation paresseuse avec `LazyAssertion`             |
| `validateSimpleField(...)`      | Valide un champ unique via une fonction de validation  |
| `validateLazyField(...)`        | Pareil, mais injecté dans un `LazyAssertion` groupé    |

---

### 📌 Exemple `validateSimple`

```php
return AssertValidation::validateSimple(function () use ($value) {
    Assertion::minLength($value, 3, 'Value must be at least 3 characters');
});
```

---

### 📌 Exemple `validateLazy`

```php
return AssertValidation::validateLazy(function (LazyAssertion $lazy) use ($request) {
    $lazy
        ->that($request->email, 'email')->email()
        ->that($request->language, 'language')->notEmpty();
});
```

---

### 📌 Exemple `validateLazyField`

```php
return AssertValidation::validateLazy(function (LazyAssertion $lazy) use ($request) {
    AssertValidation::validateLazyField($lazy, $request->email, 'email', [EmailAddress::class, 'validate']);
});
```

---

### 📌 Exemple `validateSimpleField`

```php
return AssertValidation::validateSimple(function () use ($value) {
    AssertValidation::validateSimpleField($value, 'language', [Language::class, 'validate']);
});
```

---

### 🔁 Résultat attendu

Toutes ces méthodes retournent un `ValidationResult` qui peut être utilisé de manière fluide :

```php
$validationResult = $this->validator->validate($request);

if ($validationResult->hasFailed()) {
    $this->presentErrors($presenter, $validationResult);
    return;
}
```
