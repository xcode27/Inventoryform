<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\receivinginventory;
use App\submittedform;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;
use Excel;

class invreceivingform extends Controller
{
    //
    public function saveInventory(Request $request){
    		
    		$date_create = date('Y-m-d H:i:s');
    		try{

    				 $validator = Validator::make($request->all(), [
                                        'store' => 'required',
                                        'inv_date' => 'required',
                                        'supervisor' => 'required',
                                        'control_no' => 'required',
                                        'user' => 'required',
                                        'storename' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        $inventory = receivinginventory::select('store')->where('store', $request->input('store'))
                        												->where('inventory_date', $request->input('inv_date'))
                        												->get();

                         if(count($inventory) > 0){
                                return response(['status'=>'error', 'message' => 'Inventory already recorded']);
                            }else{

                                $inv = receivinginventory::create([
                                            'control_no' => $request->input('control_no'),
                                            'store' => $request->input('store'),
                                            'supervisor' => $request->input('supervisor'),
                                            'promo' => $request->input('promo'),
                                            'inventory_date' => $request->input('inv_date'),
                                            'received_by' => $request->input('user'),
                                            'created_at' =>  $date_create,
                                            'store_name' => $request->input('storename'),
                                            
                                ]);

                                return response(['status'=>'success', 'message' => 'Inventory saved']);
                               
                            }

    		}catch(\Exception $e){
	                return $e;
	        }
    }

    public function updatesubmittedInventory(Request $request){
    		
    		$date_create = date('Y-m-d H:i:s');
    		try{

    				 $validator = Validator::make($request->all(), [
                                        'inv_date' => 'required',
                                        'control_no' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'success','message'=>$validator->errors()->all()]);
                        }

                       

                        $inv = receivinginventory::where('id',$request->input('recid'))->first();

                        $inv->control_no = $request->input('control_no');
                        $inv->inventory_date = $request->input('inv_date');
                        $inv->updated_at = $date_create;
                        $inv->save();

                        $frm = submittedform::where('receiving_id',$request->input('recid'))->first();
                        $frm->inventory_date = $request->input('inv_date');
                        $frm->save();

                        return response(['status'=>'success', 'message' => 'Inventory updated']);
                               
                            

    		}catch(\Exception $e){
	                return $e;
	        }
    }

    public function addform(Request $request){

        $date_create = date('Y-m-d H:i:s');
            try{

                     $validator = Validator::make($request->all(), [
                                        'form' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        $store = receivinginventory::select('id')->where('store', $request->input('store'))
                        										->where('inventory_date', $request->input('inv_date'))
                        										->first();
                       

                        $form = submittedform::select('formname')->where('receiving_id', $store->id)
                                                        ->where('formname', $request->input('form'))
                                                        ->where('inventory_date', $request->input('inv_date'))
                                                        ->get();

                         if(count($form) > 0){

                         	return response(['status'=>'error', 'message' => 'Form already recorded']);
                         }else{

                         	 $formsubmit = submittedform::create([
                                            'receiving_id' => $store->id,
                                            'formname' => $request->input('form'),
                                            'inventory_date' => $request->input('inv_date'), 
                                            'remarks' => $request->input('remarks'),        
                                ]);

                                return response(['status'=>'success', 'message' => 'Form saved']);
                         }


            }catch(\Exception $e){
                    return $e;
            }

    }

    public function displayInventory(){

    	try{

    		$details = DB::table('receivinginventories')
						->leftJoin('storemapping', 'storemapping.store_code','receivinginventories.store')
						->Select('receivinginventories.id','receivinginventories.store','receivinginventories.supervisor','receivinginventories.promo','receivinginventories.inventory_date','receivinginventories.received_by','receivinginventories.created_at','receivinginventories.store_name','storemapping.promo as diser','receivinginventories.control_no')
						->get();
                   
         return (DataTables::of($details)
                            ->addColumn('action', function($details){
                                return ' <button class="btn btn-primary" title="view"  id="'.$details->id.'@'.$details->store.'@'.$details->inventory_date.'@'.$details->store_name.'@'.$details->supervisor.'@'.$details->promo.'@'.$details->diser.'@'.$details->control_no.'" onclick="getInfo(this.id)" data-toggle="modal" data-target="#modModal" style="cursor:pointer;"><i class="fa fa-edit"></i></button>';
                            })
                            ->make(true));

    	}catch(\Exception $e){
	         return $e;
	    }
    }

    public function displayformsubmitted($param){

    	$params = explode("@",$param);
    	$store = $params[0];
    	$inv_date = $params[1];

    	 $store = receivinginventory::select('id')->where('store', $store)
                        										->where('inventory_date', $inv_date)
                        										->first();

    	try{

    		$details = submittedform::select('id','formname')->where('receiving_id',$store->id)->get();
                   
         return (DataTables::of($details)
                            ->addColumn('action', function($details){
                                return ' <button class="btn btn-danger" title="view"  id="'.$details->id.'" onclick="removeform(this.id)"  style="cursor:pointer;"><i class="fa fa-trash"></i></button>';
                            })
                            ->make(true));

    	}catch(\Exception $e){
	         return $e;
	    }
    }



    public function updateInventory(Request $request){
    		
    		$date_create = date('Y-m-d H:i:s');
    		try{

    				 $validator = Validator::make($request->all(), [
                                        'store' => 'required',
                                        'inv_date' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'success','message'=>$validator->errors()->all()]);
                        }

                     

                       $inv = receivinginventory::where('id','=', $request->input('storeid'))->first();

				        $inv->store = $request->input('store');
			        	$inv->inventory_date = $request->input('inv_date');
			        	$inv->store_name = $request->input('storename');
			        	$inv->updated_at = $date_create;
				        $inv->save();

                        return response(['status'=>'success', 'message' => 'Inventory saved']);
                               
                            

    		}catch(\Exception $e){
	                return $e;
	        }
    }

    public function deleteInventory($id){

    	$inv = receivinginventory::findOrFail($id);

        if($inv->delete()){
                return response(['status'=>'success', 'message' => 'Inventory form removed']);
        }
    }

     public function removeForm($id){

    	$frm = submittedform::findOrFail($id);

        if($frm->delete()){
                return response(['status'=>'success', 'message' => 'Inventory form removed']);
        }
    }

    public function deleteInventorySubmitted($id){

    	

    	$getData = receivinginventory::select('id','inventory_date')->where('id', $id)->first();
    	$form = submittedform::where('receiving_id', $id)
    							->where('inventory_date', $getData->inventory_date)
    							->delete();

    	$inv = receivinginventory::findOrFail($id);

        if($inv->delete()){
                return response(['status'=>'success', 'message' => 'Inventory form removed']);
        }
    }

    public function getMonitoring($year){

        $param = explode('@', $year);

    	try{


            $details =  DB::table('receivinginventories')
                                ->join(DB::raw("(SELECT x.`receiving_id`,xx.`store`,xx.`store_name`,xx.`created_at`,COUNT(xx.created_at) AS tot FROM form_submitted X,receivinginventories xx 
                                    WHERE x.`receiving_id` = xx.id 
                                    AND YEAR(xx.created_at) = '".$param[1]."'
                                    AND x.formname  = '".$param[0]."'
                                    group by xx.created_at) AS b"), 'b.receiving_id', '=', 'receivinginventories.id')
                                ->Select("b.store","b.store_name",
                                            DB::raw('SUM(IF(MONTH(b.`created_at`) = 1, b.tot, 0)) AS JANUARY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 2, b.tot, 0)) AS FEBRUARY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 3, b.tot, 0)) AS MARCH'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 4, b.tot, 0)) AS APRIL'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 5, b.tot, 0)) AS MAY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 6, b.tot, 0)) AS JUNE'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 7, b.tot, 0)) AS JULY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 8, b.tot, 0)) AS AUGUST'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 9, b.tot, 0)) AS SEPTEMBER'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 10, b.tot, 0)) AS OCTOBER'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 11, b.tot, 0)) AS NOVEMBER'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 12, b.tot, 0)) AS DECEMBER'))
                                ->groupBy(DB::raw('b.store'))
                                ->get();
                     
         return (DataTables::of($details)->make(true));

    	}catch(\Exception $e){
	         return $e;
	    }

    }

	public function downloadReport($param){

        $param = explode('@', $param);

		try{

				$details =  DB::table('receivinginventories')
                                ->join(DB::raw("(SELECT x.`receiving_id`,xx.`store`,xx.`store_name`,xx.`created_at`,COUNT(xx.created_at) AS tot FROM form_submitted X,receivinginventories xx 
                                    WHERE x.`receiving_id` = xx.id 
                                    AND YEAR(xx.created_at) = '".$param[1]."'
                                    AND x.formname  = '".$param[0]."'
                                    group by xx.created_at) AS b"), 'b.receiving_id', '=', 'receivinginventories.id')
                                ->Select("b.store","b.store_name",
                                            DB::raw('SUM(IF(MONTH(b.`created_at`) = 1, b.tot, 0)) AS JANUARY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 2, b.tot, 0)) AS FEBRUARY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 3, b.tot, 0)) AS MARCH'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 4, b.tot, 0)) AS APRIL'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 5, b.tot, 0)) AS MAY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 6, b.tot, 0)) AS JUNE'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 7, b.tot, 0)) AS JULY'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 8, b.tot, 0)) AS AUGUST'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 9, b.tot, 0)) AS SEPTEMBER'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 10, b.tot, 0)) AS OCTOBER'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 11, b.tot, 0)) AS NOVEMBER'),
                                            DB::raw('SUM(IF(MONTH(b.created_at) = 12, b.tot, 0)) AS DECEMBER'))
                                ->groupBy(DB::raw('b.store'))
			       				->get()->toArray();

			       	$details_array[] = array("STORE CODE",
			       							"STORE",
											"JANUARY",
											"FEBRUARY",
											"MARCH",
											"APRIL",
											"MAY",
											"JUNE",
											"JULY",
											"AUGUST",
											"SEPTEMBER",
											"OCTOBER",
											"NOVEMBER",
											"DECEMBER");

			       	foreach ($details as $data) {
				       		$details_array[] = array("STORE CODE" => $data->store,
				       						"STORE" => $data->store_name,
											"JANUARY" => $data->JANUARY,
											"FEBRUARY" => $data->FEBRUARY,
											"MARCH" => $data->MARCH,
											"APRIL" => $data->APRIL,
											"MAY" => $data->MAY,
											"JUNE" => $data->JUNE,
											"JULY" => $data->JULY,
											"AUGUST" => $data->AUGUST,
											"SEPTEMBER" => $data->SEPTEMBER,
											"OCTOBER"  => $data->OCTOBER, 
											"NOVEMBER"  => $data->NOVEMBER,
											"DECEMBER"  => $data->DECEMBER);
				       	}

                        $title = '';

                          if($param[0] == 'ORO-FO'){
                                 $title = 'ORO FORM';
                            }

                            if($param[0] == 'OMG-NA'){
                                 $title = 'OMG-NAIL FORM';
                            }

                            if($param[0] == 'OMG-HA'){
                                 $title = 'OMG-HAIR FORM';
                            }

				       	Excel::create('Inventory Monitoring',function($excel) use ($details_array){
				       		$excel->setTitle('Inventory Monitoring ');
				       		$excel->sheet('Inventory Monitoring',function($sheet)
				       			use ($details_array){
				       				$sheet->fromArray($details_array, null, 'A1', false, false);
				       		});

				       	})->download('csv');

		}catch(\Exception $e){
			return $e;
		}
	}

}
