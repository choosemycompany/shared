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

1. Le UseCase exécute sa logique et présente un objet `TResponse`
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
final class UserRegisterJsonPresenter extends ResourceViewModelPresenter
{
    protected function extract(mixed $response): User
    {
        return $response->user;
    }

    protected function createViewModel(): JsonUserViewModel
    {
        return new JsonUserViewModel($this->resource);
    }
}
```

---

## 🧪 Avantages

- ✅ Découplage complet
- ✅ Uniformisation de la présentation
- ✅ Gestion d’erreurs cohérente
- ✅ Vue unique et typée
