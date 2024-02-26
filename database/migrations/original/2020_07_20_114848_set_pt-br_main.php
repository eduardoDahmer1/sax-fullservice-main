<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class SetPtBrMain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rows = DB::table('generalsettings')->get(['id']);
        foreach ($rows as $row) {
            DB::table('generalsettings')
                    ->where('id', $row->id)
                    ->update(['lang_id' => 1]);
        }

        $rows = DB::table('languages')->get(['id']);
        foreach ($rows as $row) {
            if ($row->id == 1) {
                DB::table('languages')
                    ->where('id', 1)
                    ->update([
                        'locale' => 'pt-br',
                        'is_default' => 1,
                        'language' => 'Português',
                        'file' => 'pt-br.json',
                        'rtl' => 0
                    ]);
            } else {
                DB::table('languages')->where('id', $row->id)->delete();
            }
        }

        $rows = DB::table('admin_languages')->get(['id']);
        foreach ($rows as $row) {
            if ($row->id == 1) {
                DB::table('admin_languages')
                    ->where('id', 1)
                    ->update([
                        'name' => 'pt-br',
                        'is_default' => 1,
                        'language' => 'Português',
                        'file' => 'admin_pt-br.json',
                        'rtl' => 0
                    ]);
            } else {
                DB::table('admin_languages')->where('id', $row->id)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
