<?php

use App\Models\Childcategory;
use App\Models\Subcategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRetroactiveChildcategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $childs = Childcategory::all();
        foreach($childs as $child){
         $ch = $child->subcategory->category->id;
         $child->update(array('category_id' => $ch));
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
