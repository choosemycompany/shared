# State

## 🧱 Contrats fournis

| Contrat              | Rôle                                                   |
|----------------------|--------------------------------------------------------|
| `ErrorState`         | Le use case a produit des erreurs métier (`ErrorList`) |
| `ErrorStateTrait`    | Implémentation de référence de `ErrorState`            |
| `NotFoundState`      | La ressource ciblée n'existe pas                       |
| `NotFoundStateTrait` | Implémentation de référence de `NotFoundState`         |

---

## 🎯 Pourquoi ces contrats sont dans `shared`

Un `State` est l'objet que se passent les décorateurs d'un use case (`execute(Request, State)`).
Chaque décorateur y écrit le fait métier qu'il a établi (erreurs de validation, ressource
introuvable) et le décorateur de présentation, en bout de pile, le lit pour choisir la réponse.

Ces contrats ne portent aucune règle métier propre à une application : `happy` et `main`
en avaient chacun une copie identique. Ils sont donc fournis ici, une seule fois, et les
applications composent leurs `State` concrets avec les traits.

La règle du dépôt s'applique : un contrat n'entre dans `shared` que s'il est utilisé par au
moins deux applications backend. C'est pourquoi `AccessDeniedState`, utilisé par `main` seul,
reste dans `main`.

---

## ✅ Invariants garantis par les traits

- **Fail fast** : lire `errors()` avant `setErrors()` lève une `\LogicException`.
- **Double écriture interdite** : `setErrors()` et `markNotFound()` lèvent une `\LogicException`
  au second appel. Un décorateur qui écrit deux fois le même fait révèle un problème d'ordre
  dans la pile, pas un cas métier.
- **Mutation protégée** : `assertNoErrors()` et `assertNotNotFound()` permettent à un `State`
  concret de refuser toute mutation une fois qu'il est en échec.
- **Méthodes `final`** : un `State` concret compose les traits, il ne les redéfinit pas.

```php
final class UserRegisterState implements ErrorState, NotFoundState
{
    use ErrorStateTrait;
    use NotFoundStateTrait;

    private ?UserRegister $user = null;

    public function setUser(UserRegister $user): void
    {
        $this->assertNoErrors();

        $this->user = $user;
    }
}
```

---

## 🖨️ Présenter les erreurs d'un `State`

Dans une pile `State`, les décorateurs de validation n'appellent aucun presenter : ils écrivent
dans le `State` et s'arrêtent. Le décorateur le plus externe (`...UseCaseWithErrorListPresentation`)
lit `$state->errors()` et présente une `ErrorListResponse`.

```php
final class UserRegisterUseCaseWithErrorListPresentation implements UserRegisteringUseCase
{
    public function execute(UserRegisterRequest $request, UserRegisterState $state): void
    {
        $this->innerUseCase->execute($request, $state);

        if ($state->hasErrors()) {
            $this->errorPresenter->present(new ErrorListResponse($state->errors()));
        }
    }
}
```

`PresentErrorsTrait` reste réservé à la génération précédente, où chaque décorateur de
validation présente lui-même un `FailureResult`.
