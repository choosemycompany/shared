# Système de Présentation — Clean Architecture

## 🎯 Objectif

Ce système structure la **couche de présentation** dans une architecture Clean en séparant :

- La logique métier (UseCases)
- La gestion des erreurs (`NotFound`, `AccessDenied`, erreurs métier)
- La production d’un `ViewModel` destiné à la sortie (API, Web, etc.)

Il favorise la cohérence, la testabilité et le découplage strict.

---

## 🧩 Composant principal : `ResourceViewModelPresenter`

```php
abstract class ResourceViewModelPresenter implements PresenterState, ViewModelAccess
```

### Typage générique

| Type         | Description                                      | Cas d'application                                    |
|--------------|--------------------------------------------------|------------------------------------------------------|
| `TResponse`  | Réponse brute retournée par le UseCase           | UserRegisterResponse, SurveyListResponse             |
| `TResource`  | Ressource extraite depuis la réponse             | User, Survey, Organization[]                         |
| `TViewModel` | Donnée transformée, prête à être présentée       | UserRegisterJsonViewModel, PaginatedSurveyViewModel  |

---

## 🔁 Cycle de vie standard

1. Le UseCase exécute sa logique et retourne un objet `TResponse`
2. Le `Presenter` :
   - Vérifie si une erreur métier, un refus d’accès ou un `not found` a été présenté
   - Sinon, extrait une `TResource` à partir de la `TResponse`
   - Construit un `TViewModel` depuis cette ressource

```php
$response = $useCase->execute($request);
$presenter->present($response);
return $presenter->viewModel();
```

---

## ❌ Plus besoin de passer le `Presenter` dans `execute()`

Historiquement, certains UseCases recevaient un `Presenter` en paramètre :

```php
$useCase->execute($request, $presenter);
```

Ce modèle reste **possible** si on le souhaite.

Cependant, grâce au nouveau système de `ViewModelPresenter`, il devient désormais **optionnel**.

### ✅ Nouvelle approche

```php
$response = $useCase->execute($request);
$presenter->present($response);
```

Cela permet :

- De rendre les UseCases **plus simples à tester**
- D’exécuter la logique métier indépendamment de toute logique de présentation
- De **découpler encore davantage** le domaine et la présentation

On passe d’un système **"poussé"** (le UseCase pousse sa réponse dans le presenter),  
à un système **"tiré"** (le presenter vient chercher ce qu’il doit afficher).

Cette approche respecte toujours les principes SOLID, tout en **apportant de la souplesse** à l'appelant.


### ✅ Avantages

- Découplage total entre la logique métier et la présentation
- Meilleure testabilité des UseCases
- Moins de paramètres à injecter

---

## 🛑 Gestion des erreurs centralisée

Les erreurs sont gérées par trois presenters injectés dans le `ResourceViewModelPresenter` :

- `ErrorListViewModelPresenter`
- `AccessDeniedViewModelPresenter`
- `NotFoundViewModelPresenter`

### Priorité d’affichage

`ErrorList > AccessDenied > NotFound > ViewModel`

---

## 🔒 Sécurité du Presenter — et gestion avec les décorateurs

Le système impose un cycle de vie strict à tout presenter :

- ✅ `present()` ne peut être appelé **qu’une seule fois**
- ❌ `viewModel()` lève une exception si `present()` n’a pas été préalablement appelé
- 🛑 Si une erreur (`ErrorList`, `AccessDenied`, `NotFound`) a été présentée, elle **prime** sur toute autre sortie `ViewModel`

---

### 🔨 Exemple concret

```php
/**
 * @extends ResourceViewModelPresenter<UserRegisterResponse, UserRetrieve, UserRegisterJsonViewModel>
 */
final class UserRegisterJsonPresenter extends ResourceViewModelPresenter
{
    protected function extract(mixed $response): User
    {
        return $response->user;
    }

    protected function createViewModel(): UserRegisterJsonViewModel
    {
        return new UserRegisterJsonViewModel($this->resource->identifier);
    }
}
```

---

### 🧱 Exemple avec décorateur

Le système de sécurité fonctionne également avec des décorateurs de UseCase.

Prenons le cas d’un décorateur qui ajoute une étape de validation avant d’appeler le UseCase principal :

