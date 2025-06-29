<?php

class ObjectPropertyData
{
    protected bool $primary = false;
    protected bool $column = false;

    public function setIsPrimary(bool $primary): self
    {
        $this->primary = $primary;
        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function setIsColumn(bool $isCol): self
    {
        $this->column = $isCol;
        return $this;
    }

    public function isColumn(): bool
    {
        return $this->column;
    }
}