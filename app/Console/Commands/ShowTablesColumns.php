<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowTablesColumns extends Command
{
    protected $signature = 'db:columns';
    protected $description = 'عرض أسماء الأعمدة لكل الجداول';

    public function handle()
    {
        $database = DB::getDatabaseName();
        $tables = DB::select("SHOW TABLES");
        $key = "Tables_in_{$database}";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $this->info("جدول: $tableName");

            $columns = Schema::getColumnListing($tableName);
            foreach ($columns as $column) {
                $this->line(" - $column");
            }

            $this->line(str_repeat('-', 40));
        }

        return 0;
    }
}
