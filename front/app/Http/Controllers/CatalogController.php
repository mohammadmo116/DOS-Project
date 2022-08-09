<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;


class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {


      if(Cache::get('flag'))
        {
            $response= Http::get('catalog:8000/api/getBooks');
         Cache::set('flag',0); }
        else
        { $response= Http::get('catalogr:8000/api/getBooks');
            Cache::set('flag',1);  }

        Log::info("(Front-end Server) -> getting all books -> " . $response->body());
        Log::channel('stderr')->info("(Front-end Server) -> getting all books -> " . $response->body()."\n");
        return $response->json();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    //    Cache::set('flag',1);
    //    return Cache::get('flag');

        // $response= Http::get('catalog:8000/api/getBooks');
        // return $response->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {    if(Cache::get('flag'))
        {
            $response= Http::get('catalog:8000/api/book/'.$id);
         Cache::set('flag',0); }
        else
        {   $response= Http::get('catalogr:8000/api/book/'.$id);
            Cache::set('flag',1);  }


        if($response->status()==404)
       { Log::error('(Front-end server) book was not found', ['id' => $id]);
        Log::channel('stderr')->error('(Front-end server) book was not found book', ['id' => $id]);
        return "book is not found";}
        else
        {  Log::info('(Front-end server) book was found', ['id' => $id]);
            Log::channel('stderr')->info('(Front-end server) book was found', ['id' => $id]);
            return $response->json();}

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if(Cache::get('flag'))
        {
            $response= Http::put('catalog:8000/api/bookcost/'.$id,['cost'=>$request->post('cost')]);

         Cache::set('flag',0); }
        else
        {   $response= Http::put('catalogr:8000/api/bookcost/'.$id,['cost'=>$request->post('cost')]);

            Cache::set('flag',1);  }

        if($response->status()==404)
        {
            Log::error('(Front-end server) book was not found', ['id' => $id]);
            Log::channel('stderr')->error('(Front-end server) book was not found book', ['id' => $id]);
            return "book is not found";}
        else
        {
             Log::info('(Front-end server) the book has been updated, the new cost is '.  $response->json('cost'), ['id' => $id]);
             Log::channel('stderr')->info('(Front-end server) the book has been updated, the new cost is '.  $response->json('cost'), ['id' => $id]);
        return "the book has been updated, the new cost is ".  $response->json('cost');
    }

    }

    public function search($topic)
    {
        if(Cache::get('flag'))
        {
            $response= Http::get('catalog:8000/api/search?topic='.$topic);

         Cache::set('flag',0); }
        else
        {$response= Http::get('catalogr:8000/api/search?topic='.$topic);

            Cache::set('flag',1);  }


        if($response->json('error'))
       {  log::error('(front-end Server) -> no books found (search='.$topic.')');
          Log::channel('stderr')->error('(front-end Server) -> no books found (search='.$topic.")\n");
        return $response->json('error');}
        else
        { log::info('(front-end Server) -> books found (search='.$topic .') books-> '.$response->body());
        Log::channel('stderr')->info('(front-end Server) -> books found (search='.$topic .') books-> '.$response->body()."\n");
        return $response->json();}
    }


        public function countI($id)
    {
        if(Cache::get('flag'))
        {
            $response= Http::put('catalog:8000/api/addBook/'.$id);

         Cache::set('flag',0); }
        else
        {  $response= Http::put('catalogr:8000/api/addBook/'.$id);

            Cache::set('flag',1);  }

        if($response->status()==404)
        {log::error('(front-end Server) -> book is not found',['id'=>$id]);
            Log::channel('stderr')->error('(front-end Server) -> book is not found',['id'=>$id]);
            return "book is not found";
        }
        else
        {
            log::info('(front-end Server) -> the book has been added',['id'=>$id]);
            Log::channel('stderr')->info('(front-end Server) ->  the book has been added',['id'=>$id]);
        return "the book has been added";
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
