<?php
namespace Unisharp\Taggable\Traits;

trait IndependentCategory
{
    public function entities()
    {
        $entity_model = $this->entity_model ?: substr(get_class(), 0, count(get_class()) - strlen('Category') - 1);
        return $this->hasMany($entity_model, 'category_id', 'id');
    }

    public function hasEntities()
    {
        return $this->entities()->count() > 0;
    }

    public function hasSubs()
    {
        return $this->subs()->count() > 0;
    }

    public function subs()
    {
        return $this->hasMany(get_class(), 'parent_id');
    }

    public function hasParent()
    {
        return $this->parent()->exists();
    }

    public function parent()
    {
        return $this->belongsTo(get_class());
    }
}
