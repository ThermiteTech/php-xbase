<?php

namespace XBase\Memo;

class DBase3Memo extends AbstractMemo
{
    const BLOCK_LENGTH = 512;

    public function get($pointer): ?MemoObject
    {
        if (!$this->isOpen()) {
            $this->open();
        }

        if (is_string($pointer)) {
            $pointer = (int) ltrim($pointer, ' ');
        }

        $this->fp->seek($pointer * self::BLOCK_LENGTH);

        $endMarker = $this->getBlockEndMarker();
        $result = '';
        $memoLength = 0;
        while (!$this->fp->eof()) {
            $memoLength++;
            $result .= $this->fp->read(1);

            $substr = substr($result, -3);
            if ($endMarker === $substr) {
                $result = substr($result, 0, -3);
                break;
            }
        }

        $type = $this->guessDataType($result);
        if (MemoObject::TYPE_TEXT === $type) {
            if (chr(0x00) === substr($result, -1)) {
                $result = substr($result, 0, -1); // remove endline symbol (0x00)
            }
            if ($this->convertFrom) {
                $result = iconv($this->convertFrom, 'utf-8', $result);
            }
        }

        return new MemoObject($result, $type, $pointer, $memoLength);
    }

    public function persist(MemoObject $memoObject): MemoObject
    {
        throw new \Exception('not realized'); //todo realize
    }

    protected function calculateBlockCount(string $data): int
    {
        return ceil(strlen($data) + strlen($this->getBlockEndMarker()) / self::BLOCK_LENGTH);
    }

    private function getBlockEndMarker(): string
    {
        return chr(0x1A).chr(0x1A).chr(0x00);
    }
}
