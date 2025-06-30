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

| Type         | Description                                      |
|--------------|--------------------------------------------------|
| `TResponse`  | Réponse brute retournée par le UseCase           |
| `TResource`  | Ressource extraite depuis la réponse             |
| `TViewModel` | Donnée transformée, prête à être présentée       |

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

### Avant

```php
$useCase->execute($request, $presenter);
```

### Maintenant

```php
$response = $useCase->execute($request);
$presenter->present($response);
```

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

## ✅ Sécurité

- `present()` ne peut être appelé qu’une fois
- `viewModel()` lève une exception si aucun `present()` n’a été fait
- Si une erreur a été présentée, elle prend le dessus sur le ViewModel par défaut

---

## 🔨 Exemple concret

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

## 🧪 Avantages

- ✅ Découplage complet
- ✅ Uniformisation de la présentation
- ✅ Gestion d’erreurs cohérente
- ✅ Vue unique et typée

---

## 📚 Présentateurs spécialisés

### 🎉 Présentateurs de succès (JSON)

| Classe                            | Usage                       | ViewModel retourné               |
|----------------------------------|-----------------------------|----------------------------------|
| `RegisterJsonViewModelPresenter` | Pour les cas de création    | `RegisterJsonViewModel`         |
| `RetrieveJsonViewModelPresenter` | Pour les cas de lecture     | `RetrieveJsonViewModel`         |
| `UpdateJsonViewModelPresenter`   | Pour les cas de mise à jour | `UpdateJsonViewModel`           |
| `CollectionJsonViewModelPresenter`| Pour les listes (paginées ou non) | `CollectionJsonViewModel` ou `PaginatedCollectionJsonViewModel` |

---

### ❌ Présentateurs d’erreurs

| Classe                               | Rôle                                    |
|-------------------------------------|-----------------------------------------|
| `NotFoundJsonViewModelPresenter`    | Ressource introuvable                   |
| `AccessDeniedJsonViewModelPresenter`| Accès interdit à une ressource          |
| `ErrorListJsonViewModelPresenter`   | Liste d’erreurs métier                  |
| `NoContentJsonViewModelPresenter`   | Réponse vide (204), avec fallback erreurs |

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
    private ErrorList $errors;

    public function present(ErrorListResponse $response): void
    {
        $this->errors = $response->errors;
    }

    public function hasBeenPresented(): bool
    {
        return isset($this->errors);
    }

    public function provide(): ErrorList
    {
        return $this->errors;
    }
}
```

Cette implémentation permet de manipuler une liste d’erreurs métier dans le domaine, sans dépendre d’un format de sortie spécifique. Elle peut être utilisée dans des décorateurs, validateurs ou tests.
