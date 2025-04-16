<?php

namespace DumpsterfireBase\Hydrator;

class BaseHydrator
{
    public function hydrate(array $data): self
    {
        foreach($data as $key=>$val) {
            if(property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }

        return $this;
    }
}