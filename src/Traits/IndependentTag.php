<?php

namespace Unisharp\Taggable\Traits;

use Illuminate\Support\Str;
use InvalidArgumentException;

trait IndependentTag
{
    public function parent()
    {
        return $this->hasOne(get_class($this), 'id', 'parent_id');
    }

    public function subs()
    {
        return $this->hasMany(get_class($this), 'parent_id', 'id');
    }
    public function hasSubs()
    {
        return $this->subs->count() > 0;
    }

    public function hasParent()
    {
        if ($this->parent_id === 0 || is_null($this->parent)) {
            return false;
        } else {
            return true;
        }
    }

    public function addParent($parent)
    {
        $class = get_class($this);
        if (gettype($parent) == 'int') {
            $this->parent_id = $parent;
            $this->save();
        } elseif (gettype($parent) == 'string') {
            $tag = $class::firstOrCreate(['name' => $parent]);
            $tag->name = $parent;
            $tag->save();
        } elseif ($parent instanceof $class) {
            $this->parent_id = $parent->id;
            $this->save();
        } else {
            throw new InvalidArgumentException("only accept int, string or" . get_class($this) . " object");
        }
    }

    public function entities()
    {
        $entity_model = $this->entity_model ?: substr(get_class(), 0, count(get_class()) - strlen('Tag') - 1);
        $entity_reflection = new \ReflectionClass($entity_model);
        $intermediate_table = $this->intermediate_table ?: Str::snake($entity_reflection->getShortName()) . '_tag_map';
        $entity_column = Str::snake($entity_reflection->getShortName());
        return $this->belongsToMany($entity_model, $intermediate_table, 'tag_id', $entity_column . '_id');
    }
}
