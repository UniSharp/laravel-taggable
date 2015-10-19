## Laravel Taggable

### Introduction

this api can handle tag in independent tables or use only one table 

### Install Taggable 

composer.json:

    "require" : {
        "unisharp/laravel-taggable" : "dev-master"
    }, 
    "repositories": {
        "type": "git",
        "url": "https://github.com/UniSharp/laravel-taggable.git"
    }

save it and then 

    composer update    
    
### Configure Taggable

* config/app.php

    * providers:
    
            Unisharp\Taggable\TaggableServiceProvider::class,


### Introduction

* IndependentTaggable: it's use independent table to save your tag 

    eg. if Product is exists, you can use commad to generate ProductTag Model
    
* IndependentCategorizable: it's can categorize for your model

#### What different between tag and category?

     Tag is a many to many relationship, a taggalble entity can tag many tags,
     and there're many entities can belong a tag.
     
     However, a entity can only belong one category, it's one to one relation, and there're many entities can belong a category.
     
### Generate Independent Tag Table

I assume Product model already exists, and you want make this model be taggable. if you don't have any models, you can use following builtin command to generate it

    php artisan make:model Product --migration
    
and use following command to generate tag table and model for your Product


    php artisan taggable:independent_tag_table Product
    
You will see there's ProductTag model under `app/` folder

Now, add trait for your product model let it be taggable.

    use Unisharp\Taggable\Traits\IndependentTaggable;

    class Product extends Model
    {
        use IndependentTaggable
    }
    

### Use Independent Tag


* tag your Model

        $product = Product::find(1);
        $product->tag('new_tag'); // only string
        $product->tag('tag1', 'tag2', 'tag3'); // multi string also work
        $product->tag(['tag1', 'tag2']); // array is acceptable
        
* untag it

        $product->untag('new_tag');
        $product->untag('tag1', 'tag2', 'tag3');
        $product->untag(['tag1', 'tag2']);

* get your tags

        $product->tags // it will return ProductTag back
        
* list your entity which belong specified tag

        $tag = ProductTag::find(1);
        $tag->entities // it will return Products back


### Generate Independent Category

Just like independent tag, you can generate independent category migration by command.

    php artisan taggable:independent_category_table Product

it will generate ProductCategory for Product

and you can add category trait for Product just like before.

    use Unisharp\Taggable\Traits\IndependentIndependentCategorizable;

    class Product extends Model
    {
        use IndependentCategorizable;
    }

### Use Independent Category

* categorize your model

         $product = Product::find(1);
         $product->categorize('free'); // use string
         $product->categorize(1); // use product_category id
     
* decategorize

        $product->decategorize();

* list your model's category

         $product->catetory // list its category, it return ProductCategory
    