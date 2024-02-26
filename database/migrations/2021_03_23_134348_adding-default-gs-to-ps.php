<?php

use App\Models\Generalsetting;
use App\Models\Pagesetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingDefaultGsToPs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $generalsettings = Generalsetting::all();
        foreach($generalsettings as $generalsetting)
        {
            if($generalsetting->id != 1){
                $pagesettings = Pagesetting::find(1);
                $pagesetting = $pagesettings->replicateWithTranslations();
                $pagesetting->store_id = $generalsetting->id; 
                $pagesetting->save();
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
