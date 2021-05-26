# Ulrack ORM Extension - Databases to objects

This extension provides an ORM layer for Ulrack. This layer wil simplify the
workflow of working with databases.

## Prerequisites

In order to start working with this extension a database connection is
required. The database connection can be obtained through the
`ulrack/database-extension` package. Refer to this package to setup a
connection. The package `ulrack/migration-extension` can be used to setup a
database and its' migrations.

## Setting up a data source

The data source is used to fetch information from the database. A data source
should be defined per type or table. The data source can be easily created
through configuration. Create a service file in the `configurtion/services`
directory for the data source and add the following content:
```json
{
    "services": {
        "data.source.books": {
            "parent": "orm.abstract.data.source",
            "parameters": {
                "connection": "@{database-connections.main}",
                "table": "book",
                "fields": [
                    "id",
                    "title",
                    "author"
                ]
            }
        }
    }
}
```
The parent is a reference to a default data source supplied in the package.
The value of the `connection` should be a valid reference to a database
connection. The `table` field describes the name of the table for which the
data source is used. And finally the `fields` field described which fields are
available in the table. If this field is left empty, the select queries will
default to using `*`.

## Creating a model

A model is a simple data access object for manipulating and storing data about
an entry. First, create a service definition for the model for later use.

```json
{
    "services": {
        "model.book": {
            "class": "\\MyVendor\\MyProject\\Model\\BookModel",
            "parameters": {
                "data": "@{parameters.data}"
            }
        }
    }
}
```

Then, the model itself can be created. The should extend the `AbstractModel`
provided in this package. This model contains the basic requirements for a
model. The `toArray` method below is used to convert the model to an array
when it is stored in the database. It can be used to output the model to a
user, however it is recommended to monitor the output so no sensitive data is
transported. It is recommended to fix this either at the moment of the output,
or when it is recurring, by adding a separate method for this action.

```php
<?php

namespace MyVendor\MyProject\Model;

use Ulrack\OrmExtension\Component\Model\AbstractModel;

class BookModel extends AbstractModel
{
    /**
     * Set the ID of the book.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->set('id', $id);
    }

    /**
     * Get the ID of the book.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->has('id') ? $this->get('id') : null;
    }

    /**
     * Set the title of the book.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->set('title', $title);
    }

    /**
     * Get the title of the book.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->has('title') ? $this->get('title') : null;
    }

    /**
     * Set the author of the book.
     *
     * @param string $author
     *
     * @return void
     */
    public function setAuthor(string $author): void
    {
        $this->set('author', $author);
    }

    /**
     * Get the author of the book.
     *
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->has('author') ? $this->get('author') : null;
    }

    /**
     * Converts the content from the model to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $book = [
            'title' => $this->get('title'),
            'author' => $this->get('author')
        ];

        if ($this->has('id')) {
            $book['id'] = $this->get('id');
        }

        return $book;
    }
}
```

## Create the repository

The repository will take care of the communication between a data source and
the application.

Start, by adding the following service definition, to your services file:
```json
{
    "services": {
        "repository.books": {
        "class": "\\MyVendor\\MyProject\\Repository\\BookRepository",
            "parameters": {
                "modelService": "services.model.book",
                "collectionService": "services.orm.default.collection",
                "collectionFactory": "@{services.orm.collection.factory}",
                "dataSource": "@{services.data.source.books}"
            }
        }
    }
}
```

The `modelService` field, should be a string representation of the service reference
to the model, this is used in the construction of the model.

The `colllectionService` field should be reference to the default collection
class (but can also be made custom) in string form, this is also used for the
construction of the collection.

The `collectionFactory` field should be supplied with a valid collection
factory reference. One is supplied by default with this package.

Finally, the `dataSource` field should be supplied with a reference to the
earlier created data source.

Then the `BookRepository` class can be created. The following example contains
a minimal markup of what the repository should represent. More methods are
availble through the [AbstractRepository](../../src/Component/Repository/AbstractRepository.php).
E.g. the `search` and `getAll` methods.
```php
<?php

namespace MyVendor\MyProject\Repository;

use MyVendor\MyProject\Model\BookModel;
use Ulrack\OrmExtension\Common\Model\ModelInterface;
use Ulrack\OrmExtension\Component\Repository\AbstractRepository;

class BookRepository extends AbstractRepository
{
    /**
     * Deletes the entity from the database.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function delete(ModelInterface $model): bool
    {
        if ($model instanceof BookModel) {
            if ($model->has('id')) {
                return $this->getDataSource()
                    ->deleteByField(
                        'id',
                        $model->getId()
                    )->isSuccess();
            }
        }

        return false;
    }

    /**
     * Saves the model in the database.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function save(ModelInterface $model): bool
    {
        if ($model instanceof BookModel) {
            $book = $model->toArray();
            $dataSource = $this->getDataSource();
            if (!isset($book['id'])) {
                if ($dataSource->insert($book)->isSuccess()) {
                    $model->setId($dataSource->lastInsertId());
                    return true;
                }
            } else {
                return $dataSource->updateByField(
                    'id',
                    $book['id'],
                    $book
                )->isSuccess();
            }
        }

        return false;
    }
}

```

After clearing the cache, the implementation can be used/requested through the
services layer by referencing the repository with
`@{services.repository.books}`.

## Further reading

[Back to usage index](index.md)

[Installation](installation.md)
