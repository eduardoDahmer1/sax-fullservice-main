<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimesToMelhorenvioRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('melhorenvio_requests', function (Blueprint $table) {
            $table->string('preview_url', 255)->nullable();
            $table->string('print_url', 255)->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('generated_at')->nullable();
            $table->datetime('posted_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->datetime('canceled_at')->nullable();
            $table->datetime('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('melhorenvio_requests', function (Blueprint $table) {
            $table->dropColumn('preview_url');
            $table->dropColumn('print_url');
            $table->dropColumn('created_at');
            $table->dropColumn('paid_at');
            $table->dropColumn('generated_at');
            $table->dropColumn('posted_at');
            $table->dropColumn('delivered_at');
            $table->dropColumn('canceled_at');
            $table->dropColumn('expired_at');
        });
    }
}
