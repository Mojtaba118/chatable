<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vista\Chat\App\Models\Message;

class CreateChatableMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatable_medias', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('message_id')->constrained('chatable_messages');
            $table->text('path');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatable_medias');
    }
}
