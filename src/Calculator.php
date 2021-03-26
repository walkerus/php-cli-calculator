<?php

declare(strict_types=1);

namespace App;

use SplStack;

class Calculator
{
    private array $operations;
    private SplStack $operands;
    private SplStack $operators;

    public function __construct()
    {
        $this->operations = [
            '+' => [
                'operation' => fn(int $a, int $b) => $a + $b,
                'priority' => 1,
            ],
            '-' => [
                'operation' => fn(int $a, int $b) => $a - $b,
                'priority' => 1,
            ],
            '*' => [
                'operation' => fn(int $a, int $b) => $a * $b,
                'priority' => 2,
            ],
            '/' => [
                'operation' => fn(int $a, int $b) => $a / $b,
                'priority' => 2,
            ]
        ];
        $this->operands = new SplStack();
        $this->operators = new SplStack();
    }

    public function calculate(string $expression): float
    {
        $tokens = str_split(str_replace(' ', '', $expression));
        $tokens[] = PHP_EOL;

        foreach ($tokens as $token) {
            $this->handleToken($token);
        }

        return $this->operands->pop();
    }

    private function handleToken(string $token): void
    {
        switch (true) {
            case is_numeric($token):
                $this->operands->push((int) $token);
                break;
            case $this->isOperation($token):
                if ($this->operators->isEmpty()) {
                    $this->operators->push($token);
                    break;
                }

                $currentOperation = $this->operations[$token];
                $previousOperator = $this->operators->top();

                if (!$this->isOperation($previousOperator)) {
                    $this->operators->push($token);
                    break;
                }

                $previousOperation = $this->operations[$previousOperator];

                if ($previousOperation['priority'] > $currentOperation['priority']) {
                    $this->operands->push($this->calculateLastOperation());
                    $this->handleToken($token);
                } else {
                    $this->operators->push($token);
                }

                break;
            case $token === '(':
                $this->operators->push($token);
                break;
            case $token === ')':
                if ($this->operators->top() === '(') {
                    $this->operators->pop();
                    break;
                }

                $this->operands->push($this->calculateLastOperation());
                $this->handleToken($token);

                break;
            case $token === PHP_EOL:
                if ($this->operators->isEmpty()) {
                    break;
                }

                $this->operands->push($this->calculateLastOperation());
                $this->handleToken($token);

                break;
            default:
                break;
        }
    }

    private function calculateLastOperation(): int
    {
        $a = $this->operands->pop();
        $b = $this->operands->pop();
        $operation = $this->operators->pop();

        return $this->operations[$operation]['operation']($b, $a);
    }

    private function isOperation(string $operand): bool
    {
        return array_key_exists($operand, $this->operations);
    }
}
