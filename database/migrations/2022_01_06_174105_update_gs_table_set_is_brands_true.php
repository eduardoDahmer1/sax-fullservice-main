<?php

use App\Models\Generalsetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGsTableSetIsBrandsTrue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $gs = Generalsetting::all();
        foreach($gs as $generalsetting)
        {
            $generalsetting->is_brands = true;
            $generalsetting->update();
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
