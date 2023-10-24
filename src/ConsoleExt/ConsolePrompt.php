<?php
namespace App\ConsoleExt;
class ConsolePrompt
{
    /**
     * @param string $prompt
     * @param string|bool|array $needle
     * @return string|false
     */
    public function question(string $prompt, string|bool|array $needle) : string|false
    {

        $restart = false;
        $answer = readline($prompt);

        if (gettype($needle) == 'array') {
            foreach ($needle as $item)
            {
                if ($item != $answer) {
                    $restart = true;
                } else {
                    $restart = false;
                    break;
                }
            }

        } elseif ($needle != $answer) {
            $restart = true;
        }

        if ($restart) {
            self::question($prompt, $needle);
        }

        return $answer;
    }
}