```php
final class UserRegisterUseCaseWithRequestValidation implements UserRegisteringUseCase
{
    use PresentErrorsTrait;

    public function __construct(
        private readonly UserRegisteringUseCase $userCase,
        #[Autowire(service: ErrorListJsonViewModelPresenter::class)]
        private readonly ErrorListPresenter $errorsPresenter,
        private readonly UserRegisterRequestValidation $requestValidation
    ) {}

    public function execute(UserRegisterRequest $request, UserRegisterPresenter $presenter): void
    {
        $validationResult = $this->requestValidation->validate($request);
        if ($validationResult->hasFailed()) {
            $this->presentErrors($this->errorsPresenter, $validationResult);
            return;
        }

        $this->userCase->execute($request, $presenter);
    }
}
```

Et voici le UseCase principal encapsulé par ce décorateur :

```php
final class UserRegisterUseCase implements UserRegisteringUseCase
{
    public function __construct(
        private readonly UserRegisterCreation $creation,
        private readonly UserRegisterCommand $command,
    ) {
    }

    public function execute(UserRegisterRequest $request, UserRegisterPresenter $presenter): void
    {
        $creationResult = $this->creation->create($request);

        $user = $creationResult->resource();
        $this->command->register($user);

        $presenter->present(
            new UserRegisterResponse(user: $user)
        );
    }
}
```

Même si une erreur est détectée par le décorateur, elle peut être **présentée en amont**, et toute tentative ultérieure de présenter un `ViewModel` par le UseCase principal sera ignorée.

---

### 🛡️ Pourquoi ce système est robuste

#### ✅ 1. Une seule source de vérité
- Une seule méthode `present()` est appelée dans tout le pipeline.
- Toute tentative de double présentation est bloquée.
- Le `viewModel()` renvoie la réponse la plus prioritaire (erreur ou succès).

#### ✅ 2. Compatible avec les décorateurs empilés
- Chaque décorateur peut présenter une erreur avant d’exécuter le UseCase suivant.
- L’état du presenter est partagé et respecté à chaque niveau.

#### ✅ 3. Résistant aux oublis
- Oublier d’appeler `present()` ? → `viewModel()` lève une exception explicite.
- Appeler deux fois `present()` ? → exception.
- Oublier complètement de présenter une réponse (dans les tests ou un handler) ? → comportement bloqué, jamais silencieux.

#### ✅ 4. Testabilité
- L’état du `Presenter` est accessible (`hasBeenPresented()`).
- Test unitaire simple des décorateurs et UseCases sans souci de sérialisation.

#### ✅ 5. Zéro effet de bord
- Une erreur présentée verrouille l’état de sortie.
- Aucun `ViewModel` de succès ne peut la remplacer.

---

## 📚 Presenters spécialisés

### 🎉 Presenters de succès (JSON)

| Classe                            | Usage                       | ViewModel retourné               |
|----------------------------------|-----------------------------|----------------------------------|
| `RegisterJsonViewModelPresenter` | Pour les cas de création    | `RegisterJsonViewModel`         |
| `RetrieveJsonViewModelPresenter` | Pour les cas de lecture     | `RetrieveJsonViewModel`         |
| `UpdateJsonViewModelPresenter`   | Pour les cas de mise à jour | `UpdateJsonViewModel`           |
| `CollectionJsonViewModelPresenter`| Pour les listes (paginées ou non) | `CollectionJsonViewModel` ou `PaginatedCollectionJsonViewModel` |

---

### 🧱 Presenters techniques (erreurs métier, accès, absence de contenu)

Ces Presenters sont utilisés pour renvoyer des réponses techniques ou métier
en cas d'erreur ou d'absence de contenu. Ils sont injectés dans les presenters principaux
pour fournir une sortie standardisée selon la situation.

| Classe                               | Rôle                                           | Type de réponse           |
|-------------------------------------|------------------------------------------------|---------------------------|
| `NotFoundJsonViewModelPresenter`    | Ressource introuvable                          | Erreur `404 Not Found`    |
| `AccessDeniedJsonViewModelPresenter`| Accès interdit à une ressource                 | Erreur `403 Forbidden`    |
| `ErrorListJsonViewModelPresenter`   | Liste d’erreurs métier                         | Erreur métier `422`       |
| `NoContentJsonViewModelPresenter`   | Réponse vide avec possibilité d’erreurs        | Réponse `204 No Content`  |

