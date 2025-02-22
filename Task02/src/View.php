<?php

namespace shuryginaKN\calculator\View;

use shuryginaKN\calculator\Database;

function displayStartScreen(): string
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Calculator Game</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Welcome to the Calculator game! 🌟</h1>
        <form method="POST">
            <label for="player_name">Enter your name:</label>
            <input type="text" id="player_name" name="player_name" required><br><br>
            <button type="submit">Start Game</button>
        </form>

        <form method="POST">
            <button type="submit" name="show_results">Show Game Results</button>
        </form>

    </body>
    </html>';

    return $html;
}

function displayGameForm(string $playerName, string $expression, float $result, Database $db, string $errorMessage = ''): string
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Calculator Game</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Игра "Калькулятор"</h1>';

    if (!empty($errorMessage)) {
        $html .= '<p style="color:red;">' . htmlspecialchars($errorMessage) . '</p>';
    }

    $html .= '<p>Имя игрока: ' . htmlspecialchars($playerName) . '</p>
        <p>Решите выражение: ' . htmlspecialchars($expression) . ' = ?</p>

        <form method="POST">
            <input type="hidden" name="player_name_hidden" value="' . htmlspecialchars($playerName) . '">
            <input type="hidden" name="expression_hidden" value="' . htmlspecialchars($expression) . '">
            <input type="hidden" name="result_hidden" value="' . htmlspecialchars($result) . '">

            <label for="player_answer">Ваш ответ:</label>
            <input type="text" id="player_answer" name="player_answer" required><br><br>

            <button type="submit">Проверить</button>
        </form>
    </body>
    </html>';

    return $html;
}

function displayResult(string $playerName, string $expression, float $result, float $playerAnswer, bool $isCorrect): string
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Calculator Game - Result</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Результат игры</h1>
        <p>Имя игрока: ' . htmlspecialchars($playerName) . '</p>
        <p>Выражение: ' . htmlspecialchars($expression) . '</p>
        <p>Ваш ответ: ' . htmlspecialchars($playerAnswer) . '</p>
        <p>Правильный ответ: ' . htmlspecialchars($result) . '</p>';

    if ($isCorrect) {
        $html .= '<p style="color:green;">Верно!</p>';
    } else {
        $html .= '<p style="color:red;">Неверно.</p>';
    }

    $html .= '<form method="GET" action="index.php">
                <button type="submit">Start New Game</button>
              </form>';

    $html .= '</body>
    </html>';

    return $html;
}
function displayGameResults(array $results): string
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Game Results</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Game Results</h1>
        <table>
            <thead>
                <tr>
                    <th>Player Name</th>
                    <th>Expression</th>
                    <th>Correct Answer</th>
                    <th>Your Answer</th>
                    <th>Correct?</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($results as $result) {
        $html .= '<tr>
                        <td>' . htmlspecialchars($result['player_name']) . '</td>
                        <td>' . htmlspecialchars($result['expression']) . '</td>
                        <td>' . htmlspecialchars($result['correct_answer']) . '</td>
                        <td>' . htmlspecialchars($result['player_answer']) . '</td>
                        <td>' . ($result['is_correct'] ? 'Yes' : 'No') . '</td>
                        <td>' . htmlspecialchars($result['game_date']) . '</td>
                    </tr>';
    }

    $html .= '</tbody>
        </table>

        <form method="GET" action="index.php">
            <button type="submit">Вернуться к игре</button>
        </form>
        
    </body>
    </html>';

    return $html;
}
