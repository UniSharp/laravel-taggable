<?php

namespace Unisharp\Taggable\Traits;

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
}
