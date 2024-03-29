<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUserConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_conversations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('subject', 191);
            $table->integer('user_id');
            $table->text('message');
            $table->timestamps();
            $table->enum('type', ['Ticket', 'Dispute'])->nullable();
            $table->text('order_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_conversations');
    }
}
