<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDueDateToDatetimeInDocumentRequirementsTable extends Migration
{
    public function up()
    {
        Schema::table('document_requirements', function (Blueprint $table) {
            $table->dateTime('due_date')->change();
        });
    }

    public function down()
    {
        Schema::table('document_requirements', function (Blueprint $table) {
            $table->date('due_date')->change();
        });
    }
}

