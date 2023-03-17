Overview
========

This package provides column type and extended DBAL classes for [PostgreSQL enums](https://www.postgresql.org/docs/current/datatype-enum.html) support for Doctrine and Doctrine migrations. 
Column type is based on enumType option of Column attribute/annotation.

Example
--------

```php 

enum ExampleEnum: string 
{
    case Case1 = 'case_1';
    case Case2 = 'case_2';
}

#[Entity]
class Example
{
    /** ... */

    #[Column(type: 'enum', enumType: ExampleEnum::class)]
    public ExampleEnum $type;
}
```

For the example above the doctrine migrations will generate migration with PostgreSQL enum type creation and column uses this type
```php
public function up(Schema $schema): void
{
    $this->addSql('CREATE TYPE example_enum_type AS ENUM(\'case_1\', \'case_2\')');
    $this->addSql('COMMENT ON TYPE example_enum_type IS \'ExampleEnum\'');
    
    $this->addSql('CREATE TABLE example (..., type example_enum_type NOT NULL, ...)');
    
    /** ... */
}

public function down(Schema $schema): void
{
    /** ... */
    
    $this->addSql('DROP TABLE example');
    $this->addSql('DROP TYPE example_enum_type');
}
```


Supported actions in migrations
-----------------

1. Create new enums based on usages in entities
2. Remove enums based on usages in entities
3. Update existing enum by adding new labels
4. Recreate existing enum for removing labels

:warning: Attention :warning:

Adding/removing labels to existing enums will generate SQL which may fail on execution if your tables use those labels.
Be careful and control your data!