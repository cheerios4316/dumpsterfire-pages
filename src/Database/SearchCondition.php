<?php

namespace DumpsterfirePages\Database;

use DumpsterfirePages\Container\Container;

/**
 * @deprecated NOT FULLY IMPLEMENTED. DO NOT USE. UNSAFE AND NOT WORKING
 */
class SearchCondition
{
    public const AND = "AND";
    public const OR = "OR";
    public const LIKE = "LIKE";

    protected $left = "";
    protected string $comparison = "";
    protected $right = "";

    protected array $params = [];

    public function getString(bool $wrap = false): string
    {        
        $left = is_int($this->left) ? $this->left : '"' . $this->left . '"';
        $right = is_int($this->right) ? $this->right : '"' . $this->right . '"';

        return ($wrap ? '(' : '') . $left . ' ' . $this->comparison . ' ' . $right;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string|int $left
     * @param string $comparison
     * @param string|int $right
     */
    public static function create($left, string $comparison, $right, bool $rightIsRawValue = true, array $params = []): self
    {        
        $condition = self::newObject();

        $condition->left = $left;
        $condition->comparison = $comparison;

        if(!$rightIsRawValue) {
            $condition->right = $right;
        } else {
            $paramName = ":" . $left;
            $condition->right = $paramName;
            $condition->params[$paramName] = $right;
        }

        $condition->params = [
            ...$condition->params,
            ...$params
        ];

        return $condition;
    }

    public static function and(SearchCondition $condition1, SearchCondition $condition2): self
    {
        $cond1string = $condition1->getString(true);
        $cond2string = $condition2->getString(true);

        $params = [
            ...$condition1->getParams(),
            ...$condition2->getParams()
        ];

        return self::create($cond1string, SearchCondition::AND, $cond2string, false, $params);
    }

    public static function or(SearchCondition $condition1, SearchCondition $condition2): self
    {
        $cond1string = $condition1->getString(true);
        $cond2string = $condition2->getString(true);

        $params = [
            ...$condition1->getParams(),
            ...$condition2->getParams()
        ];

        return self::create($cond1string, SearchCondition::OR, $cond2string, false, $params);
    }

    protected static function newObject(): self
    {
        return Container::getInstance()->create(self::class);
    }
}