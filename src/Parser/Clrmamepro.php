<?php

declare(strict_types=1);

namespace DatName\Parser;

use DatName\Exception\Filesystem\AccessDenied;
use DatName\Exception\Parser as ParserException;
use DatName\Interface\Parser as ParserInterface;
use Parle\Lexer;
use Parle\Parser;
use Parle\Token;
use SplFixedArray;

final class Clrmamepro implements ParserInterface
{
    private Lexer $lexer;
    private Parser $parser;
    private SplFixedArray $productions;

    private static function readFile(string $filename): string
    {
        if (!is_file($filename)) {
            throw new AccessDenied(sprintf("file '%s' does not exist", $filename));
        }
        if (!is_readable($filename)) {
            throw new AccessDenied(sprintf("file '%s' is not readable", $filename));
        }
        $data = file_get_contents($filename);
        if (false === $data) {
            throw new AccessDenied(sprintf("file '%s' cannot be read", $filename));
        }

        return $data;
    }

    public function __construct()
    {
        $this->parser = new Parser();
        $this->parser->token("'('");
        $this->parser->token("')'");
        $this->parser->token('QUOTED_STRING');
        $this->parser->token('STRING');
        $this->productions = new SplFixedArray(8);
        $this->productions[0] = $this->parser->push('START', 'SECTIONS');
        $this->productions[1] = $this->parser->push('SECTIONS', "STRING '(' ATTRIBUTES ')'");
        $this->productions[2] = $this->parser->push('SECTIONS', 'SECTIONS SECTIONS');
        $this->productions[3] = $this->parser->push('ATTRIBUTES', 'STRING VALUE');
        $this->productions[4] = $this->parser->push('ATTRIBUTES', "STRING '(' ATTRIBUTES ')'");
        $this->productions[5] = $this->parser->push('ATTRIBUTES', 'ATTRIBUTES ATTRIBUTES');
        $this->productions[6] = $this->parser->push('VALUE', 'QUOTED_STRING');
        $this->productions[7] = $this->parser->push('VALUE', 'STRING');
        $this->parser->build();
        $this->lexer = new Lexer();
        $this->lexer->push('[(]', $this->parser->tokenId("'('"));
        $this->lexer->push('[)]', $this->parser->tokenId("')'"));
        $this->lexer->push('["]([^"]|\\\\["])*["]', $this->parser->tokenId('QUOTED_STRING'));
        $this->lexer->push('[^\s]{-}["]+', $this->parser->tokenId('STRING'));
        $this->lexer->push('[\s]+', Token::SKIP);
        $this->lexer->build();
    }

    public function parse(string $input): iterable
    {
        $attributes = [];
        $depth = 1;
        $sections = [];
        $string = '';
        for ($this->parser->consume($input, $this->lexer); Parser::ACTION_ACCEPT != $this->parser->action; $this->parser->advance()) {
            switch ($this->parser->action) {
                case Parser::ACTION_ERROR:
                    throw new ParserException('wtf?');
                    break;
                case Parser::ACTION_REDUCE:
                    switch ($this->parser->reduceId) {
                        case $this->productions[1]:
                        case $this->productions[4]:
                            $section = [];
                            foreach (array_splice($attributes, -$depth) as [$name, $value]) {
                                if (is_array($value)) {
                                    $section[$name][] = $value;
                                } else {
                                    $section[$name] = $value;
                                }
                            }
                            if ($this->parser->reduceId == $this->productions[4]) {
                                $attributes[] = [$this->parser->sigil(0), $section];
                            } else {
                                $sections[$this->parser->sigil(0)][] = $section;
                            }
                            $depth = 1;
                            break;
                        case $this->productions[3]:
                            $attributes[] = [
                                $this->parser->sigil(0),
                                $string,
                            ];
                            break;
                        case $this->productions[5]:
                            $depth++;
                            break;
                        case $this->productions[6]:
                            $string = trim(preg_replace('/\\\\"/', '"', $this->parser->sigil(0)), '"');
                            break;
                        case $this->productions[7]:
                            $string = $this->parser->sigil(0);
                            break;
                    }
                    break;
            }
        }

        return $sections;
    }

    public function parseFile(string $filename): iterable
    {
        return $this->parse(self::readFile($filename));
    }

    public function validate(string $input): bool
    {
        return $this->parser->validate($input, $this->lexer);
    }

    public function validateFile(string $filename): bool
    {
        return $this->validate(self::readFile($filename));
    }
}
