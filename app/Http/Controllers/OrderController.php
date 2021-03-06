<?php

namespace App\Http\Controllers;

use App\Http\Models\Invoice;
use App\Http\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user= Auth::user();
        $invoices=Invoice::ByUser($user->id)->paginate(10);
        return view('account/order-list',compact('invoices'));
    }


    public function view($slug)
    {

        $invoice_details=Invoice::GetID($slug);
        $invoice_id=($invoice_details->get()[0]['id']);
        $invoice=Invoice::findorFail($invoice_id);
        $invoice_items=$invoice->invoice_items->all();
        $order_no=$invoice_details->get()[0]['order_no'];
        $products=array();

        $sub_total=$invoice_details->get()[0]['sub_total'];
        $shipping=$invoice_details->get()[0]['shipping'];
        $tax=$invoice_details->get()[0]['tax'];
        $invoice_date=$invoice_details->get()[0]['created_at'];


        foreach ($invoice_items as $item){
            $product= Product::findorFail($item->product_id);
            $product['qty']=$item->qty;
            array_push($products,$product);
        }


        return view ('account/order',compact('products',
            'order_no','sub_total','shipping','tax','invoice_date'));

    }


}
