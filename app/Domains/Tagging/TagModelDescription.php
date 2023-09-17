<?php

namespace App\Domains\Tagging;

use Illuminate\Support\Facades\Schema;

class TagModelDescription
{
    /**
     * Get column names for a table.
     *
     * @param string $tableName
     * @return array
     */
    public function getColumnNames(string $tableName): array {
        return Schema::getColumnListing($tableName);
    }

    /**
     * Get info for the tags table.
     *
     * @return array
     */
    public function getTagsTableInfo(): array {
        $tableName = 'tags';
        $columnNames = $this->getColumnNames($tableName);

        return [
            'table_name' => $tableName,
            'column_names' => $columnNames
        ];
    }
}
