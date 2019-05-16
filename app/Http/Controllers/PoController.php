<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pocreated;
use App\poproduct;
use App\poserve;
use App\prodservelist;
use App\pocreated_details;
use App\receivinginventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;
use GuzzleHttp\Client;

class PoController extends Controller
{

    public function addToList(Request $request){

        try{
                $date_create = date('Y-m-d H:i:s');

                $validator = Validator::make($request->all(), [
                                        'info' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }
               
                $obj = json_decode($request->input('info'));
               
                if(!$obj->data){
                   
                    return response()->json(['status'=>'error','message'=>'Qty is required']);
                }

                $count = '';
                foreach($obj->data as $row)
                {
                    $checkExist = pocreated_details::select('controlno','product_code')
                                                            ->where('controlno',$row->Controlno)
                                                            ->where('product_code',$row->Productcode)
                                                            ->first();
                    if(count($checkExist) > 0){
                        return response()->json(['status'=>'error','message'=>'Product already Exist - '.$row->Productname]);
                        break;
                    }
                    pocreated_details::create([
                                                'tran_no'        => $row->Id,
                                                'head_id'        => $row->Head_id,
                                                'controlno'      => $row->Controlno,
                                                'product_code'   => $row->Productcode,
                                                'product_name'   => $row->Productname,
                                                'qty'            => $row->Qty,
                                             ]);
                    $count = 1;
                }

                if($count == 1){
                    return response(['status'=>'success', 'message' => '1']);
                }

        }catch(\Exception $e){
            return $e;
        }

    }

    public function getDataFromInventory(Request $request){

        try{
            $store = receivinginventory::Select('store')->where('control_no',$request->input('controlno'))->get();

            return \Response::json($store);

        }catch(\Exception $e){
            return $e;
        }

    }

    public function displayOrderProductDetails(Request $request){
        $details = pocreated_details::select('id','product_code','product_name','qty')->where('controlno', $request->input('controlno'))->get();

        return \Response::json($details);
    }

    public function removeProductDetails($id){
        //return $id;
        $pocreateddetails = pocreated_details::findOrFail($id);

        if($pocreateddetails->delete()){
                return response(['status'=>'success', 'message' => 'Item successfully removed']);
        }
    }

    public function removeHeaders($id){
        //return $id;
        $getHeaderControlno = pocreated::select('po_no')->where('id',$id)->first();
        $removeDetails = pocreated_details::where('controlno',$getHeaderControlno->po_no)->delete();
        $poheaders = pocreated::findOrFail($id);

        if($poheaders->delete()){
                return response(['status'=>'success', 'message' => 'Header details successfully removed']);
        }
    }

    public function displayHeader(){
        try{
            
               $details = pocreated::select('id','controlno','po_no','store','store_name','po_date','received_by','created_at')->get();
                return (DataTables::of($details)
                    ->addColumn('action', function($details){
                                    return ' <button class="btn btn-primary"   id="'.$details->id.'@'.$details->po_no.'@'.$details->store.'@'.$details->store_name.'@'.$details->po_date.'@'.$details->controlno.'" onclick="getInfo(this.id)"  style="cursor:pointer;"><i class="fa fa-edit"></i></button>';
                                })->make(true));
        }catch(\Exception $e){
             return $e;
        }

    }

    public function savePoHead(Request $request){

        try{
                $date_create = date('Y-m-d H:i:s');
                $validator = Validator::make($request->all(), [
                                        'store'   => 'required',
                                        'po_date' => 'required',
                                        'controlno'  => 'required',
                                        'user'       => 'required',
                                        'po_no'       => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        $pocreated = pocreated::select('id')->where('po_no',$request->input('controlno'))->get();

                        if(count($pocreated) > 0){
                            return response(['status'=>'error', 'message' => 'PO already recorded.']);
                        }else{

                            $create = pocreated::create([
                                                    'po_no'       => $request->input('po_no'),
                                                    'controlno'       => $request->input('controlno'),
                                                    'store'       => $request->input('store'),
                                                    'store_name'  => $request->input('storename'),
                                                    'po_date'     => $request->input('po_date'),
                                                    'received_by' => $request->input('user'),
                                                    'created_at'  => $date_create,
                                                ]);

                            return response(['status'=>'success', 'message' => 'PO Header saved.']);
                        }

        }catch(\Exception $e){
            return $e;
        }
    }

    public function updatePoHead(Request $request){

        try{
                $date_create = date('Y-m-d H:i:s');

                $validator = Validator::make($request->all(), [
                                        'store'   => 'required',
                                        'po_date' => 'required',
                                        'c_no'    => 'required',
                                        'po_no'    => 'required',
                                        'user'    => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        $pocreated = pocreated::where('id',$request->input('recid'))->first();

                            if($pocreated->po_no != $request->input('c_no')){
                                $podetails = pocreated_details::where('controlno',$pocreated->po_no)->update(['controlno' => $request->input('c_no')]);
                            }


                             $pocreated->po_no = $request->input('po_no');
                             $pocreated->controlno = $request->input('c_no');
                             $pocreated->store = $request->input('store');
                             $pocreated->store_name = $request->input('storename');
                             $pocreated->po_date = $request->input('po_date');
                             $pocreated->updated_at = $date_create;
                             $pocreated->save();

                            return response(['status'=>'success', 'message' => 'PO Header Updated.']);
        }catch(\Exception $e){
            return $e;
        }

    }

    public function getPoCreate($po){
        try{
        $po = pocreated::select('store','store_name','po_date')->where('po_no', $po)->get();

        return \Response::json($po);
         }catch(\Exception $e){
                    return $e;
        }
    }

    public function displayraw($tranno){

        $data = explode("@",$tranno);
        $tran_no = $data[0];
        $token = $data[1];

        try{
            
                $client = new Client();
                $response = $client->request('POST',
                    'http://localhost:82/OroApi/public/api/displayrawData?tranno='.$tran_no,
                     [
                        'headers' => [
                                         'Authorization' => 'Bearer ' . $token
                                     ],
                     ]
                );

                $details = json_decode($response->getBody());
                       
                return (DataTables::of($details))->make(true);
        }catch(\Exception $e){
             return $e;
        }
    }

    public function savePoServe(Request $request){
            
            $date_create = date('Y-m-d H:i:s');
            try{

                     $validator = Validator::make($request->all(), [
                                        'po_no'   => 'required',
                                        'store'   => 'required',
                                        'po_date' => 'required',
                                        'drsi'    => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        $count = poserve::select('tran_no')->where('po_no', $request->input('po_no'))->get();

                         if(count($count) > 0){
                                return response(['status'=>'error', 'message' => 'PO already recorded']);
                            }else{

                                $poserves = poserve::create([
                                            'po_no'         => $request->input('po_no'),
                                            'store_code'    => $request->input('storecode'),
                                            'store'         => $request->input('store'),
                                            'po_date'       => $request->input('po_date'),
                                            'dr_sr'         => $request->input('drsi'),
                                            'po_serve_date' =>  $request->input('trandate'),
                                            'tran_no'       => $request->input('tranno'),
                                            'created_by'    => $request->input('user'),
                                            'date_created'  => $date_create,
                                            
                                ]);

                                    $client = new Client();
                                        $response = $client->request('POST',
                                                    'http://localhost:82/OroApi/public/api/displayrawData?tranno='.$request->input('tranno'),
                                                     [
                                                        'headers' => [
                                                                        'Authorization' => 'Bearer ' . $request->input('token')
                                                                     ],
                                                            ]
                                                );

                                               $po_item_id =  poserve::select('id')->where('tran_no', $request->input('tranno'))->first();
                                               $details = json_decode($response->getBody());

                                                $detailsarr = [];
                                                foreach($details as $data){
                                                $detailsarr[] = [
                                                                'po_serve_id'  => $po_item_id->id,
                                                                'product_code' => $data->PROD_SYS_CODE,
                                                                'product'      => $data->PROD_DESC,
                                                                'qty_serve'    => $data->QTY
                                                                ];

                                                }

                                                prodservelist::insert($detailsarr); 
                                  return response(['status'=>'success', 'message' => 'PO successfully saved']);

                            }
            }catch(\Exception $e){
                    return $e;
            }   
    }

    public function displayPOservecreated($user){

        try{
               $details = poserve::select('id','po_no','store_code','store','po_date','dr_sr','po_serve_date','date_created')->where('created_by', $user)->get(); 
                return (DataTables::of($details)
                    ->addColumn('action', function($details){
                                    return ' <button class="btn btn-primary"   id="'.$details->id.'@'.$details->po_no.'@'.$details->store_code.'@'.$details->store.'@'.$details->po_date.'@'.$details->dr_sr.'@'.$details->po_serve_date.'" onclick="getInfo(this.id)"  style="cursor:pointer;"><i class="fa fa-edit"></i></button>';
                                })->make(true));
            
        }catch(\Exception $e){
             return $e;
        }
    }

    public function removePoServe($id){

        $poservelistitem = prodservelist::where('po_serve_id', $id)->delete();
        $serve = poserve::findOrFail($id);
        if($serve->delete()){
                return response(['status'=>'success', 'message' => 'Po Serve successfully removed']);
        }
    }  
}
