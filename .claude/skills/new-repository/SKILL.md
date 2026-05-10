---
name: new-repository
description: Use this skill whenever the user asks to create a repository, add a persistence method, or asks where to put a database query. Also trigger when the user says "I need to fetch X from the database", "add a findBy method", "create the repository for Y", or wonders where to put persistence code.
version: 1.0.0
argument-hint: "[Domain] [Entity]"
allowed-tools: Read, Glob, Grep, Edit, Write, Bash
---

Available domains: !`ls src/Domain/`


# New Repository — Port + Infrastructure Pattern

Repositories are split: the Port interface lives in the domain, the Doctrine implementation lives in Infrastructure. They must always be created together.

## Locations

```
src/Domain/{Feature}/Port/{Entity}RepositoryInterface.php    ← contract (domain)
src/Infrastructure/Persistence/Doctrine{Entity}Repository.php ← implementation (infrastructure)
```

## Rules

- **Interface + Implementation always together** — never create one without the other.
- **Port interface stays in domain** — never import Doctrine classes inside `src/Domain/`.
- **Implementation stays in Infrastructure** — `src/Infrastructure/Persistence/`.
- **Persistence only** — no business logic, no domain rules inside repositories.
- **Bind the interface** — add the interface → implementation binding in `config/services.yaml`.

## Steps

1. Look at an existing Port interface in `src/Domain/{Feature}/Port/` for naming conventions.
2. Look at an existing implementation in `src/Infrastructure/Persistence/` for style.
3. Create the Port interface with the method signatures you need.
4. Create the Doctrine implementation.
5. Register the binding in `config/services.yaml`.
6. Run `make lint && make analyse` — fix any errors before reporting done.

## Port Interface Pattern

```php
<?php

declare(strict_types=1);

namespace App\Domain\{Feature}\Port;

use App\Entity\{Entity};

interface {Entity}RepositoryInterface
{
    public function find(string $id): {Entity};

    public function save({Entity} $entity, bool $flush = true): void;
}
```

## Infrastructure Implementation Pattern

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\{Feature}\Port\{Entity}RepositoryInterface;
use App\Entity\{Entity};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class Doctrine{Entity}Repository extends ServiceEntityRepository implements {Entity}RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, {Entity}::class);
    }

    public function find(mixed $id, mixed $lockMode = null, mixed $lockVersion = null): {Entity}
    {
        $entity = parent::find($id);

        if (null === $entity) {
            throw new {Entity}NotFoundException();
        }

        return $entity;
    }

    public function save({Entity} $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
```

## services.yaml Binding

```yaml
services:
    App\Domain\{Feature}\Port\{Entity}RepositoryInterface:
        class: App\Infrastructure\Persistence\Doctrine{Entity}Repository
```

Add under the existing `services:` block — do not duplicate existing entries.
