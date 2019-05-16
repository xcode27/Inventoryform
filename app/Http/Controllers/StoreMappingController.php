<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\storemapping;
use App\form;
use App\assign_diser;

class StoreMappingController extends Controller
{
    //

    public function saveStore(Request $request){

    	$date_create = date('Y-m-d H:i:s');
    		try{

    				 $validator = Validator::make($request->all(), [
    				 					'storecode' => 'required',
                                        'supervisor' => 'required',
                                        'area' => 'required',
                                        'submisiondate' => 'required',
                                      
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        if($request->input('diser') == "[{ 'Name':''}]"){
                                return response(['status'=>'error', 'message' => 'Promo is required']);
                        }

                        if($request->input('submisiondate') == "[{ 'Date':''}]"){
                                return response(['status'=>'error', 'message' => 'Submission date is required']);
                        }

                        $store = storemapping::select('store_code')->where('store_code', $request->input('storecode'))
                        												->get();

                         if(count($store) > 0){
                                return response(['status'=>'error', 'message' => 'Store already recorded']);
                            }else{

                                $user = storemapping::create([
                                			'store_code' => $request->input('storecode'),
                                            'store_name' => $request->input('store_name'),
                                            'supcode' => $request->input('supcode'),
                                            'supervisor' => $request->input('supervisor'),
                                            'promo' => $request->input('diser'),
                                            'area' => $request->input('area'),
                                            'expected_submission' => $request->input('submisiondate'),
                                            'created_by' => $request->input('user'),
                                            'date_created' =>  $date_create,    
                                ]);
                                    
                                return response(['status'=>'success', 'message' => 'Store successfully saved. Please add form requirements. Thank you.']);
                                   
                            }

    		}catch(\Exception $e){
	                return $e;
	        }

    }

     public function saveForm(Request $request){

    	$date_create = date('Y-m-d H:i:s');
    		try{

    				 $validator = Validator::make($request->all(), [
                                        'supcode' => 'required',
    				 					'storecode' => 'required',
                                        'formname' => 'required',
                                        'formdes' => 'required',
                                        'frequency' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        $form = form::select('formname')->where('formname', $request->input('formname'))
                        								->where('storecode', $request->input('storecode'))
                                                        ->where('supcode', $request->input('supcode'))
                        								->get();

                         if(count($form) > 0){
                                return response(['status'=>'error', 'message' => 'Form already recorded']);
                            }else{

                                $user = form::create([
                                            'supcode' => $request->input('supcode'),
                                			'storecode' => $request->input('storecode'),
                                			'formcode' => $request->input('formcode'),
                                            'formname' => $request->input('formname'),
                                            'form_description' => $request->input('formdes'),
                                            'frequency' => $request->input('frequency'),
                                            'date_created' =>  $date_create,    
                                ]);

                                return response(['status'=>'success', 'message' => 'Form successfully saved.']);
                                   
                            }

    		}catch(\Exception $e){
	                return $e;
	        }

    }

    public function displayFormperOutlet($storecode){

        try{
            
                $details = form::select('id','storecode','formcode','formname','form_description','frequency')->where('storecode',$storecode)->get();
                       
                return (DataTables::of($details)
                                ->addColumn('action', function($details){
                                    return ' <button class="btn btn-danger"   id="'.$details->id.'" onclick="removeForm(this.id)"   style="cursor:pointer;"><i class="fa fa-trash"></i></button>';
                                })
                                ->make(true));
            

        }catch(\Exception $e){
             return $e;
        }
    }

    public function removeform($id){

    	$form = form::findOrFail($id);

        if($form->delete()){
                return response(['status'=>'success', 'message' => 'Form item removed']);
        }
    }

    public function displayStoreMapped(){

    	
    	try{
            
                $store = storemapping::select('id','store_code','supervisor','promo',DB::raw('(REPLACE((REPLACE((REPLACE((REPLACE(promo, "[{", "")),"}]","")),"{","")),"}","")) as promodiser'),'area','store_name','supcode','expected_submission')->get();
         
                return (DataTables::of($store)
                                ->addColumn('action', function($store){
                                    return ' <button class="btn btn-primary"   id="'.$store->id.'@'.$store->store_code.'@'.$store->supervisor.'@'.$store->promo.'@'.$store->area.'@'.$store->store_name.'@'.$store->supcode.'@'.$store->expected_submission.'" onclick="getDetails(this.id)"   style="cursor:pointer;"><i class="fa fa-edit"></i></button>';
                                })
                                ->make(true));
            

        }catch(\Exception $e){
             return $e;
        }

    }

     public function updateStore(Request $request){

    	$date_create = date('Y-m-d H:i:s');
    		try{


    				 $validator = Validator::make($request->all(), [
    				 					'storecode' => 'required',
                                        'supervisor' => 'required',
                                        'area' => 'required',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        if($request->input('diser') == "[{ 'Name':''}]"){
                                return response(['status'=>'error', 'message' => 'Promo is required']);
                        }

                        if($request->input('submisiondate') == "[{ 'Date':''}]"){
                                return response(['status'=>'error', 'message' => 'Submission date is required']);
                        }

                        $store = storemapping::where('id', $request->input('recid'))->first();


                        $store->store_code = $request->input('storecode');
                        $store->store_name = $request->input('store_name');
                        $store->supcode = $request->input('supcode');
                        $store->supervisor = $request->input('supervisor');
                        $store->promo = $request->input('diser');
                        $store->area = $request->input('area');
                        $store->expected_submission = $request->input('submisiondate');
                        $store->date_modified = $date_create;
                        $store->save();
                        
                        return response(['status'=>'success', 'message' => 'Store successfully updated.']);
                                   
                            

    		}catch(\Exception $e){
	                return $e;
	        }

    }

    public function removestore($id){

    	$storeid = storemapping::select('store_code')->where('id', $id)->first();


        $form = form::where('storecode', $storeid->store_code)->delete();

    	$store = storemapping::findOrFail($id);

        if($store->delete()){
                return response(['status'=>'success', 'message' => 'Store mapped successfully removed']);
        }


    }

    public function getForm($store){

        $details = form::select('formcode','formname')->where('storecode',$store)->get();

        return \Response::json($details);

    }

    


}
