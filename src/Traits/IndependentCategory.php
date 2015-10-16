<?php
namespace Unisharp\Taggable\Traits;

trait IndependentCategory
{
    public function entities()
    {
        $entity_model = $this->entity_model ?: substr(get_class(), 0, count(get_class()) - strlen('Category') - 1);
        return $this->hasMany($entity_model, 'category_id', 'id');
    }
}
