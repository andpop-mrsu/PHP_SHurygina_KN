<?php

namespace shuryginaKN\calculator;

use shuryginaKN\calculator\View;
use shuryginaKN\calculator\Database;
use shuryginaKN\calculator\Game;


class GameController
{
    private Database $db;
    private Game $game;

    public function __construct(Database $db, Game $game)
    {
        $this->db = $db;
        $this->game = $game;
    }

    public function startGameWithData(string $playerName, string $expression, float $result, string $playerAnswer)
    {

        if ($playerAnswer === null || !is_numeric($playerAnswer)) {
            $errorMessage = "Пожалуйста, введите числовой ответ.";
            $html = View\displayGameForm($playerName, $expression, $result, $this->db, $errorMessage);
            echo $html;
            return;
        }

        $playerAnswer = (float)$playerAnswer;
        $isCorrect = abs($playerAnswer - $result) < 0.0001;

        $this->db->saveGameResult($playerName, $expression, $result, $playerAnswer, $isCorrect);

        $html = View\displayResult($playerName, $expression, $result, $playerAnswer, $isCorrect);
        echo $html;

    }
      
}