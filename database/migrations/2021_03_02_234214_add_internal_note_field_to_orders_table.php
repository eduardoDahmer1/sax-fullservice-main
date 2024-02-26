<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInternalNoteFieldToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('internal_note')->nullable()->after('order_note');
        });

        $orders = Order::all(['id','order_note']);
        foreach ($orders as $order) {
            $original_note = $order->order_note;
            $notes = explode("|",$original_note);
            $new_order_note = "";
            $new_internal_note = "";
            foreach ($notes as $note) {
                if(strpos($note,"#:[") === false
                    && strpos($note,"AEX:[") === false
                    && strpos($note,"MELHORENVIO:[") === false
                    && strpos($note,"AEXCODE:[") === false){
                        $new_order_note .= !empty($new_internal_note) ? "|" . $note : $note;
                }else{
                    $new_internal_note .= !empty($new_internal_note) ? "|" . $note : $note;
                }
            }
            Order::where('id',$order->id)->update(['internal_note'=>$new_internal_note,'order_note'=>$new_order_note]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $orders = Order::all(['id','order_note','internal_note']);
        foreach ($orders as $order) {           
            $new_note = !empty($order->order_note) ? $order->order_note."|".$order->internal_note : $order->internal_note;
            Order::where('id',$order->id)->update(['order_note'=>$new_note]);
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('internal_note');
        });
    }
}
