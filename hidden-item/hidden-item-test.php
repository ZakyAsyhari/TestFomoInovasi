<?php

class HiddenItemGame
{
    private array $grid;

    public function __construct()
    {
        $this->grid = [
            ['.', '.', '.', '.', '.'],
            ['.', '#', '#', '.', '.'],
            ['.', '.', 'X', '.', '.'],
            ['.', '#', '.', '.', '.'],
            ['.', '.', '.', '#', '.'],
        ];
    }

    public function run(int $a, int $b, int $c): void
    {
        $start = $this->findPlayer();

        echo "Player start position: ({$start[0]}, {$start[1]})\n\n";

        $probablePoints = $this->calculateProbablePoints($start, $a, $b, $c);

        echo "Probable coordinate points:\n";

        foreach ($probablePoints as $point) {
            echo "({$point[0]}, {$point[1]})\n";
        }

        echo "\nGrid with probable locations:\n";

        $this->markProbablePoints($probablePoints);

        $this->printGrid();
    }

    private function findPlayer(): array
    {
        foreach ($this->grid as $row => $cols) {
            foreach ($cols as $col => $value) {
                if ($value === 'X') {
                    return [$row, $col];
                }
            }
        }

        throw new Exception("Player position not found");
    }

    private function calculateProbablePoints(
        array $start,
        int $a,
        int $b,
        int $c
    ): array {
        [$row, $col] = $start;

        $points = [];
        for ($i = 1; $i <= $a; $i++) {
            $newRow = $row - $i;

            if ($this->isValidPoint($newRow, $col)) {
                $points[] = [$newRow, $col];
            }
        }
        $row = $row - $a;

        for ($i = 1; $i <= $b; $i++) {
            $newCol = $col + $i;

            if ($this->isValidPoint($row, $newCol)) {
                $points[] = [$row, $newCol];
            }
        }
        $col = $col + $b;

        for ($i = 1; $i <= $c; $i++) {
            $newRow = $row + $i;

            if ($this->isValidPoint($newRow, $col)) {
                $points[] = [$newRow, $col];
            }
        }

        return $points;
    }

    private function isValidPoint(int $row, int $col): bool
    {
        return isset($this->grid[$row][$col]) &&
               $this->grid[$row][$col] !== '#';
    }

    private function markProbablePoints(array $points): void
    {
        foreach ($points as [$row, $col]) {
            if ($this->grid[$row][$col] === '.') {
                $this->grid[$row][$col] = '$';
            }
        }
    }

    private function printGrid(): void
    {
        foreach ($this->grid as $row) {
            echo implode(' ', $row) . "\n";
        }
    }
}

$game = new HiddenItemGame();
$game->run(1, 2, 1);
