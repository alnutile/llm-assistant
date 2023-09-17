<?php

namespace Tests\Feature;

use Facades\App\Domains\Tagging\TagModelDescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagModelDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_schema() {
        $tags = TagModelDescription::getTagsTableInfo();
        $this->assertNotNull($tags['table_name']);
        $this->assertNotEmpty($tags['column_names']);

        $message = sprintf("SQL query extracting info to answer the user's question.
SQL should be written using this database schema:
Table: %s
Columns: %s
The query should be returned in plain text, not in JSON.",
        $tags['table_name'], implode(", ", $tags['column_names']));
        put_fixture("tags_table.txt", $message, false);
    }
}
