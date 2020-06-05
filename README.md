# laragrad/eloquent-model-pg-casts

This package provides a trait `\Laragrad\Models\Concerns\PgTypeCastable` that adds into `Eloquent\Model` some PostgreSQL specific fields cast types.

## Installing

Run command in console

	composer require laragrad/eloquent-model-pg-casts

## Added cast types

* **pg_array** - for any array fields
* **pg_text_array** - for text[] fields
* **pg_uuid_array** - for uuid[] fields
* **pg_int_array** - for int[] fields
* **pg_numeric_array** - for numeric[] fields

## Using

For example, table `test_groups` has a field **test_ids** uuid[] and you have model for this table.

Add into model

* Use declaration for trait `\Laragrad\Models\Concerns\PgTypeCastable`
* Add cast type **pg_uuid_array** for attribute **test_ids**

```php
class TestGroup extends Model 
{
    use \Laragrad\Models\Concerns\PgTypeCastable;

    protected $casts = [
    	'test_ids' => 'pg_uuid_array',
    ];
}
```

Code example

```php
>>> $m = new App\TestGroup();
>>> $m->title = 'First group';
>>> $m->test_ids = ['00000000-0000-0000-0000-000000000001','00000000-0000-0000-0000-000000000002'];
>>> $m->save();
>>> $m->refersh();
>>> $m
=> App\TestGroup {#3171
     id: 1,
     title: "First group",
     test_ids: "{00000000-0000-0000-0100-000000000001,00000000-0000-0000-0100-000000000002}",
   }
>>> $m->test_ids
=> [
     "00000000-0000-0000-0100-000000000001",
     "00000000-0000-0000-0100-000000000002",
   ]
>>> $m->toArray()
=> [
     "id" => 1,
     "title" => "First group",
     "test_ids" => [
       "00000000-0000-0000-0100-000000000001",
       "00000000-0000-0000-0100-000000000002",
     ],
   ]
```