---

### 🧱 Composition claire

Ces classes peuvent être injectées en tant que services Symfony, et utilisées dans les presenters métiers pour centraliser la transformation des réponses en ViewModels sérialisables.

---

### 🔁 Exemple d’usage combiné

```php
/**
 * @extends RetrieveJsonViewModelPresenter<UserRetrieveResponse, UserRetrieve>
 */
final class UserRetrieveJsonPresenter extends RetrieveJsonViewModelPresenter
{
    protected function extract(mixed $response): MyEntity
    {
        return $response->entity;
    }

    protected function createViewModel(): JsonViewModel
    {
        return $this->initializeViewModel(
            new UserRetrieveViewModel(
                $this->resource->identifier,
            ),
        );
    }
}
```

---

## 🧩 `ErrorListDomainPresenter`

```php
final class ErrorListDomainPresenter implements ErrorListPresenter, PresenterState, ErrorListProvider
{
    private bool $presented = false;

    private ErrorList $errors;

    public function present(ErrorListResponse $response): void
    {
        $this->presented = true;
        $this->errors = $response->errors;
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    public function provide(): ErrorList
    {
        return $this->errors;
    }
}
```

Cette implémentation permet de manipuler une liste d’erreurs métier dans le domaine, sans dépendre d’un format de sortie spécifique. Elle peut être utilisée dans des décorateurs, validateurs ou tests.

---

## 🧱 Presenters abstraits disponibles

Le système propose trois bases différentes de presenters, selon la nature de la réponse à présenter :

| Classe                             | Utilisation principale                         | Particularités                            |
|-----------------------------------|-------------------------------------------------|-------------------------------------------|
| `ResourceViewModelPresenter`      | Pour une réponse contenant une ressource unique| Gère aussi les erreurs                    |
| `CollectionResourceViewModelPresenter` | Pour des collections avec pagination | Gère aussi les erreurs et la pagination   |
| `DirectViewModelPresenter`        | Pour des réponses directes sans extraction     | Aucune gestion d’erreurs, très minimaliste|

---

### 🔎 `ResourceViewModelPresenter`

- Utilisé quand la réponse contient un objet métier unique (ex: `User`, `Organization`, etc.).
- Permet d’extraire et transformer cette ressource en `ViewModel`.

---

### 📦 `CollectionResourceViewModelPresenter`

- Idéal pour des listes de ressources (ex: `Job[]`, `Survey[]`) avec ou sans pagination.
- Extrait la liste et éventuellement les métadonnées de pagination.

---

### ⚡ `DirectViewModelPresenter`

- À utiliser pour les cas très simples ou statiques (ex: réponse booléenne, message technique).
- Pas d’extraction métier : la réponse EST le ViewModel.
- Ne gère pas les erreurs (`ErrorList`, `NotFound`, `AccessDenied`) — tout doit être déjà filtré en amont.

---

Ces trois abstractions permettent de couvrir **tous les formats de réponse possibles** dans la couche de présentation tout en respectant la Clean Architecture.

---

# Résultats métier (`Result`) — Couche Domaine

La couche **Domaine** expose des objets de type `Result` permettant de modéliser proprement les résultats d’un UseCase ou d’une validation métier, sans recourir aux exceptions pour le contrôle de flux.

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

## ✅ `ValidationResult`

Représente le résultat d’une validation métier (ex : contraintes métier, unicité, préconditions...).

- `ValidationResult::valid()`
- `ValidationResult::invalid(ErrorList $errors)`

---

## 🔁 Interfaces partagées

| Interface        | Description                                |
|------------------|--------------------------------------------|
| `FailureResult`  | Fournit `hasFailed()` et `errors()`        |
| `ResultStatus`   | Fournit `hasSucceeded()`                   |

---

## 🎯 Présentation des erreurs avec `PresentErrorsTrait`

Le trait `PresentErrorsTrait` permet de centraliser l'appel au `ErrorListPresenter` :

```php
use PresentErrorsTrait;

if ($result->hasFailed()) {
    $this->presentErrors($presenter, $result);
}
```

Cela améliore la lisibilité des UseCases et rend la gestion des erreurs métier cohérente.

---

Ces objets `Result` permettent une **programmation fonctionnelle et fluide**, tout en restant typés, testables et explicites.

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
