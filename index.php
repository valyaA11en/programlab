<?php

class CodexLinkProcessor
{
    private array $codexLinks;

    public function __construct(array $codexLinks)
    {
        $this->codexLinks = $codexLinks;
    }

    public function processText(string $text): string
    {
        $regex = '/ст\.\s?(\d+\.\d+|\d+)(?:\s?([A-Za-zА-Яа-яёЁ]+))?/u';

        return preg_replace_callback($regex, function ($matches) {
            return $this->replaceWithLink($matches);
        }, $text);
    }

    private function replaceWithLink(array $matches): string
    {
        $articleNumber = trim($matches[1]);

        if (isset($this->codexLinks[$articleNumber])) {
            $title = $this->codexLinks[$articleNumber]['title'];
            $url = $this->codexLinks[$articleNumber]['url'];

            return $this->generateLink($matches[0], $title, $url);
        }

        return $matches[0];
    }

    private function generateLink(string $text, string $title, string $url): string
    {
        return "<a href=\"$url\" title=\"$title\" target=\"_blank\">$text</a>";
    }
}

$codexLinks = [
    "2" => [
        "title" => "Статья 2. Основные принципы правового регулирования трудовых отношений",
        "url" => "https://my-advo.cat/codex/NDU3Mw=="
    ],
    "5.27" => [
        "title" => "Статья 5.27. Нарушение трудового законодательства",
        "url" => "https://my-advo.cat/codex/NzY4OA=="
    ],
    "5.57" => [
        "title" => "Статья 5.57. Административное правонарушение",
        "url" => "https://my-advo.cat/codex/NzY4OQ=="
    ],
    "12.15" => [
        "title" => "Статья 12.15. Нарушения правил дорожного движения",
        "url" => "https://my-advo.cat/codex/NzY0MQ=="
    ],
    "26" => [
        "title" => "Статья 26. Уровни социального партнерства",
        "url" => "https://my-advo.cat/codex/NDU5OA=="
    ],
    "141.1" => [
        "title" => "Часть 2 статьи 141.1 УК РФ. Преступления, связанные с несообщением информации",
        "url" => "https://my-advo.cat/codex/NzY4MA=="
    ]
];

$inputText = "В статье ст. 2 ТК РФ описаны основные принципы трудовых отношений. 
Также важно учитывать ст. 26 ТК РФ об уровнях социального партнерства. 
В статье ст. 5.27 КоАП РФ рассматривается нарушение трудового законодательства. 
Также необходимо обратить внимание на ст. 5.57 КоАП РФ, которая регулирует административные правонарушения.";

$processor = new CodexLinkProcessor($codexLinks);
$processedText = $processor->processText($inputText);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обработанный текст с ссылками</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Пример текста с ссылками на кодексы</h1>

    <p><strong>Текст до обработки:</strong></p>
    <div class="text-container"><?= htmlspecialchars($inputText) ?></div>

    <p><strong>Текст после обработки:</strong></p>
    <div class="text-container"><?= $processedText ?></div>
</div>

</body>
</html>
