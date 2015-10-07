<?php

namespace Unisharp\Taggable\Traits;

use Illuminate\Support\Str;

trait IndependentTaggable
{
    public function getTagModelName()
    {
        if (isset($this->tagModel)) {
            return $this->tagModel;
        } else {
            return $this->getTagModelNameDefault();
        }
    }

    public function getTagModelNameDefault()
    {
        return $this->getNamespaceName() . '\\' . config('tagable.suffix', 'TagFor') . $this->getShortName() ;
    }

    public function getShortName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }

    public function getNamespaceName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getNamespaceName();
    }

    public function tags()
    {
        $intermediate_table = $this->intermediate_table ?: Str::snake($this->getShortName()) . '_tags';
        return $this->belongsToMany($this->getTagModelName(), $intermediate_table, 'article_id', 'tag_id');
    }

    public function tag()
    {
        $args = func_get_args();
        if (count($args) == 1 && is_array($args[0])) {
            $tags = $args[0];
        } else {
            $tags = $args;
        }

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function untag()
    {
        $args = func_get_args();
        if (count($args) == 1 && is_array($args[0])) {
            $tags = $args[0];
        } else {
            $tags = $args;
        }

        foreach ($tags as $tag) {
            $this->removeTag($tag);
        }
    }

    public function addTag($tag_name)
    {
        $class = $this->getTagModelName();
        $tag = $class::where('name', $tag_name)->first();
        if (empty($tag)) {
            $tag = $class::create(['name' => $tag_name]);
            $this->tags()->saveMany([$tag]);
        }

        if (!$this->tags->contains($tag)) {
            $this->tags()->attach($tag->id);
        }
    }

    public function removeTag($tag_name)
    {
        $class = $this->getTagModelName();
        $tag = $class::where('name', $tag_name)->first();
        if (!empty($tag) && $this->tags->contains($tag->id)) {
            $this->tags()->detach($tag->id);
        }
    }
}
