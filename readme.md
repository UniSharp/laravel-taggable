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
    
    
### Generate Independent Tag Table

I assume Product model already exists, and you want make this model be taggable. if you don't have any models, you can use following builtin command to generate it

    php artisan make:model Product --migration
    
and use following command to generate tag table and model for your Product


    php artisan taggable:independent_table Product
    
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