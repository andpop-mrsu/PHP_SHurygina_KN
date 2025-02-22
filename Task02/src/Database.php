<?php

namespace shuryginaKN\calculator;

class Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('sqlite:../db/database.sqlite');
        $this->createTables();
    }
    private function createTables()
    {
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS players (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    date DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS games (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    player_id INTEGER NOT NULL,
                    expression TEXT NOT NULL,
                    correct_answer REAL NOT NULL,
                    player_answer REAL NOT NULL,
                    is_correct INTEGER NOT NULL,
                    game_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (player_id) REFERENCES players (id)
                );
            ");
        } catch (\PDOException $e) {
            echo "Ошибка при создании таблиц: " . $e->getMessage();
            die();
        }
    }

    public function saveGameResult(string $playerName, string $expression, float $correctAnswer, float $playerAnswer, bool $isCorrect): void
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO players (name) VALUES (?)");
            $stmt->execute([$playerName]);
            $playerId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("
                INSERT INTO games (player_id, expression, correct_answer, player_answer, is_correct)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$playerId, $expression, $correctAnswer, $playerAnswer, (int)$isCorrect]);

            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            echo "Ошибка при сохранении результата игры: " . $e->getMessage();
            die();
        }
    }

    public function getGameResults(): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    p.name AS player_name,
                    g.expression,
                    g.correct_answer,
                    g.player_answer,
                    g.is_correct,
                    g.game_date
                FROM games g
                JOIN players p ON g.player_id = p.id
                ORDER BY g.game_date DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Ошибка при получении результатов игр: " . $e->getMessage();
            return [];
        }
    }
}
