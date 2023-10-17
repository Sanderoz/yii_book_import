<?php

namespace common\components\import;

use Generator;
use JsonStreamingParser\Listener\InMemoryListener;
use JsonStreamingParser\Parser;

class FileImport
{
    public function __construct(
        public string $url,
        public string $filepath
    )
    {
        file_put_contents($this->filepath, fopen($this->url, 'r'));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getJson(): array
    {
        $json = file_get_contents($this->filepath);
        return json_decode($json, true);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getJsonWithListener(): array
    {
        $listener = new InMemoryListener();
        $stream = fopen($this->filepath, 'r');
        try {
            $parser = new Parser($stream, $listener);
            $parser->parse();
            fclose($stream);
        } catch (\Exception $e) {
            fclose($stream);
            throw $e;
        }
        return $listener->getJson();
    }

    /**
     * На случай если в json исходнике будет 1 строка - 1 объект
     * @return iterable
     */
    public function readFile(): iterable
    {
        $file = fopen($this->filepath, 'r');
        while ($line = fgets($file)) {
            yield json_decode($line, true);
        }
        fclose($file);
    }

    /**
     * @return void
     */
    public function unlinkFile(): void
    {
        unlink($this->filepath);
    }
}