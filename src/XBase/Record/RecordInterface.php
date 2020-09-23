<?php

namespace XBase\Record;

use XBase\Column\ColumnInterface;

interface RecordInterface
{
    /**
     * @return int Zero based row index.
     */
    public function getRecordIndex(): int;

    public function isDeleted(): bool;

    /**
     * Get column value.
     *
     * @param ColumnInterface|string $columnName
     */
    public function get($columnName);

    /**
     * Set column value.
     *
     * @param ColumnInterface|string $columnName
     */
    public function set($columnName, $value): self;

    /**
     * @deprecated since 1.3 and will be delete in 2.0. Use (string) $record->get('name')
     */
    public function getString(string $columnName);

    /**
     * @deprecated since 1.3 and will be delete in 2.0. Use (string) $record->get('name')
     */
    public function getObject(ColumnInterface $column);
}
