<?php

require __DIR__ . '/../vendor/autoload.php';

use shuryginaKN\calculator\GameController;
use shuryginaKN\calculator\Database;
use shuryginaKN\calculator\Game;
use shuryginaKN\calculator\View;

$dbPath = __DIR__ . '/../db/database.sqlite';
$db = new Database($dbPath);
$game = new Game();
$gameController = new GameController($db, $game);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['player_name'])) {
        $playerName = $_POST['player_name'];
        $expressionData = $game->generateExpression();

        if ($expressionData['expression'] === null) {
            echo "Ошибка при генерации выражения.";
        } else {
            echo View\displayGameForm($playerName, $expressionData['expression'], $expressionData['result'], $db);
        }
    } elseif (isset($_POST['player_answer'])) {
        $playerName = $_POST['player_name_hidden'];
        $expression = $_POST['expression_hidden'];
        $result = (float)$_POST['result_hidden'];

        $gameController->startGameWithData($playerName, $expression, $result, $_POST['player_answer']);
    } elseif (isset($_POST['show_results'])) {
        $results = $db->getGameResults();
        echo View\displayGameResults($results);
    } else {
        echo View\displayStartScreen();
    }
} else {
    echo View\displayStartScreen();
}
