<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;


class PagesController extends Controller
{
    public function index(){
    	return view('pages.index');
    }

    public function home(){

        return view('pages.home');
    }

     public function CreateMenu(){

        return view('pages.menus');
    }

     public function MappedMenu(){

        return view('pages.accessrights');
    }

    
     public function CreateUser(){
         return view('pages.userregistration');
      
    }


    public function receivedInventory($id){
        return view('pages.receivedinventory')->with('mod_id',$id);
      
    }

     public function monitoringreport($id){
        return view('pages.inventorymonitoring')->with('mod_id',$id);
      
    }

    public function createPO($id){
        return view('pages.createpo')->with('mod_id',$id);
      
    }

    public function poServe($id){
        return view('pages.poserve')->with('mod_id',$id);
      
    }

    public function storeMapping($id){
        return view('pages.storemapping')->with('mod_id',$id);
      
    }

    public function actualinventory($id){
        return view('pages.inventory_adjustment')->with('mod_id',$id);
      
    }

    public function inventoryHistory($id){
        return view('pages.inventoryHistory')->with('mod_id',$id);
      
    }
}
   

