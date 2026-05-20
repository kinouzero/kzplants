<?php

namespace App\Presenters;

use App\Models\Strain;

class StrainPresenter
{
    public static function templateTags(Strain $strain)
    {
        return view('template.strain.tags', ['strain' => $strain]);
    }

    public static function templateProperties(Strain $strain)
    {
        return view('template.strain.properties', ['strain' => $strain]);
    }

    public static function tagsStyle($strains)
    {
        $tags = [];
        if (! $strains->isEmpty()) {
            foreach ($strains as $strain) {
                foreach ($strain->tags as $tag) {
                    $tags[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);
                }
            }
        }

        return implode(' ', $tags);
    }

    public static function propertiesStyle($strains)
    {
        $properties = [];
        if (! $strains->isEmpty()) {
            foreach ($strains as $strain) {
                foreach ($strain->properties as $property) {
                    $properties[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
                }
            }
        }

        return implode(' ', $properties);
    }
}
