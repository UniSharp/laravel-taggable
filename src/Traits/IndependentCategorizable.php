<?php
namespace Unisharp\Taggable\Traits;

trait IndependentCategorizable
{

    public function getCategoryModelName()
    {
        if (isset($this->tagModel)) {
            return $this->tagModel;
        } else {
            return $this->getCategoryModelNameDefault();
        }
    }

    public function getCategoryModelNameDefault()
    {
        return $this->getNamespaceName() . '\\' . $this->getShortName() . config('tagable.suffix', 'Category');
    }

    public function categorize($category)
    {
        $reflect = new \ReflectionClass($this);
        $category_entity = $reflect->getNamespaceName() . '\\' . $reflect->getShortName() . 'Category';
        if (gettype($category) == 'integer') {
            $this->category_id = $category;
        } elseif (gettype($category) == 'string') {
            $category = $this->findOrNewCategoryByName($category);
            $this->category_id = $category->id;
        } elseif ($category instanceof $category_entity) {
            $this->category_id = $category->id;
        } else {
            throw new \InvalidArgumentException();
        }

        $this->save();
    }

    public function findOrNewCategoryByName($name)
    {
        $reflect = new \ReflectionClass($this);
        $category_entity = $reflect->getNamespaceName() . '\\' . $reflect->getShortName() . 'Category';
        $category = $category_entity::where('name', $name)->first();
        if (empty($category)) {
            $category = $category_entity::create([
                'name' => $name,
            ]);
        }

        return $category;
    }

    public function category()
    {
        $reflect = new \ReflectionClass($this);
        $category_entity = $reflect->getNamespaceName() . '\\' . $reflect->getShortName() . 'Category';
        return $this->hasOne($category_entity, 'id', 'category_id');
    }
}
