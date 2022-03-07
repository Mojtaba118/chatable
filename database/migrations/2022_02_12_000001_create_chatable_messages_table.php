<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vista\Chat\App\Models\Message;

class CreateChatableMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatable_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('chatable_id');
            $table->string('chatable_type');
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type');
            $table->text('message')->nullable();
//            $table->unsignedBigInteger('readed_by_id');
//            $table->string('readed_by_type');
            $table->timestamp('readed_at');
            $table->timestamps();
        });

        Schema::table('chatable_messages', function (Blueprint $table) {
            $table->foreignId('reply_id')->after('uuid')->nullable()->constrained('messages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatable_messages');
    }
}
