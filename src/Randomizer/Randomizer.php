<?php
namespace App\Randomizer;

use App\Randomizer\Interface\RandomizerInterface;
use Exception;

class Randomizer implements RandomizerInterface
{
    protected int $length = 10;

    /**
     * @param ?string $characters
     */
    public function __construct(protected ?string $characters)
    {
        if (is_null($characters)) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $this->characters = $characters;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function randomize(): string
    {
        $charactersLength = strlen($this->characters);

        $string = '';

        for ($i = 0; $i < $this->length; $i++)
        {
            $string .= $this->characters[random_int(0, $charactersLength - 1)];
        }

        return $string;
    }

    public function getLength() : int
    {
        return $this->length;
    }

    public function setLength(int $length) : void
    {
        $this->length = $length;
    }
}