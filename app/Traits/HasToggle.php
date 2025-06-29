<?php

namespace App\Traits;

trait HasToggle
{
    public function toggle(string $attribute = 'is_active'): void
    {
        $this->$attribute = !$this->$attribute;
        $this->save();
    }
}
