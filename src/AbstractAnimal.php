<?php
namespace Cowsayphp;

abstract class AbstractAnimal implements AnimalInterface
{
    protected $character;

    /**
     * Make the animal speak.
     * @param $text string A string you want the animal says
     * @return string The animal speaks...
     */
    public function say($text)
    {
        $message = $this->getSpeechBubble($text);
        $bubbleLength = $this->getMaxLineLength($message);
        $characterLength = $this->getMaxLineLength($this->character);
        $extendLength = $characterLength-$bubbleLength;
        $extendBubble = function ($text) use ($extendLength) {
            return implode(
                "\n",
                array_map(
                    function ($line) use ($extendLength) {
                        return str_pad($line, $extendLength, ' ');
                    },
                    explode(
                        "\n",
                        $text
                    )
                )
            );
        };
        
        return str_replace(
          '{{bubble}}',
          $extendBubble($message),
          $this->character
        );
    }

    /**
     * Obtain the message as array wrapping the text
     * @param $text
     * @return array
     */
    public function getMessageLines($text)
    {
        $message = $text;
        $wrapLength = 40;
        // wrap the message to max chars
        $message = wordwrap($message, $wrapLength - 2);
        // split into array of lines
        return explode("\n", $message);
    }

    /**
     * Find the longest line and get the line length
     * @param array $lines
     * @return int
     */
    public function getMaxLineLength($lines)
    {
        if (!is_array($lines)) {
            $lines = explode("\n", $lines);
        }
        $lineLength = 0;
        // find the longest line
        foreach ($lines as $line) {
            $currentLineLength = strlen($line);
            if ($currentLineLength > $lineLength) {
                $lineLength = $currentLineLength;
            }
        }
        return $lineLength;
    }

    /**
     * Obtain the speech bubble.
     * @param $text
     * @return string
     */
    public function getSpeechBubble($text)
    {
        $lines = $this->getMessageLines($text);
        $lineLength = $this->getMaxLineLength($lines);
        $text = '';
        $numberOfLines = count($lines);
        $firstLine = str_pad(array_shift($lines), $lineLength);
        if ($numberOfLines === 1) {
            $text = "< {$firstLine} >";
        } else {
            $lastLine = str_pad(array_pop($lines), $lineLength);
            $text = "/ {$firstLine} \\\n";
            foreach ($lines as $line) {
                $line = str_pad($line, $lineLength);
                $text .= "| {$line} |\n";
            }
            $text .= "\\ {$lastLine} /";
        }
        return $text;
    }
}